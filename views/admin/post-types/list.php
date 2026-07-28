<?php
/**
 * Post types list view.
 *
 * @package SherBlock
 *
 * @var \SherBlock\PostTypes\PostType[] $post_types Gutenberg-enabled custom post types.
 */

defined( 'ABSPATH' ) || exit;
?>
<div class="wrap sherblock sherblock-cpts-list">

	<div class="sherblock-page-header">
		<div>
			<h1><?php esc_html_e( 'Gutenberg Post Types', 'sherblock' ); ?></h1>
			<p class="description"><?php esc_html_e( 'Public post types that support the block editor.', 'sherblock' ); ?></p>
		</div>
	</div>

	<?php if ( empty( $post_types ) ) : ?>
		<div class="sherblock-empty-state">
			<span class="dashicons dashicons-admin-post"></span>
			<h3><?php esc_html_e( 'No Gutenberg post types found', 'sherblock' ); ?></h3>
			<p><?php esc_html_e( 'No custom post types with block editor support were found.', 'sherblock' ); ?></p>
		</div>
	<?php else : ?>
		<table class="widefat fixed striped sherblock-cpts-table">
			<thead>
				<tr>
					<th scope="col"><?php esc_html_e( 'Post Type', 'sherblock' ); ?></th>
					<th scope="col"><?php esc_html_e( 'Slug', 'sherblock' ); ?></th>
					<th scope="col"><?php esc_html_e( 'Visibility', 'sherblock' ); ?></th>
				</tr>
			</thead>
			<tbody>
				<?php foreach ( $post_types as $post_type ) : ?>
					<tr>
						<td>
							<a href="<?php echo esc_url( admin_url( 'admin.php?page=sherblock-cpt-detail&post_type=' . rawurlencode( $post_type->getName() ) ) ); ?>">
								<strong><?php echo esc_html( $post_type->getLabel() ); ?></strong>
							</a>
						</td>
						<td>
							<code><?php echo esc_html( $post_type->getName() ); ?></code>
							<button type="button" class="sherblock-copy-btn" data-copy="<?php echo esc_attr( $post_type->getName() ); ?>" title="<?php esc_attr_e( 'Copy slug', 'sherblock' ); ?>">
								<span class="dashicons dashicons-admin-page"></span>
							</button>
						</td>
						<td>
							<?php if ( $post_type->isPublic() ) : ?>
								<span class="status-badge status-badge--publish">
									<?php esc_html_e( 'Public', 'sherblock' ); ?>
								</span>
							<?php else : ?>
								<span class="status-badge status-badge--private">
									<?php esc_html_e( 'Private', 'sherblock' ); ?>
								</span>
							<?php endif; ?>
						</td>
					</tr>
				<?php endforeach; ?>
			</tbody>
		</table>
	<?php endif; ?>
</div>
