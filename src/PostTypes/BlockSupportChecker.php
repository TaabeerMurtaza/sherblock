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
		// TODO: Check post type supports 'editor' and use_block_editor_for_post_type().
		unset( $postType );

		return false;
	}

	public function supportsCustomFields( string $postType ): bool {
		// TODO: Useful for ACF/Carbon meta-backed blocks on hybrid post types.
		unset( $postType );

		return false;
	}
}
