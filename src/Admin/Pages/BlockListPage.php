<?php
/**
 * Registered blocks list admin page.
 *
 * @package SherBlock\Admin\Pages
 */

declare(strict_types=1);

namespace SherBlock\Admin\Pages;

use SherBlock\Admin\Menu;

/**
 * Lists all discovered Gutenberg blocks on the site.
 */
final class BlockListPage {

	public const SLUG = 'sherblock-blocks';

	public function register(): void {
		add_submenu_page(
			Menu::MENU_SLUG,
			__( 'Blocks', 'sherblock' ),
			__( 'Blocks', 'sherblock' ),
			'manage_options',
			self::SLUG,
			[ $this, 'render' ]
		);
	}

	public function render(): void {
		// TODO: Inject BlockRepository, fetch blocks, pass to view.
		$blocks = [];

		$this->loadView( 'blocks/list.php', compact( 'blocks' ) );
	}

	/**
	 * @param array<string, mixed> $data Variables extracted into the view scope.
	 */
	private function loadView( string $view, array $data = [] ): void {
		$path = SHERBLOCK_PATH . 'views/admin/' . $view;

		if ( ! is_readable( $path ) ) {
			return;
		}

		// phpcs:ignore WordPress.PHP.DontExtract.extract_extract -- Controlled view data.
		extract( $data, EXTR_SKIP );
		include $path;
	}
}
