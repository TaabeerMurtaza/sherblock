<?php
/**
 * Block detail view.
 *
 * @package SherBlock
 *
 * @var string $block_name Block identifier (namespace/block-name).
 * @var mixed  $block      TODO: SherBlock\Blocks\Block|null.
 * @var array  $usages     TODO: Posts using this block.
 */

defined( 'ABSPATH' ) || exit;
?>
<div class="wrap sherblock sherblock-block-detail">
	<h1><?php esc_html_e( 'Block Detail', 'sherblock' ); ?></h1>
	<?php if ( $block_name ) : ?>
		<p>
			<code><?php echo esc_html( $block_name ); ?></code>
		</p>
	<?php endif; ?>
	<?php // TODO: Show block metadata, provider, and usage table. ?>
</div>
