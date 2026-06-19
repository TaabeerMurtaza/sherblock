<?php
/**
 * Post type value object.
 *
 * @package SherBlock\PostTypes
 */

declare(strict_types=1);

namespace SherBlock\PostTypes;

/**
 * Immutable representation of a WordPress post type relevant to SherBlock.
 */
final class PostType {

	public function __construct(
		private readonly string $name,
		private readonly string $label,
		private readonly bool $supportsBlocks,
		private readonly bool $isPublic = true,
	) {
	}

	public function getName(): string {
		return $this->name;
	}

	public function getLabel(): string {
		return $this->label;
	}

	public function supportsBlocks(): bool {
		return $this->supportsBlocks;
	}

	public function isPublic(): bool {
		return $this->isPublic;
	}
}
