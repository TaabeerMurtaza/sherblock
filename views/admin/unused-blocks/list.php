<?php
/**
 * Unused blocks view.
 *
 * @package SherBlock
 *
 * @var \SherBlock\Blocks\Block[] $unused_blocks Blocks with zero indexed usage.
 */

defined( 'ABSPATH' ) || exit;
?>
<div class="wrap sherblock sherblock-unused-blocks">

	<div class="sherblock-page-header">
		<div>
			<h1><?php esc_html_e( 'Unused Blocks', 'sherblock' ); ?></h1>
			<p class="description"><?php esc_html_e( 'Registered blocks that are not used in any indexed content.', 'sherblock' ); ?></p>
		</div>
	</div>

	<?php if ( empty( $unused_blocks ) ) : ?>
		<div class="sherblock-empty-state">
			<span class="dashicons dashicons-yes-alt"></span>
			<h3><?php esc_html_e( 'All blocks are in use', 'sherblock' ); ?></h3>
			<p><?php esc_html_e( 'Every registered block appears in at least one piece of content.', 'sherblock' ); ?></p>
		</div>
	<?php else : ?>
		<div class="sherblock-panel" style="margin-bottom: 24px;">
			<div class="sherblock-panel-header">
				<h2>
					<?php
					printf(
						/* translators: %s: number of unused blocks */
						esc_html__( '%s unused blocks found', 'sherblock' ),
						esc_html( number_format_i18n( count( $unused_blocks ) ) )
					);
					?>
				</h2>
			</div>
			<div class="sherblock-panel-body">
				<table class="widefat fixed striped">
					<thead>
						<tr>
							<th scope="col"><?php esc_html_e( 'Block', 'sherblock' ); ?></th>
							<th scope="col"><?php esc_html_e( 'Name', 'sherblock' ); ?></th>
							<th scope="col"><?php esc_html_e( 'Category', 'sherblock' ); ?></th>
							<th scope="col"><?php esc_html_e( 'Provider', 'sherblock' ); ?></th>
						</tr>
					</thead>
					<tbody>
						<?php foreach ( $unused_blocks as $block ) : ?>
							<?php
							$provider_slug = sanitize_html_class( $block->getProvider() );
							?>
							<tr>
								<td>
									<strong><?php echo esc_html( $block->getTitle() ); ?></strong>
								</td>
								<td>
									<code><?php echo esc_html( $block->getName() ); ?></code>
									<button type="button" class="sherblock-copy-btn" data-copy="<?php echo esc_attr( $block->getName() ); ?>" title="<?php esc_attr_e( 'Copy block name', 'sherblock' ); ?>">
										<span class="dashicons dashicons-admin-page"></span>
									</button>
								</td>
								<td><?php echo esc_html( $block->getCategory() ?: '—' ); ?></td>
								<td>
									<span class="provider-badge">
										<span class="provider-dot provider-dot--<?php echo esc_attr( $provider_slug ); ?>"></span>
										<code><?php echo esc_html( $block->getProvider() ); ?></code>
									</span>
								</td>
							</tr>
						<?php endforeach; ?>
					</tbody>
				</table>
			</div>
		</div>
	<?php endif; ?>
</div>
