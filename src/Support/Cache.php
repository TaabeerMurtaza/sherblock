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

	private const GROUP         = 'sherblock';
	private const OPTION_PREFIX = 'sherblock_cache_v_';

	public function get( string $key ): mixed {
		$prefixed = $this->prefixedKey( $key );

		$cached = wp_cache_get( $prefixed, self::GROUP );

		if ( false !== $cached ) {
			return $cached;
		}

		$transient = get_transient( $prefixed );

		if ( false !== $transient ) {
			return $transient;
		}

		return false;
	}

	public function set( string $key, mixed $value, int $ttl = 0 ): void {
		$prefixed = $this->prefixedKey( $key );

		wp_cache_set( $prefixed, $value, self::GROUP );

		if ( $ttl > 0 ) {
			set_transient( $prefixed, $value, $ttl );
		}
	}

	public function delete( string $key ): void {
		$prefixed = $this->prefixedKey( $key );

		wp_cache_delete( $prefixed, self::GROUP );
		delete_transient( $prefixed );
	}

	public function flushGroup(): void {
		$version_key = self::OPTION_PREFIX . 'group';
		$version     = (int) get_option( $version_key, 0 );
		update_option( $version_key, $version + 1 );
	}

	private function prefixedKey( string $key ): string {
		$version     = (int) get_option( self::OPTION_PREFIX . 'group', 0 );
		$version_key = self::OPTION_PREFIX . $version . '_';

		return $version_key . $key;
	}
}
