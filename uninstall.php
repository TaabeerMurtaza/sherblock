<?php
/**
 * Fired when the plugin is uninstalled.
 *
 * @package SherBlock
 */

declare(strict_types=1);

use SherBlock\Database\Migration;
use SherBlock\Database\Schema;

if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) {
	exit;
}

require_once __DIR__ . '/vendor/autoload.php';

( new Migration( new Schema() ) )->drop();

delete_option( 'sherblock_db_version' );
