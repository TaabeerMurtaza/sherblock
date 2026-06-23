<?php
/**
 * Database migration runner.
 *
 * @package SherBlock\Database
 */

declare(strict_types=1);

namespace SherBlock\Database;

/**
 * Applies schema changes using WordPress dbDelta().
 */
final class Migration {

	public function __construct(
		private readonly Schema $schema,
	) {
	}

	/**
	 * Create or update custom tables.
	 */
	public function run(): void {
		global $wpdb;

		require_once ABSPATH . 'wp-admin/includes/upgrade.php';

		foreach ( $this->schema->getTables() as $sql ) {
			if ( '' === $sql ) {
				continue;
			}

			dbDelta( $sql );
		}

		update_option( 'sherblock_db_version', SHERBLOCK_VERSION );
	}

	/**
	 * Drop custom tables on uninstall or reset.
	 */
	public function drop(): void {
		global $wpdb;

		foreach ( array_keys( $this->schema->getTables() ) as $suffix ) {
			$table = $this->schema->getTablePrefix() . $suffix;
			// phpcs:ignore WordPress.DB.PreparedSQL.InterpolatedNotPrepared -- Table name from schema helper.
			$wpdb->query( "DROP TABLE IF EXISTS {$table}" );
		}

		delete_option( 'sherblock_db_version' );
	}
}
