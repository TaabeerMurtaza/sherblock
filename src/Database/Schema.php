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
		// TODO: Return SQL for block_usage and optional index_meta tables.
		return [
			'block_usage' => '',
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
