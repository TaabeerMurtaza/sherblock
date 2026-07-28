<?php
/**
 * AJAX re-index handler.
 *
 * @package SherBlock\Index
 */

declare(strict_types=1);

namespace SherBlock\Index;

use SherBlock\Database\Schema;
use SherBlock\PostTypes\BlockSupportChecker;

/**
 * Handles batched AJAX re-indexing of all content.
 */
final class ReindexHandler {

	public function __construct(
		private readonly IndexBuilder $indexBuilder,
		private readonly IndexRepositoryInterface $indexRepository,
		private readonly BlockSupportChecker $blockSupportChecker,
		private readonly Schema $schema,
	) {
	}

	public function register(): void {
		add_action( 'wp_ajax_sherblock_reindex', [ $this, 'handleReindex' ] );
	}

	public function handleReindex(): void {
		if ( ! check_ajax_referer( 'sherblock_nonce', 'nonce', false ) ) {
			wp_send_json_error( [ 'message' => __( 'Invalid security token.', 'sherblock' ) ] );
		}

		if ( ! current_user_can( 'manage_options' ) ) {
			wp_send_json_error( [ 'message' => __( 'You do not have permission to do this.', 'sherblock' ) ] );
		}

		$offset = isset( $_POST['offset'] ) ? absint( $_POST['offset'] ) : 0;
		$batch  = 50;

		$post_types = get_post_types( [ 'public' => true ], 'names' );
		$block_types = [];

		foreach ( $post_types as $pt ) {
			if ( $this->blockSupportChecker->supportsBlocks( $pt ) ) {
				$block_types[] = $pt;
			}
		}

		$args = [
			'post_type'      => $block_types,
			'post_status'    => 'any',
			'posts_per_page' => $batch,
			'offset'         => $offset,
			'fields'         => 'ids',
			'no_found_rows'  => false,
		];

		$query = new \WP_Query( $args );
		$total = (int) $query->found_posts;
		$posts = $query->posts;

		foreach ( $posts as $post_id ) {
			$this->indexPost( (int) $post_id );
		}

		$processed = $offset + count( $posts );
		$done      = $processed >= $total;

		wp_send_json_success( [
			'processed'   => $processed,
			'total'       => $total,
			'done'        => $done,
			'next_offset' => $done ? 0 : $processed,
		] );
	}

	private function indexPost( int $post_id ): void {
		$post = get_post( $post_id );

		if ( ! $post instanceof \WP_Post ) {
			return;
		}

		if ( ! $this->blockSupportChecker->supportsBlocks( $post->post_type ) ) {
			return;
		}

		$this->indexRepository->deleteByPost( $post_id );

		if ( in_array( $post->post_status, [ 'auto-draft', 'trash' ], true ) ) {
			return;
		}

		$blocks = $this->indexBuilder->buildFromContent( $post->post_content, $post_id );

		foreach ( $blocks as $block ) {
			$this->indexRepository->store( $post_id, $block['block_name'] );
		}
	}
}
