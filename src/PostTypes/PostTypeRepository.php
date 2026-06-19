<?php
/**
 * Post type repository implementation.
 *
 * @package SherBlock\PostTypes
 */

declare(strict_types=1);

namespace SherBlock\PostTypes;

/**
 * Discovers registered post types and filters to those supporting the block editor.
 */
final class PostTypeRepository implements PostTypeRepositoryInterface {

	public function __construct(
		private readonly BlockSupportChecker $supportChecker,
	) {
	}

	/**
	 * {@inheritDoc}
	 */
	public function findAllBlockEnabled(): array {
		// TODO: Iterate get_post_types(), wrap each in PostType when supportsBlocks() is true.
		return [];
	}

	/**
	 * {@inheritDoc}
	 */
	public function findByName( string $name ): ?PostType {
		// TODO: Resolve single post type object via get_post_type_object().
		unset( $name );

		return null;
	}
}
