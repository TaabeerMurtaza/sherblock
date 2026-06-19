<?php
/**
 * Blocks list view.
 *
 * @package SherBlock
 *
 * @var array $blocks TODO: Block[] from BlockRepository::findAll().
 */

defined( 'ABSPATH' ) || exit;
?>
<div class="wrap sherblock sherblock-blocks-list">
	<h1><?php esc_html_e( 'Registered Blocks', 'sherblock' ); ?></h1>
	<p class="description">
		<?php esc_html_e( 'All Gutenberg blocks discovered on this site.', 'sherblock' ); ?>
	</p>
	<?php // TODO: Render WP_List_Table or custom table of blocks. ?>
</div>
