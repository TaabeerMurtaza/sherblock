<?php
/**
 * Single CPT block usage admin page.
 *
 * @package SherBlock\Admin\Pages
 */

declare(strict_types=1);

namespace SherBlock\Admin\Pages;

use SherBlock\Admin\Menu;

/**
 * Shows which blocks appear across entries of one post type.
 */
final class CptDetailPage {

	public const SLUG = 'sherblock-cpt-detail';

	public function register(): void {
		add_submenu_page(
			Menu::MENU_SLUG,
			__( 'Post Type Detail', 'sherblock' ),
			null,
			'manage_options',
			self::SLUG,
			[ $this, 'render' ]
		);
	}

	public function render(): void {
		// TODO: Read post type from $_GET, aggregate block usage via IndexRepository.
		$post_type = isset( $_GET['post_type'] ) ? sanitize_key( (string) $_GET['post_type'] ) : '';
		$post_type_obj = null;
		$block_stats     = [];

		$this->loadView( 'post-types/detail.php', compact( 'post_type', 'post_type_obj', 'block_stats' ) );
	}

	/**
	 * @param array<string, mixed> $data
	 */
	private function loadView( string $view, array $data = [] ): void {
		$path = SHERBLOCK_PATH . 'views/admin/' . $view;

		if ( ! is_readable( $path ) ) {
			return;
		}

		// phpcs:ignore WordPress.PHP.DontExtract.extract_extract
		extract( $data, EXTR_SKIP );
		include $path;
	}
}
