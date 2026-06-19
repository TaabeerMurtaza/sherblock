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
		$this->blocks[ $block->getName() ] = $block;
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
		return $this->blocks[ $name ] ?? null;
	}

	public function has( string $name ): bool {
		return isset( $this->blocks[ $name ] );
	}

	public function clear(): void {
		$this->blocks = [];
	}
}
