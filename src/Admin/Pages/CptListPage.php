<?php
/**
 * Gutenberg-enabled CPT list admin page.
 *
 * @package SherBlock\Admin\Pages
 */

declare(strict_types=1);

namespace SherBlock\Admin\Pages;

use SherBlock\Admin\Menu;
use SherBlock\PostTypes\PostTypeRepositoryInterface;

/**
 * Lists public inbuilt and custom post types that support the block editor.
 */
final class CptListPage {

	public const SLUG = 'sherblock-cpts';

	public function __construct(
		private readonly PostTypeRepositoryInterface $postTypeRepository,
	) {
	}

	public function register(): void {
		add_submenu_page(
			Menu::MENU_SLUG,
			__( 'Post Types', 'sherblock' ),
			__( 'Post Types', 'sherblock' ),
			'manage_options',
			self::SLUG,
			[ $this, 'render' ]
		);
	}

	public function render(): void {
		$post_types = $this->postTypeRepository->findAllBlockEnabled();

		$this->loadView( 'post-types/list.php', compact( 'post_types' ) );
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
