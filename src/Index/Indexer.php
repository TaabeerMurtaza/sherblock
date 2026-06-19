<?php
/**
 * Block usage indexer orchestrator.
 *
 * @package SherBlock\Index
 */

declare(strict_types=1);

namespace SherBlock\Index;

/**
 * Coordinates parsing post content and persisting block usage relationships.
 */
final class Indexer {

	public function __construct(
		private readonly IndexBuilder $builder,
		private readonly IndexRepositoryInterface $repository,
	) {
	}

	/**
	 * Re-index block usage for a single post.
	 */
	public function indexPost( int $postId ): void {
		// TODO: Load post_content, build index rows, replace existing rows for this post.
		unset( $postId );
	}

	/**
	 * Full site re-index. Intended for WP-Cron or manual admin action.
	 */
	public function indexAll(): void {
		// TODO: Query all block-enabled post types and index each published post.
	}

	/**
	 * Remove all indexed data (e.g. before rebuild).
	 */
	public function purge(): void {
		// TODO: Truncate index tables via repository.
	}
}
