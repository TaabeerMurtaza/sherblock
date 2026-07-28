<?php
/**
 * Post type detail view.
 *
 * @package SherBlock
 *
 * @var string                                  $post_type     Post type slug.
 * @var \SherBlock\PostTypes\PostType|null      $post_type_obj Post type metadata.
 * @var array<int, array<string, mixed>>        $block_stats   Aggregated block usage for this CPT.
 */

defined( 'ABSPATH' ) || exit;

$cpts_list_url = admin_url( 'admin.php?page=sherblock-cpts' );
?>
<div class="wrap sherblock sherblock-cpt-detail">

	<div class="sherblock-breadcrumb">
		<a href="<?php echo esc_url( $cpts_list_url ); ?>">&larr; <?php esc_html_e( 'Back to post types', 'sherblock' ); ?></a>
	</div>

	<?php if ( '' === $post_type ) : ?>
		<div class="sherblock-empty-state">
			<span class="dashicons dashicons-admin-post"></span>
			<h3><?php esc_html_e( 'No post type specified', 'sherblock' ); ?></h3>
			<p><?php esc_html_e( 'Choose a post type from the list.', 'sherblock' ); ?></p>
		</div>
	<?php elseif ( $post_type_obj ) : ?>

		<div class="sherblock-page-header">
			<div>
				<h1><?php echo esc_html( $post_type_obj->getLabel() ); ?></h1>
				<p class="description">
					<?php
					printf(
						/* translators: %s: post type slug */
						esc_html__( 'Block usage across all entries of the %s post type.', 'sherblock' ),
						esc_html( $post_type )
					);
					?>
				</p>
			</div>
		</div>

		<div class="sherblock-block-detail-layout">
			<div class="sherblock-block-detail-sidebar">
				<div class="sherblock-panel">
					<div class="sherblock-panel-header">
						<h2><?php esc_html_e( 'Post Type Info', 'sherblock' ); ?></h2>
					</div>
					<div class="sherblock-panel-body">
						<table class="sherblock-meta-table">
							<tbody>
								<tr>
									<th scope="row"><?php esc_html_e( 'Slug', 'sherblock' ); ?></th>
									<td><code><?php echo esc_html( $post_type ); ?></code></td>
								</tr>
								<tr>
									<th scope="row"><?php esc_html_e( 'Label', 'sherblock' ); ?></th>
									<td><?php echo esc_html( $post_type_obj->getLabel() ); ?></td>
								</tr>
								<tr>
									<th scope="row"><?php esc_html_e( 'Visibility', 'sherblock' ); ?></th>
									<td>
										<?php if ( $post_type_obj->isPublic() ) : ?>
											<span class="sherblock-provider-status sherblock-provider-status--active">
												<span class="dashicons dashicons-visibility"></span>
												<?php esc_html_e( 'Public', 'sherblock' ); ?>
											</span>
										<?php else : ?>
											<span class="sherblock-provider-status sherblock-provider-status--inactive">
												<span class="dashicons dashicons-hidden"></span>
												<?php esc_html_e( 'Private', 'sherblock' ); ?>
											</span>
										<?php endif; ?>
									</td>
								</tr>
								<tr>
									<th scope="row"><?php esc_html_e( 'Block Support', 'sherblock' ); ?></th>
									<td>
										<?php if ( $post_type_obj->supportsBlocks() ) : ?>
											<span class="sherblock-provider-status sherblock-provider-status--active">
												<span class="dashicons dashicons-yes-alt"></span>
												<?php esc_html_e( 'Enabled', 'sherblock' ); ?>
											</span>
										<?php else : ?>
											<span class="sherblock-provider-status sherblock-provider-status--inactive">
												<span class="dashicons dashicons-dismiss"></span>
												<?php esc_html_e( 'Disabled', 'sherblock' ); ?>
											</span>
										<?php endif; ?>
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
						<h2>
							<?php
							printf(
								/* translators: %s: number of unique blocks */
								esc_html__( 'Block Frequency (%s unique blocks)', 'sherblock' ),
								esc_html( number_format_i18n( count( $block_stats ) ) )
							);
							?>
						</h2>
					</div>
					<div class="sherblock-panel-body">
						<?php if ( empty( $block_stats ) ) : ?>
							<div class="sherblock-empty-state">
								<span class="dashicons dashicons-screenoptions"></span>
								<h3><?php esc_html_e( 'No block usage found', 'sherblock' ); ?></h3>
								<p><?php esc_html_e( 'No indexed content uses this post type yet, or the index is empty.', 'sherblock' ); ?></p>
							</div>
						<?php else : ?>
							<table class="widefat fixed striped">
								<thead>
									<tr>
										<th scope="col"><?php esc_html_e( 'Block', 'sherblock' ); ?></th>
										<th scope="col" class="num"><?php esc_html_e( 'Total Uses', 'sherblock' ); ?></th>
										<th scope="col" class="num"><?php esc_html_e( 'Posts', 'sherblock' ); ?></th>
									</tr>
								</thead>
								<tbody>
									<?php foreach ( $block_stats as $stat ) : ?>
										<tr>
											<td>
												<a href="<?php echo esc_url( admin_url( 'admin.php?page=sherblock-block-detail&block=' . rawurlencode( (string) $stat['block_name'] ) ) ); ?>">
													<strong><?php echo esc_html( (string) $stat['block_name'] ); ?></strong>
												</a>
												<button type="button" class="sherblock-copy-btn" data-copy="<?php echo esc_attr( (string) $stat['block_name'] ); ?>" title="<?php esc_attr_e( 'Copy block name', 'sherblock' ); ?>">
													<span class="dashicons dashicons-admin-page"></span>
												</button>
											</td>
											<td class="num"><?php echo esc_html( number_format_i18n( (int) $stat['usage_count'] ) ); ?></td>
											<td class="num"><?php echo esc_html( number_format_i18n( (int) $stat['post_count'] ) ); ?></td>
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
