<?php
/**
 * Custom database table implementation of the block usage index.
 *
 * @package SherBlock\Index
 */

declare(strict_types=1);

namespace SherBlock\Index;

use SherBlock\Database\Schema;

/**
 * Persists block usage rows to SherBlock custom tables via $wpdb.
 */
final class DatabaseIndexRepository implements IndexRepositoryInterface {

	public function __construct(
		private readonly \wpdb $wpdb,
		private readonly Schema $schema,
	) {
	}

	/**
	 * {@inheritDoc}
	 */
	public function store( int $postId, string $blockName, array $meta = [] ): void {
		$table = $this->schema->getBlockUsageTableName();
		$meta_json = [] === $meta ? null : wp_json_encode( $meta );

		$this->wpdb->insert(
			$table,
			[
				'post_id'    => $postId,
				'block_name' => $blockName,
				'meta'       => $meta_json,
			],
			[ '%d', '%s', '%s' ]
		);
	}

	/**
	 * {@inheritDoc}
	 */
	public function deleteByPost( int $postId ): void {
		$table = $this->schema->getBlockUsageTableName();

		$this->wpdb->delete(
			$table,
			[ 'post_id' => $postId ],
			[ '%d' ]
		);
	}

	/**
	 * {@inheritDoc}
	 */
	public function findByBlock( string $blockName ): array {
		$usage_table = $this->schema->getBlockUsageTableName();
		$posts_table = $this->wpdb->posts;

		// phpcs:ignore WordPress.DB.PreparedSQL.InterpolatedNotPrepared -- Table names from schema / $wpdb.
		$sql = $this->wpdb->prepare(
			"SELECT
				p.ID AS post_id,
				p.post_title,
				p.post_type,
				p.post_status,
				SUM( CASE WHEN bu.block_name = %s THEN 1 ELSE 0 END ) AS block_occurrences,
				COUNT( DISTINCT bu.block_name ) AS total_block_types
			FROM {$usage_table} bu
			INNER JOIN {$posts_table} p ON p.ID = bu.post_id
			WHERE bu.post_id IN (
				SELECT post_id FROM {$usage_table} WHERE block_name = %s
			)
			GROUP BY p.ID
			ORDER BY p.post_title ASC",
			$blockName,
			$blockName
		);

		// phpcs:ignore WordPress.DB.PreparedSQL.NotPrepared -- Prepared above.
		$rows = $this->wpdb->get_results( $sql, ARRAY_A );

		if ( ! is_array( $rows ) ) {
			return [];
		}

		return array_map( [ $this, 'mapUsageRow' ], $rows );
	}

	/**
	 * {@inheritDoc}
	 */
	public function findByPost( int $postId ): array {
		$table = $this->schema->getBlockUsageTableName();

		// phpcs:ignore WordPress.DB.PreparedSQL.InterpolatedNotPrepared -- Table name from schema.
		$sql = $this->wpdb->prepare(
			"SELECT post_id, block_name, meta FROM {$table} WHERE post_id = %d ORDER BY id ASC",
			$postId
		);

		// phpcs:ignore WordPress.DB.PreparedSQL.NotPrepared -- Prepared above.
		$rows = $this->wpdb->get_results( $sql, ARRAY_A );

		return is_array( $rows ) ? $rows : [];
	}

	/**
	 * {@inheritDoc}
	 */
	public function findByPostType( string $postType ): array {
		$usage_table = $this->schema->getBlockUsageTableName();
		$posts_table = $this->wpdb->posts;

		// phpcs:ignore WordPress.DB.PreparedSQL.InterpolatedNotPrepared -- Table names from schema / $wpdb.
		$sql = $this->wpdb->prepare(
			"SELECT
				bu.block_name,
				COUNT( * ) AS usage_count,
				COUNT( DISTINCT bu.post_id ) AS post_count
			FROM {$usage_table} bu
			INNER JOIN {$posts_table} p ON p.ID = bu.post_id
			WHERE p.post_type = %s
			GROUP BY bu.block_name
			ORDER BY usage_count DESC",
			$postType
		);

		// phpcs:ignore WordPress.DB.PreparedSQL.NotPrepared -- Prepared above.
		$rows = $this->wpdb->get_results( $sql, ARRAY_A );

		return is_array( $rows ) ? $rows : [];
	}

	/**
	 * {@inheritDoc}
	 */
	public function countDistinctPosts(): int {
		$table = $this->schema->getBlockUsageTableName();

		// phpcs:ignore WordPress.DB.PreparedSQL.InterpolatedNotPrepared -- Table name from schema.
		$sql = "SELECT COUNT( DISTINCT post_id ) FROM {$table}";

		// phpcs:ignore WordPress.DB.PreparedSQL.NotPrepared, WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching -- Custom table, read-only.
		$result = $this->wpdb->get_var( $sql );

		return (int) $result;
	}

	/**
	 * {@inheritDoc}
	 */
	public function getTopBlocks( int $limit ): array {
		$table = $this->schema->getBlockUsageTableName();

		// phpcs:ignore WordPress.DB.PreparedSQL.InterpolatedNotPrepared -- Table name from schema.
		$sql = $this->wpdb->prepare(
			"SELECT block_name, COUNT(*) AS usage_count, COUNT( DISTINCT post_id ) AS post_count
			FROM {$table}
			GROUP BY block_name
			ORDER BY usage_count DESC
			LIMIT %d",
			$limit
		);

		// phpcs:ignore WordPress.DB.PreparedSQL.NotPrepared -- Prepared above.
		$rows = $this->wpdb->get_results( $sql, ARRAY_A );

		return is_array( $rows ) ? $rows : [];
	}

	/**
	 * {@inheritDoc}
	 */
	public function getRecentPosts( int $limit ): array {
		$usage_table = $this->schema->getBlockUsageTableName();
		$posts_table = $this->wpdb->posts;

		// phpcs:ignore WordPress.DB.PreparedSQL.InterpolatedNotPrepared -- Table names from schema / $wpdb.
		$sql = $this->wpdb->prepare(
			"SELECT
				p.ID AS post_id,
				p.post_title,
				p.post_type,
				p.post_date,
				COUNT( DISTINCT bu.block_name ) AS block_count
			FROM {$usage_table} bu
			INNER JOIN {$posts_table} p ON p.ID = bu.post_id
			GROUP BY p.ID
			ORDER BY p.post_date DESC
			LIMIT %d",
			$limit
		);

		// phpcs:ignore WordPress.DB.PreparedSQL.NotPrepared -- Prepared above.
		$rows = $this->wpdb->get_results( $sql, ARRAY_A );

		return is_array( $rows ) ? $rows : [];
	}

	/**
	 * @param array<string, mixed> $row
	 * @return array<string, mixed>
	 */
	private function mapUsageRow( array $row ): array {
		$post_id   = (int) ( $row['post_id'] ?? 0 );
		$post_type = (string) ( $row['post_type'] ?? '' );
		$label     = $post_type;

		$post_type_object = get_post_type_object( $post_type );

		if ( $post_type_object instanceof \WP_Post_Type ) {
			$label = $post_type_object->labels->singular_name ?? $post_type;
		}

		return [
			'post_id'           => $post_id,
			'post_title'        => (string) ( $row['post_title'] ?? '' ),
			'post_type'         => $post_type,
			'post_type_label'   => is_string( $label ) ? $label : $post_type,
			'post_status'       => (string) ( $row['post_status'] ?? '' ),
			'block_occurrences' => (int) ( $row['block_occurrences'] ?? 0 ),
			'total_block_types' => (int) ( $row['total_block_types'] ?? 0 ),
			'edit_link'         => get_edit_post_link( $post_id, 'raw' ) ?: '',
		];
	}
}
