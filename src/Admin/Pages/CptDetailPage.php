<?php
/**
 * Single CPT block usage admin page.
 *
 * @package SherBlock\Admin\Pages
 */

declare(strict_types=1);

namespace SherBlock\Admin\Pages;

use SherBlock\Admin\Menu;
use SherBlock\Index\IndexRepositoryInterface;
use SherBlock\PostTypes\PostType;
use SherBlock\PostTypes\PostTypeRepositoryInterface;

/**
 * Shows which blocks appear across entries of one post type.
 */
final class CptDetailPage {

	public const SLUG = 'sherblock-cpt-detail';

	public function __construct(
		private readonly PostTypeRepositoryInterface $postTypeRepository,
		private readonly IndexRepositoryInterface $indexRepository,
	) {
	}

	public function register(): void {
		add_submenu_page(
			Menu::MENU_SLUG,
			__( 'Post Type Detail', 'sherblock' ),
			null,
			'manage_options',
			self::SLUG,
			[ $this, 'render' ]
		);
	}

	public function render(): void {
		// phpcs:ignore WordPress.Security.NonceVerification.Recommended -- Read-only detail view.
		$post_type = isset( $_GET['post_type'] ) ? sanitize_key( wp_unslash( (string) $_GET['post_type'] ) ) : '';

		if ( '' === $post_type ) {
			$this->loadView(
				'post-types/detail.php',
				[
					'post_type'     => '',
					'post_type_obj' => null,
					'block_stats'   => [],
				]
			);

			return;
		}

		$post_type_obj = $this->postTypeRepository->findByName( $post_type );

		if ( ! $post_type_obj instanceof PostType ) {
			wp_die(
				esc_html__( 'The requested post type was not found.', 'sherblock' ),
				esc_html__( 'Post Type Detail', 'sherblock' ),
				[
					'response'  => 404,
					'back_link' => true,
				]
			);
		}

		$block_stats = $this->indexRepository->findByPostType( $post_type );

		$this->loadView(
			'post-types/detail.php',
			compact( 'post_type', 'post_type_obj', 'block_stats' )
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

		// phpcs:ignore WordPress.PHP.DontExtract.extract_extract
		extract( $data, EXTR_SKIP );
		include $path;
	}
}
