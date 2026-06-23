<?php
/**
 * Block usage summary for a single post.
 *
 * @package SherBlock\Blocks
 */

declare(strict_types=1);

namespace SherBlock\Blocks;

/**
 * Immutable summary of how one block is used on a specific post.
 */
final class PostBlockUsage {

	public function __construct(
		private readonly int $postId,
		private readonly string $title,
		private readonly string $postType,
		private readonly string $postTypeLabel,
		private readonly string $status,
		private readonly int $blockOccurrences,
		private readonly int $totalBlockTypes,
		private readonly string $editLink,
	) {
	}

	public function getPostId(): int {
		return $this->postId;
	}

	public function getTitle(): string {
		return $this->title;
	}

	public function getPostType(): string {
		return $this->postType;
	}

	public function getPostTypeLabel(): string {
		return $this->postTypeLabel;
	}

	public function getStatus(): string {
		return $this->status;
	}

	public function getBlockOccurrences(): int {
		return $this->blockOccurrences;
	}

	public function getTotalBlockTypes(): int {
		return $this->totalBlockTypes;
	}

	public function getEditLink(): string {
		return $this->editLink;
	}
}
