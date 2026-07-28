<?php
/**
 * Fired when the plugin is uninstalled.
 *
 * @package SherBlock
 */

declare(strict_types=1);

if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) {
	exit;
}

require_once __DIR__ . '/vendor/autoload.php';

use SherBlock\Database\Migration;
use SherBlock\Database\Schema;

// Drop custom tables.
( new Migration( new Schema() ) )->drop();

// Delete all SherBlock options.
global $wpdb;

// phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching
$wpdb->query(
	"DELETE FROM {$wpdb->options} WHERE option_name LIKE 'sherblock\_%'"
);

// Delete all SherBlock transients.
// phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching
$wpdb->query(
	"DELETE FROM {$wpdb->options} WHERE option_name LIKE '_transient\_sherblock\_%'"
);

// phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching
$wpdb->query(
	"DELETE FROM {$wpdb->options} WHERE option_name LIKE '_transient_timeout\_sherblock\_%'"
);

// Flush object cache.
wp_cache_flush();
