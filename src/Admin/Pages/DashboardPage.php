<?php
/**
 * Dashboard admin page.
 *
 * @package SherBlock\Admin\Pages
 */

declare(strict_types=1);

namespace SherBlock\Admin\Pages;

use SherBlock\Admin\Menu;
use SherBlock\Blocks\BlockRepositoryInterface;
use SherBlock\Index\IndexRepositoryInterface;
use SherBlock\PostTypes\PostTypeRepositoryInterface;
use SherBlock\Providers\BlockProviderManager;

/**
 * Main dashboard with overview stats, charts, and quick actions.
 */
final class DashboardPage {

	public const SLUG = 'sherblock';

	public function __construct(
		private readonly BlockRepositoryInterface $blockRepository,
		private readonly PostTypeRepositoryInterface $postTypeRepository,
		private readonly IndexRepositoryInterface $indexRepository,
		private readonly BlockProviderManager $providerManager,
	) {
	}

	public function register(): void {
		add_submenu_page(
			Menu::MENU_SLUG,
			__( 'Dashboard', 'sherblock' ),
			__( 'Dashboard', 'sherblock' ),
			'manage_options',
			self::SLUG,
			[ $this, 'render' ]
		);
	}

	public function render(): void {
		$all_blocks     = $this->blockRepository->findAll();
		$total_blocks   = count( $all_blocks );
		$post_types     = $this->postTypeRepository->findAllBlockEnabled();
		$total_cpts     = count( $post_types );
		$total_indexed  = $this->indexRepository->countDistinctPosts();
		$top_blocks     = $this->indexRepository->getTopBlocks( 10 );
		$recent_posts   = $this->indexRepository->getRecentPosts( 5 );
		$providers      = $this->providerManager->all();
		$max_usage      = ! empty( $top_blocks ) ? max( array_column( $top_blocks, 'usage_count' ) ) : 0;

		$this->loadView(
			'dashboard.php',
			compact(
				'total_blocks',
				'total_cpts',
				'total_indexed',
				'top_blocks',
				'recent_posts',
				'providers',
				'max_usage',
			)
		);
	}

	/**
	 * @param array<string, mixed> $data
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
