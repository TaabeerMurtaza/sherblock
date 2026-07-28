<?php
/**
 * Freemius licensing wrapper.
 *
 * @package SherBlock\Support
 */

declare(strict_types=1);

namespace SherBlock\Support;

/**
 * Thin wrapper around the Freemius SDK for license checking.
 *
 * To activate Freemius, define these constants in wp-config.php:
 *   SHERBLOCK_FREEMIUS_ID     — Your Freemius plugin ID
 *   SHERBLOCK_FREEMIUS_KEY    — Your Freemius public key
 *   SHERBLOCK_FREEMIUS_SECRET — Your Freemius secret key
 *
 * Or set the values directly in the init() method below.
 */
final class Licensing {

	private static ?Licensing $instance = null;

	/**
	 * Get the singleton instance.
	 */
	public static function instance(): self {
		if ( null === self::$instance ) {
			self::$instance = new self();
		}

		return self::$instance;
	}

	/**
	 * Initialize Freemius SDK. Call once from sherblock.php.
	 */
	public function init(): void {
		if ( ! $this->isConfigured() ) {
			return;
		}

		if ( function_exists( 'sherblock_fs' ) ) {
			return;
		}

		// phpcs:disable WordPress.NamingConventions.ValidFunctionName.FunctionNameInvalid
		function sherblock_fs() {
			$instance = \Freemius::instance(
				'sherblock',
				[
					'id'         => defined( 'SHERBLOCK_FREEMIUS_ID' ) ? SHERBLOCK_FREEMIUS_ID : '',
					'slug'       => 'sherblock',
					'type'       => 'plugin',
					'public_key' => defined( 'SHERBLOCK_FREEMIUS_KEY' ) ? SHERBLOCK_FREEMIUS_KEY : '',
					'is_premium' => true,
					'has_addons' => false,
					'has_paid_plans' => true,
					'menu'       => [
						'slug'        => 'sherblock',
						'first-path'  => 'admin.php?page=sherblock',
						'support'     => false,
						'account'     => true,
						'pricing'     => true,
						'contact'     => false,
					],
				]
			);

			$instance->add_filter( 'plugin_icon', function () {
				return SHERBLOCK_URL . 'assets/css/admin.css';
			});

			return $instance;
		}
		// phpcs:enable WordPress.NamingConventions.ValidFunctionName.FunctionNameInvalid
	}

	/**
	 * Check if Freemius credentials are configured.
	 */
	public function isConfigured(): bool {
		return defined( 'SHERBLOCK_FREEMIUS_ID' )
			&& defined( 'SHERBLOCK_FREEMIUS_KEY' )
			&& '' !== SHERBLOCK_FREEMIUS_ID
			&& '' !== SHERBLOCK_FREEMIUS_KEY;
	}

	/**
	 * Check if the current user has a valid premium license.
	 */
	public function isPremium(): bool {
		if ( ! $this->isConfigured() || ! function_exists( 'sherblock_fs' ) ) {
			return false;
		}

		$fs = sherblock_fs();

		return $fs->can_use_premium_code();
	}

	/**
	 * Get the upgrade URL.
	 */
	public function getUpgradeUrl(): string {
		if ( ! $this->isConfigured() || ! function_exists( 'sherblock_fs' ) ) {
			return '#';
		}

		return sherblock_fs()->get_upgrade_url();
	}

	/**
	 * Get the pricing page URL.
	 */
	public function getPricingUrl(): string {
		if ( ! $this->isConfigured() || ! function_exists( 'sherblock_fs' ) ) {
			return '#';
		}

		return sherblock_fs()->get_pricing_url();
	}
}
