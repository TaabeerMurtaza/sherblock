<?php
/**
 * Main plugin bootstrap.
 *
 * @package SherBlock
 */

declare(strict_types=1);

namespace SherBlock;

use SherBlock\Admin\Admin;
use SherBlock\Admin\Menu;
use SherBlock\Admin\Pages\BlockDetailPage;
use SherBlock\Admin\Pages\BlockListPage;
use SherBlock\Admin\Pages\CptListPage;
use SherBlock\Blocks\BlockRegistry;
use SherBlock\Blocks\BlockRepository;
use SherBlock\Blocks\BlockUsageFinder;
use SherBlock\Database\Migration;
use SherBlock\Database\Schema;
use SherBlock\Index\DatabaseIndexRepository;
use SherBlock\Index\IndexBuilder;
use SherBlock\Index\Indexer;
use SherBlock\PostTypes\BlockSupportChecker;
use SherBlock\PostTypes\PostTypeRepository;
use SherBlock\Providers\AcfProvider;
use SherBlock\Providers\BlockProviderManager;
use SherBlock\Providers\CarbonFieldsProvider;
use SherBlock\Providers\CoreBlockProvider;
use SherBlock\Providers\LazyBlocksProvider;

/**
 * Registers services and boots plugin subsystems.
 */
final class Plugin {

	private static ?self $instance = null;

	private Admin $admin;

	private BlockProviderManager $providerManager;

	private Migration $migration;

	private Indexer $indexer;

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
		global $wpdb;

		$this->providerManager = new BlockProviderManager();
		$this->providerManager->register( new CoreBlockProvider() );
		$this->providerManager->register( new AcfProvider() );
		$this->providerManager->register( new CarbonFieldsProvider() );
		$this->providerManager->register( new LazyBlocksProvider() );

		$schema              = new Schema();
		$blockSupportChecker = new BlockSupportChecker();
		$postTypeRepository  = new PostTypeRepository( $blockSupportChecker );
		$blockRegistry       = new BlockRegistry();
		$blockRepository     = new BlockRepository( $blockRegistry, $this->providerManager );
		$indexRepository     = new DatabaseIndexRepository( $wpdb, $schema );
		$indexBuilder        = new IndexBuilder();
		$blockUsageFinder    = new BlockUsageFinder( $indexRepository );

		$this->migration = new Migration( $schema );
		$this->indexer   = new Indexer( $indexBuilder, $indexRepository, $blockSupportChecker, $schema );

		$this->admin = new Admin(
			new Menu(
				new BlockListPage( $blockRepository ),
				new CptListPage( $postTypeRepository ),
				new BlockDetailPage( $blockRepository, $blockUsageFinder ),
			),
		);
	}

	/**
	 * Register WordPress action and filter hooks.
	 */
	private function registerHooks(): void {
		register_activation_hook(
			SHERBLOCK_FILE,
			function (): void {
				$this->migration->run();
				$this->indexer->indexAll();
			}
		);

		add_action(
			'init',
			function (): void {
				load_plugin_textdomain(
					'sherblock',
					false,
					dirname( plugin_basename( SHERBLOCK_FILE ) ) . '/languages'
				);
			}
		);

		add_action(
			'save_post',
			function ( int $post_id ): void {
				$this->indexer->indexPost( $post_id );
			}
		);
	}
}
