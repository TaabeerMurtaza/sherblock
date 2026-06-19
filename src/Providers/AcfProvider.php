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
		// TODO: Check for ACF and acf_get_block_types().
		return function_exists( 'acf_get_block_types' );
	}

	/**
	 * {@inheritDoc}
	 */
	public function discoverBlocks(): array {
		// TODO: Map ACF block definitions to Block value objects.
		return [];
	}
}
