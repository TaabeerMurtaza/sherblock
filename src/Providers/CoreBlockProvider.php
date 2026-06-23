<?php
/**
 * WordPress core block discovery provider.
 *
 * @package SherBlock\Providers
 */

declare(strict_types=1);

namespace SherBlock\Providers;

use SherBlock\Blocks\Block;

/**
 * Discovers blocks registered via register_block_type() / WP_Block_Type_Registry.
 */
final class CoreBlockProvider implements BlockProviderInterface {

	public function getId(): string {
		return 'core';
	}

	public function isAvailable(): bool {
		return class_exists( \WP_Block_Type_Registry::class );
	}

	/**
	 * {@inheritDoc}
	 */
	public function discoverBlocks(): array {
		$registry = \WP_Block_Type_Registry::get_instance();
		$blocks   = [];

		foreach ( $registry->get_all_registered() as $block_type ) {
			$blocks[] = $this->mapToBlock( $block_type );
		}

		return $blocks;
	}

	private function mapToBlock( \WP_Block_Type $block_type ): Block {
		$title = $block_type->title;

		if ( ! is_string( $title ) || '' === $title ) {
			$title = $block_type->name;
		}

		return new Block(
			$block_type->name,
			$title,
			is_string( $block_type->category ) ? $block_type->category : '',
			$this->getId(),
			is_array( $block_type->attributes ) ? $block_type->attributes : [],
			is_array( $block_type->supports ) ? $block_type->supports : [],
		);
	}
}
