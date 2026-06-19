<?php
/**
 * Block usage lookup service.
 *
 * @package SherBlock\Blocks
 */

declare(strict_types=1);

namespace SherBlock\Blocks;

use SherBlock\Index\IndexRepositoryInterface;

/**
 * Answers "where is this block used?" using the indexed data store.
 */
final class BlockUsageFinder {

	public function __construct(
		private readonly IndexRepositoryInterface $indexRepository,
	) {
	}

	/**
	 * @return array<int, array<string, mixed>> Post records referencing the block.
	 */
	public function findPostsUsingBlock( string $blockName ): array {
		// TODO: Query index repository and map rows to lightweight post summaries.
		return $this->indexRepository->findByBlock( $blockName );
	}

	/**
	 * @return string[] Block names found in the given post.
	 */
	public function findBlocksInPost( int $postId ): array {
		// TODO: Return block names from index; fall back to live parse_blocks() if stale.
		$rows = $this->indexRepository->findByPost( $postId );

		return array_column( $rows, 'block_name' );
	}

	public function countUsages( string $blockName ): int {
		// TODO: Return total usage count from index.
		return count( $this->findPostsUsingBlock( $blockName ) );
	}
}
