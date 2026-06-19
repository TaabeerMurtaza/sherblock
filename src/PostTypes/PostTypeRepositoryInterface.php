<?php
/**
 * Post type data access contract.
 *
 * @package SherBlock\PostTypes
 */

declare(strict_types=1);

namespace SherBlock\PostTypes;

/**
 * Abstracts queries for Gutenberg-enabled post types.
 */
interface PostTypeRepositoryInterface {

	/**
	 * @return PostType[]
	 */
	public function findAllBlockEnabled(): array;

	public function findByName( string $name ): ?PostType;
}
