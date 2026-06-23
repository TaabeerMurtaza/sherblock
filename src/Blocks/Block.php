<?php
/**
 * Block value object.
 *
 * @package SherBlock\Blocks
 */

declare(strict_types=1);

namespace SherBlock\Blocks;

/**
 * Immutable representation of a registered Gutenberg block.
 */
final class Block {

	/**
	 * @param array<string, mixed> $attributes    Default or schema attributes.
	 * @param array<string, mixed> $supports      Block support flags.
	 * @param int|null             $sourcePostId  Source post ID when a block maps to a CPT row (e.g. Lazy Blocks).
	 */
	public function __construct(
		private readonly string $name,
		private readonly string $title,
		private readonly string $category,
		private readonly string $provider,
		private readonly array $attributes = [],
		private readonly array $supports = [],
		private readonly ?int $sourcePostId = null,
	) {
	}

	public function getName(): string {
		return $this->name;
	}

	public function getTitle(): string {
		return $this->title;
	}

	public function getCategory(): string {
		return $this->category;
	}

	public function getProvider(): string {
		return $this->provider;
	}

	/**
	 * @return array<string, mixed>
	 */
	public function getAttributes(): array {
		return $this->attributes;
	}

	/**
	 * @return array<string, mixed>
	 */
	public function getSupports(): array {
		return $this->supports;
	}

	public function getSourcePostId(): ?int {
		return $this->sourcePostId;
	}
}
