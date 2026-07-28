<?php
/**
 * Dashboard view.
 *
 * @package SherBlock
 *
 * @var int                                          $total_blocks  Total registered blocks.
 * @var int                                          $total_cpts    Gutenberg-enabled post types.
 * @var int                                          $total_indexed Total indexed posts.
 * @var array<int, array<string, mixed>>             $top_blocks    Top blocks by usage.
 * @var array<int, array<string, mixed>>             $recent_posts  Recently indexed posts.
 * @var \SherBlock\Providers\BlockProviderInterface[] $providers     Registered block providers.
 * @var int                                          $max_usage     Usage count of the most-used block.
 */

defined( 'ABSPATH' ) || exit;

$blocks_url = admin_url( 'admin.php?page=sherblock-blocks' );
$cpts_url   = admin_url( 'admin.php?page=sherblock-cpts' );
?>
<div class="wrap sherblock sherblock-dashboard">

	<div class="sherblock-page-header">
		<div>
			<h1><?php esc_html_e( 'SherBlock Dashboard', 'sherblock' ); ?></h1>
			<p class="description"><?php esc_html_e( 'Overview of Gutenberg block usage across your site.', 'sherblock' ); ?></p>
		</div>
		<div class="sherblock-page-header-actions">
			<button type="button" class="button button-primary sherblock-reindex-btn">
				<span class="dashicons dashicons-update" style="margin-top: 4px;"></span>
				<?php esc_html_e( 'Re-index All Content', 'sherblock' ); ?>
			</button>
		</div>
	</div>

	<div class="sherblock-reindex-wrap" style="display: none;">
		<div class="sherblock-progress-bar">
			<div class="progress-fill"></div>
			<span class="progress-text">0%</span>
		</div>
		<p class="sherblock-reindex-status"></p>
	</div>

	<div class="sherblock-stats-grid">
		<div class="sherblock-stat-card sherblock-stat-card--accent">
			<span class="stat-label"><?php esc_html_e( 'Registered Blocks', 'sherblock' ); ?></span>
			<span class="stat-value"><?php echo esc_html( number_format_i18n( $total_blocks ) ); ?></span>
			<span class="stat-detail"><?php esc_html_e( 'From all providers', 'sherblock' ); ?></span>
		</div>
		<div class="sherblock-stat-card sherblock-stat-card--accent">
			<span class="stat-label"><?php esc_html_e( 'Indexed Posts', 'sherblock' ); ?></span>
			<span class="stat-value"><?php echo esc_html( number_format_i18n( $total_indexed ) ); ?></span>
			<span class="stat-detail"><?php esc_html_e( 'Content with block data', 'sherblock' ); ?></span>
		</div>
		<div class="sherblock-stat-card sherblock-stat-card--accent">
			<span class="stat-label"><?php esc_html_e( 'Post Types', 'sherblock' ); ?></span>
			<span class="stat-value"><?php echo esc_html( number_format_i18n( $total_cpts ) ); ?></span>
			<span class="stat-detail"><?php esc_html_e( 'Gutenberg-enabled', 'sherblock' ); ?></span>
		</div>
		<div class="sherblock-stat-card sherblock-stat-card--accent">
			<span class="stat-label"><?php esc_html_e( 'Active Providers', 'sherblock' ); ?></span>
			<span class="stat-value">
				<?php
				$active_count = 0;
				foreach ( $providers as $p ) {
					if ( $p->isAvailable() ) {
						++$active_count;
					}
				}
				echo esc_html( number_format_i18n( $active_count ) );
				?>
			</span>
			<span class="stat-detail">
				<?php
				printf(
					/* translators: %d: total number of providers */
					esc_html__( 'of %d registered', 'sherblock' ),
					count( $providers )
				);
				?>
			</span>
		</div>
	</div>

	<div class="sherblock-quick-actions">
		<a href="<?php echo esc_url( $blocks_url ); ?>" class="sherblock-quick-action">
			<span class="dashicons dashicons-screenoptions"></span>
			<?php esc_html_e( 'Browse Blocks', 'sherblock' ); ?>
		</a>
		<a href="<?php echo esc_url( $cpts_url ); ?>" class="sherblock-quick-action">
			<span class="dashicons dashicons-admin-post"></span>
			<?php esc_html_e( 'View Post Types', 'sherblock' ); ?>
		</a>
	</div>

	<div class="sherblock-dashboard-grid">
		<div class="sherblock-panel">
			<div class="sherblock-panel-header">
				<h2><?php esc_html_e( 'Most Used Blocks', 'sherblock' ); ?></h2>
			</div>
			<div class="sherblock-panel-body">
				<?php if ( empty( $top_blocks ) ) : ?>
					<div class="sherblock-empty-state">
						<span class="dashicons dashicons-chart-bar"></span>
						<h3><?php esc_html_e( 'No usage data yet', 'sherblock' ); ?></h3>
						<p><?php esc_html_e( 'Block usage will appear here after content is indexed.', 'sherblock' ); ?></p>
					</div>
				<?php else : ?>
					<div class="sherblock-bar-chart">
						<?php foreach ( $top_blocks as $top_block ) : ?>
							<?php
							$usage_count = (int) $top_block['usage_count'];
							$percentage  = $max_usage > 0 ? ( $usage_count / $max_usage ) * 100 : 0;
							?>
							<div class="bar-item">
								<span class="bar-label" title="<?php echo esc_attr( $top_block['block_name'] ); ?>">
									<?php echo esc_html( $top_block['block_name'] ); ?>
								</span>
								<div class="bar-track">
									<div class="bar-fill" style="width: <?php echo esc_attr( (string) $percentage ); ?>%"></div>
								</div>
								<span class="bar-value">
									<?php
									printf(
										/* translators: %s: number of usages */
										esc_html( _n( '%s use', '%s uses', $usage_count, 'sherblock' ) ),
										esc_html( number_format_i18n( $usage_count ) )
									);
									?>
								</span>
							</div>
						<?php endforeach; ?>
					</div>
				<?php endif; ?>
			</div>
			<?php if ( ! empty( $top_blocks ) ) : ?>
				<div class="sherblock-panel-footer">
					<a href="<?php echo esc_url( $blocks_url ); ?>">
						<?php esc_html_e( 'View all blocks &rarr;', 'sherblock' ); ?>
					</a>
				</div>
			<?php endif; ?>
		</div>

		<div class="sherblock-panel">
			<div class="sherblock-panel-header">
				<h2><?php esc_html_e( 'Block Providers', 'sherblock' ); ?></h2>
			</div>
			<div class="sherblock-panel-body">
				<?php if ( empty( $providers ) ) : ?>
					<div class="sherblock-empty-state">
						<span class="dashicons dashicons-plugins-checked"></span>
						<h3><?php esc_html_e( 'No providers registered', 'sherblock' ); ?></h3>
					</div>
				<?php else : ?>
					<ul class="sherblock-provider-list">
						<?php foreach ( $providers as $provider ) : ?>
							<li>
								<span><?php echo esc_html( $provider->getId() ); ?></span>
								<?php if ( $provider->isAvailable() ) : ?>
									<span class="sherblock-provider-status sherblock-provider-status--active">
										<span class="dashicons dashicons-yes-alt"></span>
										<?php esc_html_e( 'Active', 'sherblock' ); ?>
									</span>
								<?php else : ?>
									<span class="sherblock-provider-status sherblock-provider-status--inactive">
										<span class="dashicons dashicons-dismiss"></span>
										<?php esc_html_e( 'Inactive', 'sherblock' ); ?>
									</span>
								<?php endif; ?>
							</li>
						<?php endforeach; ?>
					</ul>
				<?php endif; ?>
			</div>
		</div>
	</div>

	<?php if ( ! empty( $recent_posts ) ) : ?>
		<div class="sherblock-panel" style="margin-bottom: 32px;">
			<div class="sherblock-panel-header">
				<h2><?php esc_html_e( 'Recently Indexed Content', 'sherblock' ); ?></h2>
			</div>
			<div class="sherblock-panel-body">
				<ul class="sherblock-activity-list">
					<?php foreach ( $recent_posts as $recent ) : ?>
						<?php
						$post_type_obj = get_post_type_object( (string) $recent['post_type'] );
						$type_label    = $post_type_obj instanceof \WP_Post_Type
							? $post_type_obj->labels->singular_name
							: $recent['post_type'];
						?>
						<li>
							<div>
								<?php if ( ! empty( $recent['post_id'] ) ) : ?>
									<a href="<?php echo esc_url( get_edit_post_link( (int) $recent['post_id'], 'raw' ) ); ?>" class="activity-title">
										<?php echo esc_html( $recent['post_title'] ?: __( '(no title)', 'sherblock' ) ); ?>
									</a>
								<?php else : ?>
									<span class="activity-title">
										<?php echo esc_html( $recent['post_title'] ?: __( '(no title)', 'sherblock' ) ); ?>
									</span>
								<?php endif; ?>
								<div class="activity-meta">
									<?php echo esc_html( (string) $type_label ); ?>
									<?php if ( ! empty( $recent['post_date'] ) ) : ?>
										&mdash; <?php echo esc_html( (string) $recent['post_date'] ); ?>
									<?php endif; ?>
								</div>
							</div>
							<span class="activity-blocks">
								<?php
								$block_count = (int) $recent['block_count'];
								printf(
									/* translators: %s: number of blocks */
									esc_html( _n( '%s block', '%s blocks', $block_count, 'sherblock' ) ),
									esc_html( number_format_i18n( $block_count ) )
								);
								?>
							</span>
						</li>
					<?php endforeach; ?>
				</ul>
			</div>
		</div>
	<?php endif; ?>

</div>
