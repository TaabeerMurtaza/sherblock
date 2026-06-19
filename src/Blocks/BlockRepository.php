<?php
/**
 * Block repository implementation.
 *
 * @package SherBlock\Blocks
 */

declare(strict_types=1);

namespace SherBlock\Blocks;

use SherBlock\Providers\BlockProviderManager;

/**
 * Aggregates discovered blocks from providers and the in-memory registry.
 */
final class BlockRepository implements BlockRepositoryInterface {

	public function __construct(
		private readonly BlockRegistry $registry,
		private readonly BlockProviderManager $providerManager,
	) {
	}

	/**
	 * {@inheritDoc}
	 */
	public function findAll(): array {
		// TODO: Merge provider discoveries into registry, then return registry->all().
		return $this->registry->all();
	}

	/**
	 * {@inheritDoc}
	 */
	public function findByName( string $name ): ?Block {
		// TODO: Ensure registry is hydrated before lookup.
		return $this->registry->get( $name );
	}

	/**
	 * {@inheritDoc}
	 */
	public function findByProvider( string $providerId ): array {
		// TODO: Filter blocks where Block::getProvider() matches $providerId.
		return [];
	}
}
