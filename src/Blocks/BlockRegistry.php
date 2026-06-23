<?php
/**
 * In-memory block registry.
 *
 * @package SherBlock\Blocks
 */

declare(strict_types=1);

namespace SherBlock\Blocks;

/**
 * Holds discovered Block value objects keyed by block name.
 */
final class BlockRegistry {

	/** @var array<string, Block> */
	private array $blocks = [];

	public function register( Block $block ): void {
		$this->blocks[ $this->resolveKey( $block ) ] = $block;
	}

	/**
	 * @param Block[] $blocks
	 */
	public function registerMany( array $blocks ): void {
		foreach ( $blocks as $block ) {
			$this->register( $block );
		}
	}

	/**
	 * @return Block[]
	 */
	public function all(): array {
		return array_values( $this->blocks );
	}

	public function get( string $name ): ?Block {
		if ( isset( $this->blocks[ $name ] ) ) {
			return $this->blocks[ $name ];
		}

		foreach ( $this->blocks as $block ) {
			if ( $block->getName() === $name ) {
				return $block;
			}
		}

		return null;
	}

	public function has( string $name ): bool {
		return null !== $this->get( $name );
	}

	private function resolveKey( Block $block ): string {
		$post_id = $block->getSourcePostId();

		if ( null !== $post_id ) {
			return 'post:' . $post_id;
		}

		return $block->getName();
	}

	public function clear(): void {
		$this->blocks = [];
	}
}
