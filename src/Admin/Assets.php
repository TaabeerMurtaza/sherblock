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
		if ( false === strpos( $hook, 'sherblock' ) ) {
			return;
		}

		wp_enqueue_style(
			'sherblock-admin',
			SHERBLOCK_URL . 'assets/css/admin.css',
			[],
			SHERBLOCK_VERSION
		);

		wp_enqueue_script(
			'sherblock-admin',
			SHERBLOCK_URL . 'assets/js/admin.js',
			[ 'jquery' ],
			SHERBLOCK_VERSION,
			true
		);

		wp_localize_script(
			'sherblock-admin',
			'sherblockAdmin',
			[
				'ajaxUrl' => admin_url( 'admin-ajax.php' ),
				'nonce'   => wp_create_nonce( 'sherblock_nonce' ),
				'i18n'    => [
					'reindex'    => __( 'Re-index All Content', 'sherblock' ),
					'indexing'   => __( 'Indexing...', 'sherblock' ),
					'preparing'  => __( 'Preparing...', 'sherblock' ),
					'processed'  => __( 'Processed', 'sherblock' ),
					'posts'      => __( 'posts', 'sherblock' ),
					'complete'   => __( 'Indexing complete!', 'sherblock' ),
					'error'      => __( 'An error occurred. Please try again.', 'sherblock' ),
				],
			]
		);
	}
}
