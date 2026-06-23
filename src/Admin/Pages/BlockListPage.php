<?php
/**
 * Registered blocks list admin page.
 *
 * @package SherBlock\Admin\Pages
 */

declare(strict_types=1);

namespace SherBlock\Admin\Pages;

use SherBlock\Admin\Menu;
use SherBlock\Blocks\BlockRepositoryInterface;

/**
 * Lists all discovered Gutenberg blocks on the site.
 */
final class BlockListPage {

	public const SLUG = 'sherblock-blocks';

	public const PER_PAGE = 20;

	public function __construct(
		private readonly BlockRepositoryInterface $blockRepository,
	) {
	}

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
		$categories         = $this->blockRepository->getDistinctCategories();
		$providers          = $this->blockRepository->getDistinctProviders();
		$filter_category    = $this->getFilterCategory( $categories );
		$filter_provider    = $this->getFilterProvider( $providers );
		$search_query       = $this->getSearchQuery();
		$filters_active     = null !== $filter_category || null !== $filter_provider || null !== $search_query;
		$all_blocks         = $this->blockRepository->findFiltered( $filter_category, $filter_provider, $search_query );
		$total_items        = count( $all_blocks );
		$total_pages        = max( 1, (int) ceil( $total_items / self::PER_PAGE ) );
		$current_page       = min( $this->getCurrentPage(), $total_pages );
		$offset             = ( $current_page - 1 ) * self::PER_PAGE;
		$blocks             = array_slice( $all_blocks, $offset, self::PER_PAGE );
		$per_page           = self::PER_PAGE;
		$pagination_base    = $this->buildPaginationBase( $filter_category, $filter_provider, $search_query );
		$clear_filters_url  = admin_url( 'admin.php?page=' . self::SLUG );

		$this->loadView(
			'blocks/list.php',
			compact(
				'blocks',
				'categories',
				'providers',
				'filter_category',
				'filter_provider',
				'search_query',
				'filters_active',
				'clear_filters_url',
				'current_page',
				'total_pages',
				'total_items',
				'per_page',
				'pagination_base'
			)
		);
	}

	private function getCurrentPage(): int {
		// phpcs:ignore WordPress.Security.NonceVerification.Recommended -- Read-only list pagination.
		$paged = isset( $_GET['paged'] ) ? absint( wp_unslash( (string) $_GET['paged'] ) ) : 1;

		return max( 1, $paged );
	}

	/**
	 * @param string[] $allowed_categories
	 */
	private function getFilterCategory( array $allowed_categories ): ?string {
		// phpcs:ignore WordPress.Security.NonceVerification.Recommended -- Read-only list filter.
		if ( ! isset( $_GET['category'] ) ) {
			return null;
		}

		$category = sanitize_key( wp_unslash( (string) $_GET['category'] ) );

		if ( '' === $category || ! in_array( $category, $allowed_categories, true ) ) {
			return null;
		}

		return $category;
	}

	/**
	 * @param string[] $allowed_providers
	 */
	private function getFilterProvider( array $allowed_providers ): ?string {
		// phpcs:ignore WordPress.Security.NonceVerification.Recommended -- Read-only list filter.
		if ( ! isset( $_GET['provider'] ) ) {
			return null;
		}

		$provider = sanitize_key( wp_unslash( (string) $_GET['provider'] ) );

		if ( '' === $provider || ! in_array( $provider, $allowed_providers, true ) ) {
			return null;
		}

		return $provider;
	}

	private function getSearchQuery(): ?string {
		// phpcs:ignore WordPress.Security.NonceVerification.Recommended -- Read-only list search.
		if ( ! isset( $_GET['search'] ) ) {
			return null;
		}

		$search = sanitize_text_field( wp_unslash( (string) $_GET['search'] ) );

		if ( '' === $search ) {
			return null;
		}

		return $search;
	}

	private function buildPaginationBase( ?string $filter_category, ?string $filter_provider, ?string $search_query ): string {
		$query_args = [ 'page' => self::SLUG ];

		if ( null !== $filter_category ) {
			$query_args['category'] = $filter_category;
		}

		if ( null !== $filter_provider ) {
			$query_args['provider'] = $filter_provider;
		}

		if ( null !== $search_query ) {
			$query_args['search'] = $search_query;
		}

		return add_query_arg(
			'paged',
			'%#%',
			add_query_arg( $query_args, admin_url( 'admin.php' ) )
		);
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
