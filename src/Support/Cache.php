<?php
/**
 * WordPress transient/object-cache wrapper.
 *
 * @package SherBlock\Support
 */

declare(strict_types=1);

namespace SherBlock\Support;

/**
 * Caches expensive discovery and index queries.
 */
final class Cache {

	private const GROUP = 'sherblock';

	public function get( string $key ): mixed {
		// TODO: Try wp_cache_get(), fall back to get_transient().
		unset( $key );

		return false;
	}

	public function set( string $key, mixed $value, int $ttl = 0 ): void {
		// TODO: wp_cache_set() and set_transient() when $ttl > 0.
		unset( $key, $value, $ttl );
	}

	public function delete( string $key ): void {
		// TODO: wp_cache_delete() and delete_transient().
		unset( $key );
	}

	public function flushGroup(): void {
		// TODO: Bump cache version option or delete known transient keys.
	}
}
