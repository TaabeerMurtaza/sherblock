<?php
/**
 * CSV export handler.
 *
 * @package SherBlock\Export
 */

declare(strict_types=1);

namespace SherBlock\Export;

use SherBlock\Blocks\BlockRepositoryInterface;
use SherBlock\Blocks\BlockUsageFinder;
use SherBlock\Support\ProFeatures;

/**
 * Generates CSV exports of block usage data (premium feature).
 */
final class CsvExporter {

	public function __construct(
		private readonly BlockRepositoryInterface $blockRepository,
		private readonly BlockUsageFinder $blockUsageFinder,
		private readonly ProFeatures $proFeatures,
	) {
	}

	public function register(): void {
		add_action( 'wp_ajax_sherblock_export_csv', [ $this, 'handleExport' ] );
	}

	public function handleExport(): void {
		if ( ! check_ajax_referer( 'sherblock_nonce', 'nonce', false ) ) {
			wp_send_json_error( [ 'message' => __( 'Invalid security token.', 'sherblock' ) ] );
		}

		if ( ! current_user_can( 'manage_options' ) ) {
			wp_send_json_error( [ 'message' => __( 'You do not have permission to do this.', 'sherblock' ) ] );
		}

		if ( ! $this->proFeatures->isExportEnabled() ) {
			wp_send_json_error( [ 'message' => __( 'CSV export is a premium feature.', 'sherblock' ) ] );
		}

		$blocks = $this->blockRepository->findAll();

		header( 'Content-Type: text/csv; charset=utf-8' );
		header( 'Content-Disposition: attachment; filename=sherblock-export-' . gmdate( 'Y-m-d' ) . '.csv' );

		$output = fopen( 'php://output', 'w' );

		if ( ! $output ) {
			wp_send_json_error( [ 'message' => __( 'Could not generate export.', 'sherblock' ) ] );
		}

		fputcsv( $output, [
			'Block Name',
			'Title',
			'Category',
			'Provider',
			'Usage Count',
		] );

		foreach ( $blocks as $block ) {
			$count = $this->blockUsageFinder->countUsages( $block->getName() );

			fputcsv( $output, [
				$block->getName(),
				$block->getTitle(),
				$block->getCategory(),
				$block->getProvider(),
				$count,
			] );
		}

		fclose( $output );
		exit;
	}
}
