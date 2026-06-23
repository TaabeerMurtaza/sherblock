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
	 * @return array<int, array{block_name: string}>
	 */
	public function buildFromContent( string $content, int $postId ): array {
		unset( $postId );

		$parsed = parse_blocks( $content );
		$names  = $this->collectBlockNames( $parsed );
		$rows   = [];

		foreach ( $names as $name ) {
			$rows[] = [ 'block_name' => $name ];
		}

		return $rows;
	}

	/**
	 * @param array<int, array<string, mixed>> $blocks Parsed block tree from parse_blocks().
	 * @return string[] Block names in document order, one entry per occurrence.
	 */
	private function collectBlockNames( array $blocks ): array {
		$names = [];

		foreach ( $blocks as $block ) {
			$block_name = $block['blockName'] ?? '';

			if ( is_string( $block_name ) && '' !== $block_name ) {
				$names[] = $block_name;
			}

			$inner = $block['innerBlocks'] ?? [];

			if ( is_array( $inner ) && [] !== $inner ) {
				$names = array_merge( $names, $this->collectBlockNames( $inner ) );
			}
		}

		return $names;
	}
}
