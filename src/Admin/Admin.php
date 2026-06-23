<?php
/**
 * Admin subsystem bootstrap.
 *
 * @package SherBlock\Admin
 */

declare(strict_types=1);

namespace SherBlock\Admin;

/**
 * Boots admin menu, assets, and page controllers.
 */
final class Admin {

	public function __construct(
		private readonly Menu $menu,
		private readonly Assets $assets = new Assets(),
	) {
	}

	public function register(): void {
		if ( ! is_admin() ) {
			return;
		}

		$this->menu->register();
		$this->assets->register();
	}
}
