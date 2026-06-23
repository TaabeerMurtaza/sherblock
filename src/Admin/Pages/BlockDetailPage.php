<?php
/**
 * Single block detail admin page.
 *
 * @package SherBlock\Admin\Pages
 */

declare(strict_types=1);

namespace SherBlock\Admin\Pages;

use SherBlock\Admin\Menu;
use SherBlock\Blocks\Block;
use SherBlock\Blocks\BlockRepositoryInterface;
use SherBlock\Blocks\BlockUsageFinder;

/**
 * Shows metadata and usage locations for one block.
 */
final class BlockDetailPage {

	public const SLUG = 'sherblock-block-detail';

	public function __construct(
		private readonly BlockRepositoryInterface $blockRepository,
		private readonly BlockUsageFinder $blockUsageFinder,
	) {
	}

	public function register(): void {
		add_submenu_page(
			Menu::MENU_SLUG,
			__( 'Block Detail', 'sherblock' ),
			null,
			'manage_options',
			self::SLUG,
			[ $this, 'render' ]
		);
	}

	public function render(): void {
		// phpcs:ignore WordPress.Security.NonceVerification.Recommended -- Read-only detail view.
		$block_name = isset( $_GET['block'] ) ? sanitize_text_field( wp_unslash( (string) $_GET['block'] ) ) : '';

		if ( '' === $block_name ) {
			$this->loadView(
				'blocks/detail.php',
				[
					'block'       => null,
					'block_name'  => '',
					'usages'      => [],
					'usage_count' => 0,
				]
			);

			return;
		}

		$block = $this->blockRepository->findByName( $block_name );

		if ( ! $block instanceof Block ) {
			wp_die(
				esc_html__( 'The requested block was not found.', 'sherblock' ),
				esc_html__( 'Block Detail', 'sherblock' ),
				[
					'response'  => 404,
					'back_link' => true,
				]
			);
		}

		$usages      = $this->blockUsageFinder->findPostsUsingBlock( $block_name );
		$usage_count = count( $usages );

		$this->loadView(
			'blocks/detail.php',
			compact( 'block', 'block_name', 'usages', 'usage_count' )
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
