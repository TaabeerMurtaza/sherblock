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

	private bool $hydrated = false;

	public function __construct(
		private readonly BlockRegistry $registry,
		private readonly BlockProviderManager $providerManager,
	) {
	}

	/**
	 * {@inheritDoc}
	 */
	public function findAll(): array {
		$this->hydrate();

		$blocks = $this->registry->all();

		usort(
			$blocks,
			static fn ( Block $a, Block $b ): int => strcasecmp( $a->getTitle(), $b->getTitle() )
		);

		return $blocks;
	}

	/**
	 * {@inheritDoc}
	 */
	public function findByName( string $name ): ?Block {
		$this->hydrate();

		return $this->registry->get( $name );
	}

	/**
	 * {@inheritDoc}
	 */
	public function findByProvider( string $providerId ): array {
		$this->hydrate();

		return array_values(
			array_filter(
				$this->registry->all(),
				static fn ( Block $block ): bool => $block->getProvider() === $providerId
			)
		);
	}

	/**
	 * {@inheritDoc}
	 */
	public function findFiltered( ?string $category = null, ?string $provider = null, ?string $search = null ): array {
		$blocks = $this->findAll();

		if ( null !== $category ) {
			$blocks = array_values(
				array_filter(
					$blocks,
					static fn ( Block $block ): bool => $block->getCategory() === $category
				)
			);
		}

		if ( null !== $provider ) {
			$blocks = array_values(
				array_filter(
					$blocks,
					static fn ( Block $block ): bool => $block->getProvider() === $provider
				)
			);
		}

		if ( null !== $search ) {
			$blocks = array_values(
				array_filter(
					$blocks,
					static fn ( Block $block ): bool => false !== stripos( $block->getName(), $search )
				)
			);
		}

		return $blocks;
	}

	/**
	 * {@inheritDoc}
	 */
	public function getDistinctCategories(): array {
		$this->hydrate();

		$categories = array_unique(
			array_map(
				static fn ( Block $block ): string => $block->getCategory(),
				$this->registry->all()
			)
		);

		$categories = array_values(
			array_filter(
				$categories,
				static fn ( string $category ): bool => '' !== $category
			)
		);

		sort( $categories, SORT_STRING | SORT_FLAG_CASE );

		return $categories;
	}

	/**
	 * {@inheritDoc}
	 */
	public function getDistinctProviders(): array {
		$this->hydrate();

		$providers = array_unique(
			array_map(
				static fn ( Block $block ): string => $block->getProvider(),
				$this->registry->all()
			)
		);

		sort( $providers, SORT_STRING | SORT_FLAG_CASE );

		return array_values( $providers );
	}

	private function hydrate(): void {
		if ( $this->hydrated ) {
			return;
		}

		$this->registry->registerMany( $this->providerManager->discoverAll() );
		$this->hydrated = true;
	}
}
