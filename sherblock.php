<?php
/**
 * Plugin Name: SherBlock
 * Description: A Gutenberg inspection tool for discovering blocks and tracking block usage across your site.
 * Version: 1.0.0
 * Requires at least: 6.0
 * Requires PHP: 8.0
 * Author: Taabeer Murtaza
 * Author URI: https://github.com/TaabeerMurtaza
 * Text Domain: sherblock
 * Domain Path: /languages
 *
 * @package SherBlock
 */

declare(strict_types=1);

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

define( 'SHERBLOCK_VERSION', '1.0.0' );
define( 'SHERBLOCK_FILE', __FILE__ );
define( 'SHERBLOCK_PATH', plugin_dir_path( __FILE__ ) );
define( 'SHERBLOCK_URL', plugin_dir_url( __FILE__ ) );

$autoloader = SHERBLOCK_PATH . 'vendor/autoload.php';

if ( is_readable( $autoloader ) ) {
	require_once $autoloader;
} else {
	add_action(
		'admin_notices',
		static function (): void {
			if ( ! current_user_can( 'activate_plugins' ) ) {
				return;
			}

			printf(
				'<div class="notice notice-error"><p>%s</p></div>',
				esc_html__(
					'SherBlock requires Composer dependencies. Run composer install in the plugin directory.',
					'sherblock'
				)
			);
		}
	);

	return;
}

SherBlock\Plugin::instance()->boot();
