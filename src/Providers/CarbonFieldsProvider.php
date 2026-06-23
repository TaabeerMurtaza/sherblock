<?php
/**
 * Carbon Fields block discovery provider.
 *
 * @package SherBlock\Providers
 */

declare(strict_types=1);

namespace SherBlock\Providers;

use SherBlock\Blocks\Block;

/**
 * Discovers blocks registered via Carbon Fields block containers.
 *
 * Uses WP_Block_Type_Registry rather than Carbon Fields internals to avoid
 * autoloader conflicts with other plugins that ship their own Composer vendors.
 */
final class CarbonFieldsProvider implements BlockProviderInterface {

	private const BLOCK_NAME_PREFIX = 'carbon-fields/';

	public function getId(): string {
		return 'carbon-fields';
	}

	public function isAvailable(): bool {
		return class_exists( '\Carbon_Fields\Carbon_Fields' )
			&& class_exists( \WP_Block_Type_Registry::class );
	}

	/**
	 * {@inheritDoc}
	 */
	public function discoverBlocks(): array {
		$registry = \WP_Block_Type_Registry::get_instance();
		$blocks   = [];

		foreach ( $registry->get_all_registered() as $block_type ) {
			if ( ! str_starts_with( $block_type->name, self::BLOCK_NAME_PREFIX ) ) {
				continue;
			}

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
			is_string( $block_type->category ) ? $block_type->category : 'common',
			$this->getId(),
			is_array( $block_type->attributes ) ? $block_type->attributes : [],
			is_array( $block_type->supports ) ? $block_type->supports : [],
		);
	}
}
