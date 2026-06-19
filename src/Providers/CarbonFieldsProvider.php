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
 */
final class CarbonFieldsProvider implements BlockProviderInterface {

	public function getId(): string {
		return 'carbon-fields';
	}

	public function isAvailable(): bool {
		// TODO: Check for Carbon Fields block registration API.
		return class_exists( '\Carbon_Fields\Carbon_Fields' );
	}

	/**
	 * {@inheritDoc}
	 */
	public function discoverBlocks(): array {
		// TODO: Inspect Carbon Fields block registry and map to Block objects.
		return [];
	}
}
