<?php
/**
 * Settings view.
 *
 * @package SherBlock
 *
 * @var array<string, mixed> $settings Current settings values.
 */

defined( 'ABSPATH' ) || exit;

$action_url = admin_url( 'options.php' );
?>
<div class="wrap sherblock sherblock-settings">

	<div class="sherblock-page-header">
		<div>
			<h1><?php esc_html_e( 'SherBlock Settings', 'sherblock' ); ?></h1>
			<p class="description"><?php esc_html_e( 'Configure how SherBlock indexes and logs block data.', 'sherblock' ); ?></p>
		</div>
	</div>

	<form method="post" action="<?php echo esc_url( $action_url ); ?>">
		<?php settings_fields( 'sherblock' ); ?>

		<div class="sherblock-settings-section">
			<div class="section-header">
				<h2><?php esc_html_e( 'Indexing', 'sherblock' ); ?></h2>
			</div>
			<div class="section-body">
				<table class="form-table" role="presentation">
					<tbody>
						<tr>
							<th scope="row">
								<label for="sherblock-auto-index">
									<?php esc_html_e( 'Auto-index on save', 'sherblock' ); ?>
								</label>
							</th>
							<td>
								<input
									type="checkbox"
									name="sherblock_settings[auto_index]"
									id="sherblock-auto-index"
									value="1"
									<?php checked( ! empty( $settings['auto_index'] ) ); ?>
								/>
								<p class="description">
									<?php esc_html_e( 'Automatically re-index block usage when a post is saved.', 'sherblock' ); ?>
								</p>
							</td>
						</tr>
						<tr>
							<th scope="row">
								<label for="sherblock-batch-size">
									<?php esc_html_e( 'Batch size', 'sherblock' ); ?>
								</label>
							</th>
							<td>
								<input
									type="number"
									name="sherblock_settings[batch_size]"
									id="sherblock-batch-size"
									value="<?php echo esc_attr( (string) ( $settings['batch_size'] ?? 50 ) ); ?>"
									min="1"
									max="500"
									class="small-text"
								/>
								<p class="description">
									<?php esc_html_e( 'Number of posts to process per batch during re-indexing.', 'sherblock' ); ?>
								</p>
							</td>
						</tr>
					</tbody>
				</table>
			</div>
		</div>

		<div class="sherblock-settings-section">
			<div class="section-header">
				<h2><?php esc_html_e( 'Debugging', 'sherblock' ); ?></h2>
			</div>
			<div class="section-body">
				<table class="form-table" role="presentation">
					<tbody>
						<tr>
							<th scope="row">
								<label for="sherblock-debug-logging">
									<?php esc_html_e( 'Debug logging', 'sherblock' ); ?>
								</label>
							</th>
							<td>
								<input
									type="checkbox"
									name="sherblock_settings[debug_logging]"
									id="sherblock-debug-logging"
									value="1"
									<?php checked( ! empty( $settings['debug_logging'] ) ); ?>
								/>
								<p class="description">
									<?php esc_html_e( 'Log detailed debug information to the WordPress error log. Requires WP_DEBUG_LOG to be enabled.', 'sherblock' ); ?>
								</p>
							</td>
						</tr>
					</tbody>
				</table>
			</div>
		</div>

		<?php submit_button( __( 'Save Settings', 'sherblock' ) ); ?>
	</form>
</div>
