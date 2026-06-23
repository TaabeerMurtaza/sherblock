<?php
/**
 * Block usage indexer orchestrator.
 *
 * @package SherBlock\Index
 */

declare(strict_types=1);

namespace SherBlock\Index;

use SherBlock\Database\Schema;
use SherBlock\PostTypes\BlockSupportChecker;

/**
 * Coordinates parsing post content and persisting block usage relationships.
 */
final class Indexer {

	public function __construct(
		private readonly IndexBuilder $builder,
		private readonly IndexRepositoryInterface $repository,
		private readonly BlockSupportChecker $supportChecker,
		private readonly Schema $schema,
	) {
	}

	/**
	 * Re-index block usage for a single post.
	 */
	public function indexPost( int $postId ): void {
		$post = get_post( $postId );

		if ( ! $post instanceof \WP_Post ) {
			return;
		}

		if ( ! $this->supportChecker->supportsBlocks( $post->post_type ) ) {
			return;
		}

		if ( in_array( $post->post_status, [ 'auto-draft', 'trash' ], true ) ) {
			$this->repository->deleteByPost( $postId );

			return;
		}

		$this->repository->deleteByPost( $postId );

		$rows = $this->builder->buildFromContent( $post->post_content, $postId );

		foreach ( $rows as $row ) {
			$this->repository->store( $postId, $row['block_name'] );
		}
	}

	/**
	 * Full site re-index. Intended for WP-Cron or manual admin action.
	 */
	public function indexAll(): void {
		$post_types = array_filter(
			get_post_types(),
			fn ( string $post_type ): bool => $this->supportChecker->supportsBlocks( $post_type )
		);

		foreach ( $post_types as $post_type ) {
			$post_ids = get_posts(
				[
					'post_type'      => $post_type,
					'post_status'    => [ 'publish', 'draft', 'private', 'pending', 'future' ],
					'posts_per_page' => -1,
					'fields'         => 'ids',
					'orderby'        => 'ID',
					'order'          => 'ASC',
				]
			);

			foreach ( $post_ids as $post_id ) {
				$this->indexPost( (int) $post_id );
			}
		}
	}

	/**
	 * Remove all indexed data (e.g. before rebuild).
	 */
	public function purge(): void {
		global $wpdb;

		$table = $this->schema->getBlockUsageTableName();

		// phpcs:ignore WordPress.DB.PreparedSQL.InterpolatedNotPrepared -- Table name from schema helper.
		$wpdb->query( "TRUNCATE TABLE {$table}" );
	}
}
