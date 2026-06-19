<?php
/**
 * Admin menu registration.
 *
 * @package SherBlock\Admin
 */

declare(strict_types=1);

namespace SherBlock\Admin;

use SherBlock\Admin\Pages\BlockDetailPage;
use SherBlock\Admin\Pages\BlockListPage;
use SherBlock\Admin\Pages\CptDetailPage;
use SherBlock\Admin\Pages\CptListPage;

/**
 * Registers SherBlock admin pages under a top-level menu.
 */
final class Menu {

	public const MENU_SLUG = 'sherblock';

	public function __construct(
		private readonly BlockListPage $blockListPage = new BlockListPage(),
		private readonly BlockDetailPage $blockDetailPage = new BlockDetailPage(),
		private readonly CptListPage $cptListPage = new CptListPage(),
		private readonly CptDetailPage $cptDetailPage = new CptDetailPage(),
	) {
	}

	public function register(): void {
		add_action( 'admin_menu', [ $this, 'registerMenus' ] );
	}

	public function registerMenus(): void {
		add_menu_page(
			__( 'SherBlock', 'sherblock' ),
			__( 'SherBlock', 'sherblock' ),
			'manage_options',
			self::MENU_SLUG,
			[ $this->blockListPage, 'render' ],
			'dashicons-block-default',
			58
		);

		$this->blockListPage->register();
		$this->blockDetailPage->register();
		$this->cptListPage->register();
		$this->cptDetailPage->register();
	}
}
