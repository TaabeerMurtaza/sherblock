<?php
/**
 * Blocks list view.
 *
 * @package SherBlock
 *
 * @var \SherBlock\Blocks\Block[] $blocks            Blocks for the current page.
 * @var string[]                    $categories        Available category filter values.
 * @var string[]                    $providers         Available provider filter values.
 * @var string|null                 $filter_category   Active category filter, if any.
 * @var string|null                 $filter_provider   Active provider filter, if any.
 * @var string|null                 $search_query      Active block name search, if any.
 * @var bool                        $filters_active    Whether any filter or search is applied.
 * @var string                      $clear_filters_url URL to reset filters.
 * @var int                         $current_page      Current page number (1-based).
 * @var int                         $total_pages       Total number of pages.
 * @var int                         $total_items       Total number of blocks.
 * @var int                         $per_page          Blocks shown per page.
 * @var string                      $pagination_base   Base URL for paginate_links().
 */

defined( 'ABSPATH' ) || exit;

$pagination_args = [
	'base'      => $pagination_base,
	'format'    => '',
	'prev_text' => '&laquo;',
	'next_text' => '&raquo;',
	'total'     => $total_pages,
	'current'   => $current_page,
];

?>
<div class="wrap sherblock sherblock-blocks-list">

	<div class="sherblock-page-header">
		<div>
			<h1><?php esc_html_e( 'Registered Blocks', 'sherblock' ); ?></h1>
			<p class="description"><?php esc_html_e( 'All Gutenberg blocks discovered on this site.', 'sherblock' ); ?></p>
		</div>
	</div>

	<div class="sherblock-filter-bar">
		<form method="get" class="sherblock-blocks-filters" style="display: contents;">
			<input type="hidden" name="page" value="sherblock-blocks" />
			<div class="search-input">
				<span class="dashicons dashicons-search"></span>
				<label for="sherblock-search" class="screen-reader-text">
					<?php esc_html_e( 'Search by block name', 'sherblock' ); ?>
				</label>
				<input
					type="search"
					name="search"
					id="sherblock-search"
					value="<?php echo esc_attr( $search_query ?? '' ); ?>"
					placeholder="<?php esc_attr_e( 'Search block name…', 'sherblock' ); ?>"
				/>
			</div>
			<label for="sherblock-filter-category" class="screen-reader-text">
				<?php esc_html_e( 'Filter by category', 'sherblock' ); ?>
			</label>
			<select name="category" id="sherblock-filter-category">
				<option value=""><?php esc_html_e( 'All categories', 'sherblock' ); ?></option>
				<?php foreach ( $categories as $category ) : ?>
					<option value="<?php echo esc_attr( $category ); ?>" <?php selected( $filter_category, $category ); ?>>
						<?php echo esc_html( $category ); ?>
					</option>
				<?php endforeach; ?>
			</select>
			<label for="sherblock-filter-provider" class="screen-reader-text">
				<?php esc_html_e( 'Filter by provider', 'sherblock' ); ?>
			</label>
			<select name="provider" id="sherblock-filter-provider">
				<option value=""><?php esc_html_e( 'All providers', 'sherblock' ); ?></option>
				<?php foreach ( $providers as $provider ) : ?>
					<option value="<?php echo esc_attr( $provider ); ?>" <?php selected( $filter_provider, $provider ); ?>>
						<?php echo esc_html( $provider ); ?>
					</option>
				<?php endforeach; ?>
			</select>
			<?php submit_button( __( 'Apply', 'sherblock' ), '', 'filter_action', false ); ?>
			<?php if ( $filters_active ) : ?>
				<a href="<?php echo esc_url( $clear_filters_url ); ?>" class="button">
					<?php esc_html_e( 'Clear', 'sherblock' ); ?>
				</a>
			<?php endif; ?>
		</form>
	</div>

	<?php if ( $filters_active && $total_items > 0 ) : ?>
		<div class="sherblock-active-filters">
			<span><?php esc_html_e( 'Active filters:', 'sherblock' ); ?></span>
			<?php if ( null !== $filter_category ) : ?>
				<span class="sherblock-filter-pill">
					<?php
					printf(
						/* translators: %s: category name */
						esc_html__( 'Category: %s', 'sherblock' ),
						esc_html( $filter_category )
					);
					?>
				</span>
			<?php endif; ?>
			<?php if ( null !== $filter_provider ) : ?>
				<span class="sherblock-filter-pill">
					<?php
					printf(
						/* translators: %s: provider name */
						esc_html__( 'Provider: %s', 'sherblock' ),
						esc_html( $filter_provider )
					);
					?>
				</span>
			<?php endif; ?>
			<?php if ( null !== $search_query ) : ?>
				<span class="sherblock-filter-pill">
					<?php
					printf(
						/* translators: %s: search query */
						esc_html__( 'Search: %s', 'sherblock' ),
						esc_html( $search_query )
					);
					?>
				</span>
			<?php endif; ?>
		</div>
	<?php endif; ?>

	<?php if ( $total_items > 0 ) : ?>
		<div class="tablenav top">
			<div class="tablenav-pages">
				<span class="displaying-num">
					<?php
					printf(
						/* translators: %s: number of blocks */
						esc_html( _n( '%s item', '%s items', $total_items, 'sherblock' ) ),
						esc_html( number_format_i18n( $total_items ) )
					);
					?>
				</span>
				<?php if ( $total_pages > 1 ) : ?>
					<?php echo wp_kses_post( paginate_links( $pagination_args ) ); ?>
				<?php endif; ?>
			</div>
		</div>
	<?php endif; ?>

	<?php if ( empty( $blocks ) && 0 === $total_items ) : ?>
		<div class="sherblock-empty-state">
			<span class="dashicons dashicons-screenoptions"></span>
			<?php if ( $filters_active ) : ?>
				<h3><?php esc_html_e( 'No matching blocks', 'sherblock' ); ?></h3>
				<p><?php esc_html_e( 'No blocks match your search or filters. Try adjusting your criteria.', 'sherblock' ); ?></p>
			<?php else : ?>
				<h3><?php esc_html_e( 'No blocks found', 'sherblock' ); ?></h3>
				<p><?php esc_html_e( 'No Gutenberg blocks are registered on this site yet.', 'sherblock' ); ?></p>
			<?php endif; ?>
		</div>
	<?php else : ?>
		<table class="widefat fixed striped sherblock-blocks-table">
			<thead>
				<tr>
					<th scope="col"><?php esc_html_e( 'Block', 'sherblock' ); ?></th>
					<th scope="col"><?php esc_html_e( 'Name', 'sherblock' ); ?></th>
					<th scope="col"><?php esc_html_e( 'Category', 'sherblock' ); ?></th>
					<th scope="col"><?php esc_html_e( 'Provider', 'sherblock' ); ?></th>
				</tr>
			</thead>
			<tbody>
				<?php foreach ( $blocks as $block ) : ?>
					<?php
					$provider_id   = $block->getProvider();
					$provider_slug = sanitize_html_class( $provider_id );
					?>
					<tr>
						<td>
							<a href="<?php echo esc_url( admin_url( 'admin.php?page=sherblock-block-detail&block=' . rawurlencode( $block->getName() ) ) ); ?>">
								<strong><?php echo esc_html( $block->getTitle() ); ?></strong>
							</a>
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
								<code><?php echo esc_html( $provider_id ); ?></code>
							</span>
						</td>
					</tr>
				<?php endforeach; ?>
			</tbody>
		</table>

		<?php if ( $total_pages > 1 ) : ?>
			<div class="tablenav bottom">
				<div class="tablenav-pages">
					<span class="displaying-num">
						<?php
						printf(
							/* translators: %s: number of blocks */
							esc_html( _n( '%s item', '%s items', $total_items, 'sherblock' ) ),
							esc_html( number_format_i18n( $total_items ) )
						);
						?>
					</span>
					<?php echo wp_kses_post( paginate_links( $pagination_args ) ); ?>
				</div>
			</div>
		<?php endif; ?>
	<?php endif; ?>
</div>
