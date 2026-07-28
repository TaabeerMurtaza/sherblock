<?php
/**
 * Finds registered blocks with zero indexed usage.
 *
 * @package SherBlock\Blocks
 */

declare(strict_types=1);

namespace SherBlock\Blocks;

use SherBlock\Index\IndexRepositoryInterface;

/**
 * Cross-references discovered blocks with indexed usage to find unused blocks.
 */
final class UnusedBlockFinder {

	public function __construct(
		private readonly BlockRepositoryInterface $blockRepository,
		private readonly IndexRepositoryInterface $indexRepository,
	) {
	}

	/**
	 * @return Block[]
	 */
	public function findUnusedBlocks(): array {
		$all_blocks = $this->blockRepository->findAll();
		$top_blocks = $this->indexRepository->getTopBlocks( 10000 );
		$used_names = array_column( $top_blocks, 'block_name' );

		return array_values(
			array_filter(
				$all_blocks,
				static fn( Block $block ): bool => ! in_array( $block->getName(), $used_names, true )
			)
		);
	}

	public function countUnused(): int {
		return count( $this->findUnusedBlocks() );
	}
}
