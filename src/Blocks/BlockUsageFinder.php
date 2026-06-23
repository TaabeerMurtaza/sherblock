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
	 * @return PostBlockUsage[]
	 */
	public function findPostsUsingBlock( string $blockName ): array {
		$rows = $this->indexRepository->findByBlock( $blockName );

		return array_map(
			static fn ( array $row ): PostBlockUsage => new PostBlockUsage(
				(int) $row['post_id'],
				(string) $row['post_title'],
				(string) $row['post_type'],
				(string) $row['post_type_label'],
				(string) $row['post_status'],
				(int) $row['block_occurrences'],
				(int) $row['total_block_types'],
				(string) $row['edit_link'],
			),
			$rows
		);
	}

	/**
	 * @return string[] Block names found in the given post.
	 */
	public function findBlocksInPost( int $postId ): array {
		$rows = $this->indexRepository->findByPost( $postId );

		return array_values(
			array_unique(
				array_map(
					static fn ( array $row ): string => (string) ( $row['block_name'] ?? '' ),
					$rows
				)
			)
		);
	}

	public function countUsages( string $blockName ): int {
		return count( $this->findPostsUsingBlock( $blockName ) );
	}
}
