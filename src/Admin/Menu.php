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
use SherBlock\Admin\Pages\DashboardPage;
use SherBlock\Admin\Pages\SettingsPage;
use SherBlock\Admin\Pages\UnusedBlocksPage;

/**
 * Registers SherBlock admin pages under a top-level menu.
 */
final class Menu {

	public const MENU_SLUG = 'sherblock';

	public function __construct(
		private readonly DashboardPage $dashboardPage,
		private readonly BlockListPage $blockListPage,
		private readonly CptListPage $cptListPage,
		private readonly BlockDetailPage $blockDetailPage,
		private readonly CptDetailPage $cptDetailPage,
		private readonly UnusedBlocksPage $unusedBlocksPage,
		private readonly SettingsPage $settingsPage,
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
			[ $this->dashboardPage, 'render' ],
			'dashicons-block-default',
			58
		);

		$this->dashboardPage->register();
		$this->blockListPage->register();
		$this->blockDetailPage->register();
		$this->cptListPage->register();
		$this->cptDetailPage->register();
		$this->unusedBlocksPage->register();
		$this->settingsPage->register();
	}
}
