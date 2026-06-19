<?php
/**
 * Single block detail admin page.
 *
 * @package SherBlock\Admin\Pages
 */

declare(strict_types=1);

namespace SherBlock\Admin\Pages;

use SherBlock\Admin\Menu;

/**
 * Shows metadata and usage locations for one block.
 */
final class BlockDetailPage {

	public const SLUG = 'sherblock-block-detail';

	public function register(): void {
		add_submenu_page(
			Menu::MENU_SLUG,
			__( 'Block Detail', 'sherblock' ),
			null,
			'manage_options',
			self::SLUG,
			[ $this, 'render' ]
		);
	}

	public function render(): void {
		// TODO: Read block name from $_GET, load via BlockRepository and BlockUsageFinder.
		$block_name = isset( $_GET['block'] ) ? sanitize_text_field( wp_unslash( (string) $_GET['block'] ) ) : '';
		$block      = null;
		$usages     = [];

		$this->loadView( 'blocks/detail.php', compact( 'block_name', 'block', 'usages' ) );
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
