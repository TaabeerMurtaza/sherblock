<?php
/**
 * Aggregates block discovery providers.
 *
 * @package SherBlock\Providers
 */

declare(strict_types=1);

namespace SherBlock\Providers;

use SherBlock\Blocks\Block;

/**
 * Registers providers and merges their discovered blocks.
 */
final class BlockProviderManager {

	/** @var array<string, BlockProviderInterface> */
	private array $providers = [];

	public function register( BlockProviderInterface $provider ): void {
		$this->providers[ $provider->getId() ] = $provider;
	}

	/**
	 * @return BlockProviderInterface[]
	 */
	public function all(): array {
		return array_values( $this->providers );
	}

	public function get( string $id ): ?BlockProviderInterface {
		return $this->providers[ $id ] ?? null;
	}

	/**
	 * @return Block[]
	 */
	public function discoverAll(): array {
		// TODO: Call discoverBlocks() on each available provider and merge results.
		$blocks = [];

		foreach ( $this->providers as $provider ) {
			if ( ! $provider->isAvailable() ) {
				continue;
			}

			$blocks = array_merge( $blocks, $provider->discoverBlocks() );
		}

		return $blocks;
	}
}
