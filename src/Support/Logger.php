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

	/**
	 * @param array<string, mixed> $context
	 */
	public function info( string $message, array $context = [] ): void {
		// TODO: Log when WP_DEBUG_LOG is enabled.
		unset( $message, $context );
	}

	/**
	 * @param array<string, mixed> $context
	 */
	public function error( string $message, array $context = [] ): void {
		// TODO: Always log errors; include context as JSON when useful.
		unset( $message, $context );
	}

	/**
	 * @param array<string, mixed> $context
	 */
	public function debug( string $message, array $context = [] ): void {
		// TODO: Log only when WP_DEBUG and WP_DEBUG_LOG are true.
		unset( $message, $context );
	}
}
