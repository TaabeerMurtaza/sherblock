<?php
/**
 * Block usage index persistence contract.
 *
 * @package SherBlock\Index
 */

declare(strict_types=1);

namespace SherBlock\Index;

/**
 * Abstracts custom table reads/writes for block-to-post relationships.
 */
interface IndexRepositoryInterface {

	/**
	 * @param array<string, mixed> $meta Optional attrs hash, inner block path, etc.
	 */
	public function store( int $postId, string $blockName, array $meta = [] ): void;

	public function deleteByPost( int $postId ): void;

	/**
	 * @return array<int, array<string, mixed>>
	 */
	public function findByBlock( string $blockName ): array;

	/**
	 * @return array<int, array<string, mixed>>
	 */
	public function findByPost( int $postId ): array;

	/**
	 * @return array<int, array<string, mixed>>
	 */
	public function findByPostType( string $postType ): array;
}
