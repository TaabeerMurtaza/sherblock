<?php
/**
 * Lazy Blocks discovery provider.
 *
 * @package SherBlock\Providers
 */

declare(strict_types=1);

namespace SherBlock\Providers;

use SherBlock\Blocks\Block;

/**
 * Discovers blocks registered through the Lazy Blocks plugin.
 */
final class LazyBlocksProvider implements BlockProviderInterface {

	public function getId(): string {
		return 'lazy-blocks';
	}

	public function isAvailable(): bool {
		return function_exists( 'lazyblocks' );
	}

	/**
	 * {@inheritDoc}
	 */
	public function discoverBlocks(): array {
		$blocks_service = lazyblocks()->blocks();
		$all_controls   = lazyblocks()->controls()->get_controls();

		$posts = get_posts(
			[
				'post_type'      => 'lazyblocks',
				'post_status'    => [ 'publish', 'draft', 'pending', 'private' ],
				'posts_per_page' => -1,
				'numberposts'    => -1,
			]
		);

		$registry = class_exists( \WP_Block_Type_Registry::class )
			? \WP_Block_Type_Registry::get_instance()
			: null;

		$blocks = [];

		foreach ( $posts as $post ) {
			$lazy_block = $blocks_service->marshal_block_data_with_controls(
				$post->ID,
				$post->post_title,
				null,
				$all_controls
			);

			if ( ! is_array( $lazy_block ) ) {
				continue;
			}

			$mapped = $this->mapToBlock( $lazy_block, $registry, $post->ID, $post->post_status );

			if ( '' !== $mapped->getName() ) {
				$blocks[] = $mapped;
			}
		}

		return $blocks;
	}

	/**
	 * @param array<string, mixed> $lazy_block
	 */
	private function mapToBlock(
		array $lazy_block,
		?\WP_Block_Type_Registry $registry,
		int $source_post_id,
		string $post_status,
	): Block {
		$name = isset( $lazy_block['slug'] ) ? (string) $lazy_block['slug'] : '';

		$title = isset( $lazy_block['title'] ) ? (string) $lazy_block['title'] : $name;

		if ( 'publish' !== $post_status ) {
			$title .= ' (' . __( 'Inactive', 'sherblock' ) . ')';
		}

		$category = isset( $lazy_block['category'] ) ? (string) $lazy_block['category'] : 'common';

		$attributes = [];
		$supports   = is_array( $lazy_block['supports'] ?? null ) ? $lazy_block['supports'] : [];

		if ( null !== $registry && '' !== $name && 'publish' === $post_status ) {
			$block_type = $registry->get_registered( $name );

			if ( $block_type instanceof \WP_Block_Type ) {
				$attributes = is_array( $block_type->attributes ) ? $block_type->attributes : [];
				$supports   = is_array( $block_type->supports ) ? $block_type->supports : $supports;
			}
		}

		return new Block(
			$name,
			$title,
			$category,
			$this->getId(),
			$attributes,
			$supports,
			$source_post_id,
		);
	}
}
