<?php
/**
 * Main plugin bootstrap.
 *
 * @package SherBlock
 */

declare(strict_types=1);

namespace SherBlock;

use SherBlock\Admin\Admin;
use SherBlock\Database\Migration;
use SherBlock\Database\Schema;
use SherBlock\Providers\AcfProvider;
use SherBlock\Providers\BlockProviderManager;
use SherBlock\Providers\CarbonFieldsProvider;
use SherBlock\Providers\CoreBlockProvider;

/**
 * Registers services and boots plugin subsystems.
 */
final class Plugin {

	private static ?self $instance = null;

	private Admin $admin;

	private BlockProviderManager $providerManager;

	private Migration $migration;

	private function __construct() {
		$this->registerServices();
	}

	public static function instance(): self {
		if ( null === self::$instance ) {
			self::$instance = new self();
		}

		return self::$instance;
	}

	/**
	 * Boot the plugin: hooks, admin UI, and background indexing triggers.
	 */
	public function boot(): void {
		$this->registerHooks();
		$this->admin->register();
	}

	/**
	 * Wire up dependencies. Expand as repositories and indexers are implemented.
	 */
	private function registerServices(): void {
		$this->providerManager = new BlockProviderManager();
		$this->providerManager->register( new CoreBlockProvider() );
		$this->providerManager->register( new AcfProvider() );
		$this->providerManager->register( new CarbonFieldsProvider() );

		// TODO: Instantiate BlockRepository, Indexer, PostTypeRepository, etc.
		$this->migration = new Migration( new Schema() );
		$this->admin       = new Admin();
	}

	/**
	 * Register WordPress action and filter hooks.
	 */
	private function registerHooks(): void {
		register_activation_hook(
			SHERBLOCK_FILE,
			function (): void {
				// TODO: Run database migration on activation.
				$this->migration->run();
			}
		);

		add_action(
			'init',
			function (): void {
				// TODO: Load text domain, schedule initial index build if needed.
			}
		);

		add_action(
			'save_post',
			function ( int $post_id ): void {
				// TODO: Re-index block usage for the saved post via Indexer.
				unset( $post_id );
			}
		);
	}
}
