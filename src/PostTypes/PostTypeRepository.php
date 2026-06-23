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
		$objects = get_post_types( [], 'objects' );
		$post_types = [];

		foreach ( $objects as $object ) {
			if ( ! $object->public ) {
				continue;
			}

			if ( ! $this->supportChecker->supportsBlocks( $object->name ) ) {
				continue;
			}

			$post_types[] = $this->hydrate( $object );
		}

		usort(
			$post_types,
			static fn( PostType $a, PostType $b ): int => strcasecmp( $a->getLabel(), $b->getLabel() )
		);

		return $post_types;
	}

	/**
	 * {@inheritDoc}
	 */
	public function findByName( string $name ): ?PostType {
		$object = get_post_type_object( $name );

		if ( ! $object instanceof \WP_Post_Type ) {
			return null;
		}

		if ( ! $this->supportChecker->supportsBlocks( $object->name ) ) {
			return null;
		}

		return $this->hydrate( $object );
	}

	private function hydrate( \WP_Post_Type $object ): PostType {
		$label = $object->labels->name ?? $object->label;

		return new PostType(
			$object->name,
			is_string( $label ) ? $label : $object->name,
			$this->supportChecker->supportsBlocks( $object->name ),
			(bool) $object->public,
		);
	}
}
