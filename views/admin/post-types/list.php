<?php
/**
 * Post types list view.
 *
 * @package SherBlock
 *
 * @var array $post_types TODO: PostType[] from PostTypeRepository::findAllBlockEnabled().
 */

defined( 'ABSPATH' ) || exit;
?>
<div class="wrap sherblock sherblock-cpts-list">
	<h1><?php esc_html_e( 'Gutenberg Post Types', 'sherblock' ); ?></h1>
	<p class="description">
		<?php esc_html_e( 'Custom post types that support the block editor.', 'sherblock' ); ?>
	</p>
	<?php // TODO: Render list with links to CPT detail pages. ?>
</div>
