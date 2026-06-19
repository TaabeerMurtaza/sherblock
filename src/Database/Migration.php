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
		// TODO: require_once ABSPATH . 'wp-admin/includes/upgrade.php'; dbDelta() each table.
	}

	/**
	 * Drop custom tables on uninstall or reset.
	 */
	public function drop(): void {
		// TODO: DROP TABLE for each schema table via $wpdb->query().
	}
}
