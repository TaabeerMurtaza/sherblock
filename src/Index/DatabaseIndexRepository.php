<?php
/**
 * Custom database table implementation of the block usage index.
 *
 * @package SherBlock\Index
 */

declare(strict_types=1);

namespace SherBlock\Index;

/**
 * Persists block usage rows to SherBlock custom tables via $wpdb.
 */
final class DatabaseIndexRepository implements IndexRepositoryInterface {

	public function __construct(
		private readonly \wpdb $wpdb,
	) {
	}

	/**
	 * {@inheritDoc}
	 */
	public function store( int $postId, string $blockName, array $meta = [] ): void {
		// TODO: INSERT into block usage table.
		unset( $postId, $blockName, $meta );
	}

	/**
	 * {@inheritDoc}
	 */
	public function deleteByPost( int $postId ): void {
		// TODO: DELETE rows WHERE post_id = $postId.
		unset( $postId );
	}

	/**
	 * {@inheritDoc}
	 */
	public function findByBlock( string $blockName ): array {
		// TODO: SELECT posts using this block, join wp_posts for titles/perm links.
		unset( $blockName );

		return [];
	}

	/**
	 * {@inheritDoc}
	 */
	public function findByPost( int $postId ): array {
		// TODO: SELECT all block rows for the given post.
		unset( $postId );

		return [];
	}

	/**
	 * {@inheritDoc}
	 */
	public function findByPostType( string $postType ): array {
		// TODO: Aggregate block usage grouped by block name for a CPT.
		unset( $postType );

		return [];
	}
}
