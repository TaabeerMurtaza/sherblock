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
	<p>
		<a href="<?php echo esc_url( $blocks_list_url ); ?>">&larr; <?php esc_html_e( 'Back to blocks', 'sherblock' ); ?></a>
	</p>

	<?php if ( '' === $block_name ) : ?>
		<h1><?php esc_html_e( 'Block Detail', 'sherblock' ); ?></h1>
		<p><?php esc_html_e( 'No block was specified. Choose a block from the blocks list.', 'sherblock' ); ?></p>
	<?php elseif ( $block ) : ?>
		<h1><?php echo esc_html( $block->getTitle() ); ?></h1>
		<p class="description">
			<?php esc_html_e( 'Posts and content entries where this block appears.', 'sherblock' ); ?>
		</p>

		<table class="form-table sherblock-block-meta" role="presentation">
			<tbody>
				<tr>
					<th scope="row"><?php esc_html_e( 'Block name', 'sherblock' ); ?></th>
					<td><code><?php echo esc_html( $block->getName() ); ?></code></td>
				</tr>
				<tr>
					<th scope="row"><?php esc_html_e( 'Category', 'sherblock' ); ?></th>
					<td><?php echo esc_html( $block->getCategory() ?: '—' ); ?></td>
				</tr>
				<tr>
					<th scope="row"><?php esc_html_e( 'Provider', 'sherblock' ); ?></th>
					<td><code><?php echo esc_html( $block->getProvider() ); ?></code></td>
				</tr>
				<tr>
					<th scope="row"><?php esc_html_e( 'Posts using this block', 'sherblock' ); ?></th>
					<td><?php echo esc_html( number_format_i18n( $usage_count ) ); ?></td>
				</tr>
			</tbody>
		</table>

		<h2><?php esc_html_e( 'Usage by post', 'sherblock' ); ?></h2>

		<?php if ( empty( $usages ) ) : ?>
			<p><?php esc_html_e( 'This block is not used on any indexed content yet.', 'sherblock' ); ?></p>
		<?php else : ?>
			<table class="widefat fixed striped sherblock-block-usage-table">
				<thead>
					<tr>
						<th scope="col"><?php esc_html_e( 'Post', 'sherblock' ); ?></th>
						<th scope="col"><?php esc_html_e( 'Post type', 'sherblock' ); ?></th>
						<th scope="col"><?php esc_html_e( 'Status', 'sherblock' ); ?></th>
						<th scope="col" class="num"><?php esc_html_e( 'Block occurrences', 'sherblock' ); ?></th>
						<th scope="col" class="num"><?php esc_html_e( 'Total block types', 'sherblock' ); ?></th>
					</tr>
				</thead>
				<tbody>
					<?php foreach ( $usages as $usage ) : ?>
						<?php
						$status_object = get_post_status_object( $usage->getStatus() );
						$status_label  = $status_object->label ?? $usage->getStatus();
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
							<td><?php echo esc_html( (string) $status_label ); ?></td>
							<td class="num"><?php echo esc_html( number_format_i18n( $usage->getBlockOccurrences() ) ); ?></td>
							<td class="num"><?php echo esc_html( number_format_i18n( $usage->getTotalBlockTypes() ) ); ?></td>
						</tr>
					<?php endforeach; ?>
				</tbody>
			</table>
		<?php endif; ?>
	<?php endif; ?>
</div>
