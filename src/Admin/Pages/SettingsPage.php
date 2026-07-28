<?php
/**
 * Settings admin page.
 *
 * @package SherBlock\Admin\Pages
 */

declare(strict_types=1);

namespace SherBlock\Admin\Pages;

use SherBlock\Admin\Menu;

/**
 * Plugin settings page for SherBlock.
 */
final class SettingsPage {

	public const SLUG       = 'sherblock-settings';
	public const OPTION_KEY = 'sherblock_settings';

	public function register(): void {
		add_submenu_page(
			Menu::MENU_SLUG,
			__( 'Settings', 'sherblock' ),
			__( 'Settings', 'sherblock' ),
			'manage_options',
			self::SLUG,
			[ $this, 'render' ]
		);

		add_action( 'admin_init', [ $this, 'registerSettings' ] );
	}

	public function registerSettings(): void {
		register_setting( 'sherblock', self::OPTION_KEY, [
			'type'              => 'array',
			'sanitize_callback' => [ $this, 'sanitizeSettings' ],
			'default'           => $this->getDefaults(),
		] );
	}

	public function render(): void {
		$settings = $this->getSettings();

		$this->loadView( 'settings.php', compact( 'settings' ) );
	}

	/**
	 * @return array<string, mixed>
	 */
	public function getSettings(): array {
		$defaults = $this->getDefaults();
		$saved    = get_option( self::OPTION_KEY, [] );

		if ( ! is_array( $saved ) ) {
			return $defaults;
		}

		return wp_parse_args( $saved, $defaults );
	}

	public function isAutoIndexEnabled(): bool {
		$settings = $this->getSettings();

		return (bool) ( $settings['auto_index'] ?? true );
	}

	public function isDebugEnabled(): bool {
		$settings = $this->getSettings();

		return (bool) ( $settings['debug_logging'] ?? false );
	}

	public function getBatchSize(): int {
		$settings = $this->getSettings();

		return max( 1, (int) ( $settings['batch_size'] ?? 50 ) );
	}

	/**
	 * @param mixed $value
	 * @return array<string, mixed>
	 */
	public function sanitizeSettings( mixed $value ): array {
		if ( ! is_array( $value ) ) {
			return $this->getDefaults();
		}

		$defaults = $this->getDefaults();

		return [
			'auto_index'   => ! empty( $value['auto_index'] ),
			'batch_size'   => max( 1, min( 500, (int) ( $value['batch_size'] ?? $defaults['batch_size'] ) ) ),
			'debug_logging' => ! empty( $value['debug_logging'] ),
		];
	}

	/**
	 * @return array<string, mixed>
	 */
	private function getDefaults(): array {
		return [
			'auto_index'   => true,
			'batch_size'   => 50,
			'debug_logging' => false,
		];
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
