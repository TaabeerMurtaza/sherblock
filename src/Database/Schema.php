<?php
/**
 * Database schema definitions.
 *
 * @package SherBlock\Database
 */

declare(strict_types=1);

namespace SherBlock\Database;

/**
 * Defines custom table names and dbDelta-compatible CREATE TABLE statements.
 */
final class Schema {

	public function getTablePrefix(): string {
		global $wpdb;

		return $wpdb->prefix . 'sherblock_';
	}

	/**
	 * @return array<string, string> Table suffix => CREATE TABLE SQL (without dbDelta call).
	 */
	public function getTables(): array {
		$table   = $this->getBlockUsageTableName();
		$charset = $this->getCharsetCollate();

		return [
			'block_usage' => "CREATE TABLE {$table} (
				id bigint(20) unsigned NOT NULL AUTO_INCREMENT,
				post_id bigint(20) unsigned NOT NULL,
				block_name varchar(191) NOT NULL,
				meta longtext NULL,
				PRIMARY KEY  (id),
				KEY post_id (post_id),
				KEY block_name (block_name)
			) {$charset};",
		];
	}

	public function getCharsetCollate(): string {
		global $wpdb;

		return $wpdb->get_charset_collate();
	}

	public function getBlockUsageTableName(): string {
		return $this->getTablePrefix() . 'block_usage';
	}
}
