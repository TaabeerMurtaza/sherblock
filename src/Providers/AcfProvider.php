<?php
/**
 * ACF block discovery provider.
 *
 * @package SherBlock\Providers
 */

declare(strict_types=1);

namespace SherBlock\Providers;

use SherBlock\Blocks\Block;

/**
 * Discovers blocks registered through Advanced Custom Fields acf_register_block_type().
 */
final class AcfProvider implements BlockProviderInterface {

	public function getId(): string {
		return 'acf';
	}

	public function isAvailable(): bool {
		return function_exists( 'acf_get_block_types' );
	}

	/**
	 * {@inheritDoc}
	 */
	public function discoverBlocks(): array {
		$acf_blocks = acf_get_block_types();

		if ( ! is_array( $acf_blocks ) ) {
			return [];
		}

		$blocks = [];

		foreach ( $acf_blocks as $acf_block ) {
			if ( ! is_array( $acf_block ) ) {
				continue;
			}

			$mapped = $this->mapToBlock( $acf_block );

			if ( '' !== $mapped->getName() ) {
				$blocks[] = $mapped;
			}
		}

		return $blocks;
	}

	/**
	 * @param array<string, mixed> $acf_block
	 */
	private function mapToBlock( array $acf_block ): Block {
		$name = isset( $acf_block['name'] ) ? (string) $acf_block['name'] : '';

		return new Block(
			$name,
			(string) ( $acf_block['title'] ?? $name ),
			(string) ( $acf_block['category'] ?? 'common' ),
			$this->getId(),
			is_array( $acf_block['attributes'] ?? null ) ? $acf_block['attributes'] : [],
			[],
		);
	}
}
