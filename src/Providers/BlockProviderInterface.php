<?php
/**
 * Block discovery provider contract.
 *
 * @package SherBlock\Providers
 */

declare(strict_types=1);

namespace SherBlock\Providers;

use SherBlock\Blocks\Block;

/**
 * Each provider discovers blocks from a distinct registration source.
 */
interface BlockProviderInterface {

	/**
	 * Unique provider identifier (e.g. "core", "acf", "carbon-fields").
	 */
	public function getId(): string;

	/**
	 * Whether this provider's dependencies are active on the site.
	 */
	public function isAvailable(): bool;

	/**
	 * @return Block[]
	 */
	public function discoverBlocks(): array;
}
