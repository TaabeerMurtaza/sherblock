<?php
/**
 * Unused blocks admin page.
 *
 * @package SherBlock\Admin\Pages
 */

declare(strict_types=1);

namespace SherBlock\Admin\Pages;

use SherBlock\Admin\Menu;
use SherBlock\Blocks\UnusedBlockFinder;

/**
 * Lists blocks that are registered but have zero indexed usage.
 */
final class UnusedBlocksPage {

	public const SLUG = 'sherblock-unused-blocks';

	public function __construct(
		private readonly UnusedBlockFinder $unusedBlockFinder,
	) {
	}

	public function register(): void {
		add_submenu_page(
			Menu::MENU_SLUG,
			__( 'Unused Blocks', 'sherblock' ),
			__( 'Unused Blocks', 'sherblock' ),
			'manage_options',
			self::SLUG,
			[ $this, 'render' ]
		);
	}

	public function render(): void {
		$unused_blocks = $this->unusedBlockFinder->findUnusedBlocks();

		$this->loadView( 'unused-blocks/list.php', compact( 'unused_blocks' ) );
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
