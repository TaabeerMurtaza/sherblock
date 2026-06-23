<?php
/**
 * Determines whether a post type supports the block editor.
 *
 * @package SherBlock\PostTypes
 */

declare(strict_types=1);

namespace SherBlock\PostTypes;

/**
 * Wraps WordPress APIs for editor/block support detection.
 */
final class BlockSupportChecker {

	public function supportsBlocks( string $postType ): bool {
		if ( ! post_type_exists( $postType ) ) {
			return false;
		}

		return use_block_editor_for_post_type( $postType );
	}

	public function supportsCustomFields( string $postType ): bool {
		if ( ! post_type_exists( $postType ) ) {
			return false;
		}

		return post_type_supports( $postType, 'custom-fields' );
	}
}
