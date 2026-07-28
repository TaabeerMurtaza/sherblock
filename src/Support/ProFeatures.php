<?php
/**
 * Premium feature gating.
 *
 * @package SherBlock\Support
 */

declare(strict_types=1);

namespace SherBlock\Support;

/**
 * Centralizes premium feature checks.
 * All premium gate methods delegate to Licensing::isPremium().
 */
final class ProFeatures {

	public function __construct(
		private readonly Licensing $licensing,
	) {
	}

	public function isRestApiEnabled(): bool {
		return $this->licensing->isPremium();
	}

	public function isExportEnabled(): bool {
		return $this->licensing->isPremium();
	}

	public function isBulkOpsEnabled(): bool {
		return $this->licensing->isPremium();
	}

	public function isTrendsEnabled(): bool {
		return $this->licensing->isPremium();
	}

	public function isMultisiteEnabled(): bool {
		return $this->licensing->isPremium() && is_multisite();
	}

	/**
	 * Get the upgrade URL for upsell links.
	 */
	public function getUpgradeUrl(): string {
		return $this->licensing->getUpgradeUrl();
	}
}
