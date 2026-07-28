<?php
/**
 * Simple logging helper.
 *
 * @package SherBlock\Support
 */

declare(strict_types=1);

namespace SherBlock\Support;

/**
 * Thin wrapper around error_log() and optional WP_DEBUG checks.
 */
final class Logger {

	private const LOG_PREFIX = '[SherBlock] ';

	/**
	 * @param array<string, mixed> $context
	 */
	public function info( string $message, array $context = [] ): void {
		if ( ! defined( 'WP_DEBUG_LOG' ) || ! WP_DEBUG_LOG ) {
			return;
		}

		$this->log( 'INFO', $message, $context );
	}

	/**
	 * @param array<string, mixed> $context
	 */
	public function error( string $message, array $context = [] ): void {
		$this->log( 'ERROR', $message, $context );
	}

	/**
	 * @param array<string, mixed> $context
	 */
	public function debug( string $message, array $context = [] ): void {
		if ( ! defined( 'WP_DEBUG' ) || ! WP_DEBUG ) {
			return;
		}

		if ( ! defined( 'WP_DEBUG_LOG' ) || ! WP_DEBUG_LOG ) {
			return;
		}

		$this->log( 'DEBUG', $message, $context );
	}

	/**
	 * @param array<string, mixed> $context
	 */
	private function log( string $level, string $message, array $context = [] ): void {
		$line = self::LOG_PREFIX . '[' . $level . '] ' . $message;

		if ( ! empty( $context ) ) {
			$line .= ' ' . wp_json_encode( $context );
		}

		// phpcs:ignore WordPress.PHP.DevelopmentFunctions.error_log_error_log
		error_log( $line );
	}
}
