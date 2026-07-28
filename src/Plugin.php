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
use SherBlock\Admin\Pages\CptDetailPage;
use SherBlock\Admin\Pages\CptListPage;
use SherBlock\Admin\Pages\DashboardPage;
use SherBlock\Admin\Pages\SettingsPage;
use SherBlock\Admin\Pages\UnusedBlocksPage;
use SherBlock\Api\RestApiController;
use SherBlock\Blocks\BlockRegistry;
use SherBlock\Blocks\BlockRepository;
use SherBlock\Blocks\BlockUsageFinder;
use SherBlock\Blocks\UnusedBlockFinder;
use SherBlock\Database\Migration;
use SherBlock\Database\Schema;
use SherBlock\Export\CsvExporter;
use SherBlock\Index\DatabaseIndexRepository;
use SherBlock\Index\IndexBuilder;
use SherBlock\Index\Indexer;
use SherBlock\Index\ReindexHandler;
use SherBlock\PostTypes\BlockSupportChecker;
use SherBlock\PostTypes\PostTypeRepository;
use SherBlock\Providers\AcfProvider;
use SherBlock\Providers\BlockProviderManager;
use SherBlock\Providers\CarbonFieldsProvider;
use SherBlock\Providers\CoreBlockProvider;
use SherBlock\Providers\LazyBlocksProvider;
use SherBlock\Support\Licensing;
use SherBlock\Support\ProFeatures;

/**
 * Registers services and boots plugin subsystems.
 */
final class Plugin {

	private static ?self $instance = null;

	private Admin $admin;

	private BlockProviderManager $providerManager;

	private Migration $migration;

	private Indexer $indexer;

	private ReindexHandler $reindexHandler;

	private SettingsPage $settingsPage;

	private RestApiController $restApi;

	private CsvExporter $csvExporter;

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
		$this->reindexHandler->register();
		$this->restApi->register();
		$this->csvExporter->register();
	}

	/**
	 * Wire up dependencies.
	 */
	private function registerServices(): void {
		global $wpdb;

		$licensing  = Licensing::instance();
		$proFeatures = new ProFeatures( $licensing );

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
		$unusedBlockFinder   = new UnusedBlockFinder( $blockRepository, $indexRepository );

		$this->migration      = new Migration( $schema );
		$this->indexer        = new Indexer( $indexBuilder, $indexRepository, $blockSupportChecker, $schema );
		$this->reindexHandler = new ReindexHandler( $indexBuilder, $indexRepository, $blockSupportChecker, $schema );
		$this->settingsPage   = new SettingsPage();
		$this->restApi        = new RestApiController( $blockRepository, $postTypeRepository, $indexRepository, $blockUsageFinder );
		$this->csvExporter    = new CsvExporter( $blockRepository, $blockUsageFinder, $proFeatures );

		$dashboardPage    = new DashboardPage( $blockRepository, $postTypeRepository, $indexRepository, $this->providerManager );
		$unusedBlocksPage = new UnusedBlocksPage( $unusedBlockFinder );

		$this->admin = new Admin(
			new Menu(
				$dashboardPage,
				new BlockListPage( $blockRepository ),
				new CptListPage( $postTypeRepository ),
				new BlockDetailPage( $blockRepository, $blockUsageFinder ),
				new CptDetailPage( $postTypeRepository, $indexRepository ),
				$unusedBlocksPage,
				$this->settingsPage,
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
				if ( $this->settingsPage->isAutoIndexEnabled() ) {
					$this->indexer->indexPost( $post_id );
				}
			}
		);
	}
}
