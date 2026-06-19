<?php
/**
 * Admin asset registration.
 *
 * @package SherBlock\Admin
 */

declare(strict_types=1);

namespace SherBlock\Admin;

/**
 * Enqueues admin CSS/JS only on SherBlock screens.
 */
final class Assets {

	public function register(): void {
		add_action( 'admin_enqueue_scripts', [ $this, 'enqueue' ] );
	}

	public function enqueue( string $hook ): void {
		// TODO: Load assets only when $hook contains 'sherblock'.
		unset( $hook );

		// wp_enqueue_style( 'sherblock-admin', SHERBLOCK_URL . 'assets/css/admin.css', [], SHERBLOCK_VERSION );
		// wp_enqueue_script( 'sherblock-admin', SHERBLOCK_URL . 'assets/js/admin.js', [], SHERBLOCK_VERSION, true );
	}
}
