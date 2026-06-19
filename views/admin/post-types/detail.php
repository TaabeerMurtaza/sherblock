<?php
/**
 * Post type detail view.
 *
 * @package SherBlock
 *
 * @var string $post_type      Post type slug.
 * @var mixed  $post_type_obj  TODO: SherBlock\PostTypes\PostType|null.
 * @var array  $block_stats    TODO: Aggregated block usage for this CPT.
 */

defined( 'ABSPATH' ) || exit;
?>
<div class="wrap sherblock sherblock-cpt-detail">
	<h1><?php esc_html_e( 'Post Type Detail', 'sherblock' ); ?></h1>
	<?php if ( $post_type ) : ?>
		<p>
			<code><?php echo esc_html( $post_type ); ?></code>
		</p>
	<?php endif; ?>
	<?php // TODO: Show block frequency and sample posts per block. ?>
</div>
