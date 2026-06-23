<?php
/**
 * Block data access contract.
 *
 * @package SherBlock\Blocks
 */

declare(strict_types=1);

namespace SherBlock\Blocks;

/**
 * Abstracts block discovery queries. Rendering belongs in admin pages, not here.
 */
interface BlockRepositoryInterface {

	/**
	 * @return Block[]
	 */
	public function findAll(): array;

	public function findByName( string $name ): ?Block;

	/**
	 * @return Block[]
	 */
	public function findByProvider( string $providerId ): array;

	/**
	 * @return Block[]
	 */
	public function findFiltered( ?string $category = null, ?string $provider = null, ?string $search = null ): array;

	/**
	 * @return string[]
	 */
	public function getDistinctCategories(): array;

	/**
	 * @return string[]
	 */
	public function getDistinctProviders(): array;
}
