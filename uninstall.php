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

// TODO: Drop custom database tables created by SherBlock\Database\Schema.
// TODO: Delete plugin options and transients (e.g. index version, cache keys).
