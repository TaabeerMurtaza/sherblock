<?php
/**
 * Block detail view.
 *
 * @package SherBlock
 *
 * @var string|null                    $block_name  Block identifier (namespace/block-name).
 * @var \SherBlock\Blocks\Block|null   $block       Block metadata.
 * @var \SherBlock\Blocks\PostBlockUsage[] $usages  Posts using this block.
 * @var int                            $usage_count Number of posts using this block.
 */

defined( 'ABSPATH' ) || exit;

$blocks_list_url = admin_url( 'admin.php?page=sherblock-blocks' );
?>
<div class="wrap sherblock sherblock-block-detail">

	<div class="sherblock-breadcrumb">
		<a href="<?php echo esc_url( $blocks_list_url ); ?>">&larr; <?php esc_html_e( 'Back to blocks', 'sherblock' ); ?></a>
	</div>

	<?php if ( '' === $block_name ) : ?>
		<div class="sherblock-empty-state">
			<span class="dashicons dashicons-screenoptions"></span>
			<h3><?php esc_html_e( 'No block specified', 'sherblock' ); ?></h3>
			<p><?php esc_html_e( 'Choose a block from the blocks list.', 'sherblock' ); ?></p>
		</div>
	<?php elseif ( $block ) : ?>

		<div class="sherblock-page-header">
			<div>
				<h1>
					<?php echo esc_html( $block->getTitle() ); ?>
					<button type="button" class="sherblock-copy-btn" data-copy="<?php echo esc_attr( $block->getName() ); ?>" title="<?php esc_attr_e( 'Copy block name', 'sherblock' ); ?>">
						<span class="dashicons dashicons-admin-page"></span>
					</button>
				</h1>
				<p class="description">
					<?php
					printf(
						/* translators: %s: number of posts */
						esc_html( _n( 'Used on %s post.', 'Used on %s posts.', $usage_count, 'sherblock' ) ),
						esc_html( number_format_i18n( $usage_count ) )
					);
					?>
				</p>
			</div>
		</div>

		<div class="sherblock-block-detail-layout">
			<div class="sherblock-block-detail-sidebar">
				<div class="sherblock-panel">
					<div class="sherblock-panel-header">
						<h2><?php esc_html_e( 'Block Info', 'sherblock' ); ?></h2>
					</div>
					<div class="sherblock-panel-body">
						<table class="sherblock-meta-table">
							<tbody>
								<tr>
									<th scope="row"><?php esc_html_e( 'Name', 'sherblock' ); ?></th>
									<td><code><?php echo esc_html( $block->getName() ); ?></code></td>
								</tr>
								<tr>
									<th scope="row"><?php esc_html_e( 'Category', 'sherblock' ); ?></th>
									<td><?php echo esc_html( $block->getCategory() ?: '—' ); ?></td>
								</tr>
								<tr>
									<th scope="row"><?php esc_html_e( 'Provider', 'sherblock' ); ?></th>
									<td>
										<span class="provider-badge">
											<span class="provider-dot provider-dot--<?php echo esc_attr( sanitize_html_class( $block->getProvider() ) ); ?>"></span>
											<code><?php echo esc_html( $block->getProvider() ); ?></code>
										</span>
									</td>
								</tr>
								<tr>
									<th scope="row"><?php esc_html_e( 'Usage Count', 'sherblock' ); ?></th>
									<td>
										<strong><?php echo esc_html( number_format_i18n( $usage_count ) ); ?></strong>
										<?php esc_html_e( 'posts', 'sherblock' ); ?>
									</td>
								</tr>
							</tbody>
						</table>
					</div>
				</div>
			</div>

			<div class="sherblock-block-detail-main">
				<div class="sherblock-panel">
					<div class="sherblock-panel-header">
						<h2><?php esc_html_e( 'Usage by Post', 'sherblock' ); ?></h2>
					</div>
					<div class="sherblock-panel-body">
						<?php if ( empty( $usages ) ) : ?>
							<div class="sherblock-empty-state">
								<span class="dashicons dashicons-post"></span>
								<h3><?php esc_html_e( 'No usage found', 'sherblock' ); ?></h3>
								<p><?php esc_html_e( 'This block is not used on any indexed content yet.', 'sherblock' ); ?></p>
							</div>
						<?php else : ?>
							<table class="widefat fixed striped sherblock-block-usage-table">
								<thead>
									<tr>
										<th scope="col"><?php esc_html_e( 'Post', 'sherblock' ); ?></th>
										<th scope="col"><?php esc_html_e( 'Post Type', 'sherblock' ); ?></th>
										<th scope="col"><?php esc_html_e( 'Status', 'sherblock' ); ?></th>
										<th scope="col" class="num"><?php esc_html_e( 'Occurrences', 'sherblock' ); ?></th>
										<th scope="col" class="num"><?php esc_html_e( 'Total Blocks', 'sherblock' ); ?></th>
									</tr>
								</thead>
								<tbody>
									<?php foreach ( $usages as $usage ) : ?>
										<?php
										$status_object = get_post_status_object( $usage->getStatus() );
										$status_label  = $status_object->label ?? $usage->getStatus();
										$status_slug   = sanitize_html_class( $usage->getStatus() );
										?>
										<tr>
											<td>
												<?php if ( '' !== $usage->getEditLink() ) : ?>
													<a href="<?php echo esc_url( $usage->getEditLink() ); ?>">
														<strong><?php echo esc_html( $usage->getTitle() ?: __( '(no title)', 'sherblock' ) ); ?></strong>
													</a>
												<?php else : ?>
													<strong><?php echo esc_html( $usage->getTitle() ?: __( '(no title)', 'sherblock' ) ); ?></strong>
												<?php endif; ?>
												<div class="row-actions">
													<span class="id"><?php printf( esc_html__( 'ID: %d', 'sherblock' ), (int) $usage->getPostId() ); ?></span>
												</div>
											</td>
											<td>
												<?php echo esc_html( $usage->getPostTypeLabel() ); ?>
												<br />
												<code><?php echo esc_html( $usage->getPostType() ); ?></code>
											</td>
											<td>
												<span class="status-badge status-badge--<?php echo esc_attr( $status_slug ); ?>">
													<?php echo esc_html( (string) $status_label ); ?>
												</span>
											</td>
											<td class="num"><?php echo esc_html( number_format_i18n( $usage->getBlockOccurrences() ) ); ?></td>
											<td class="num"><?php echo esc_html( number_format_i18n( $usage->getTotalBlockTypes() ) ); ?></td>
										</tr>
									<?php endforeach; ?>
								</tbody>
							</table>
						<?php endif; ?>
					</div>
				</div>
			</div>
		</div>

	<?php endif; ?>
</div>
