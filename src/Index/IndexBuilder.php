<?php
/**
 * Parses post content into indexable block usage records.
 *
 * @package SherBlock\Index
 */

declare(strict_types=1);

namespace SherBlock\Index;

/**
 * Uses WordPress parse_blocks() to extract block names and nested structure.
 */
final class IndexBuilder {

	/**
	 * @return array<int, array<string, mixed>> Rows ready for IndexRepositoryInterface::store().
	 */
	public function buildFromContent( string $content, int $postId ): array {
		// TODO: Call parse_blocks( $content ), walk nested innerBlocks, return flat usage rows.
		unset( $content, $postId );

		return [];
	}

	/**
	 * @param array<int, array<string, mixed>> $blocks Parsed block tree from parse_blocks().
	 * @return string[] Unique block names including nested blocks.
	 */
	private function collectBlockNames( array $blocks ): array {
		// TODO: Recursively collect blockName from each node and innerBlocks.
		return [];
	}
}
