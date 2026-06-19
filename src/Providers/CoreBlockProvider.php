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
		// TODO: Always available when block editor APIs exist.
		return function_exists( 'WP_Block_Type_Registry' );
	}

	/**
	 * {@inheritDoc}
	 */
	public function discoverBlocks(): array {
		// TODO: Read WP_Block_Type_Registry::get_instance()->get_all_registered().
		return [];
	}

	/**
	 * @param object $blockType WP_Block_Type instance.
	 */
	private function mapToBlock( object $blockType ): Block {
		// TODO: Map registry entry to Block value object with provider id "core".
		unset( $blockType );

		return new Block( '', '', '', $this->getId() );
	}
}
