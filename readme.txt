=== SherBlock ===
Contributors: taabeer
Tags: gutenberg, blocks, block-editor, audit, inspection
Requires at least: 6.0
Tested up to: 6.7
Requires PHP: 8.0
Stable tag: 1.0.0
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html

Inspect, audit, and track Gutenberg block usage across your entire WordPress site.

== Description ==

**SherBlock** gives WordPress developers and site administrators a single dashboard to discover registered blocks, track where they're used, and understand how content is composed across post types.

### Why SherBlock?

Gutenberg blocks can be registered by themes, plugins, and third-party block builders. Over time it becomes hard to answer simple questions:

* Which custom blocks are registered on this site?
* Which post types support the block editor?
* Where is a specific block used?
* Which blocks are unused and can be cleaned up?

SherBlock answers these questions from a clean, modern admin interface.

### Features

* **Dashboard** — Overview of block usage stats, most used blocks, and provider status
* **Block Discovery** — Automatically finds blocks from WordPress core, ACF, Carbon Fields, and Lazy Blocks
* **Usage Indexing** — Tracks exactly which blocks appear in which posts
* **Block Browser** — Searchable, filterable list of all registered blocks
* **Block Detail** — See everywhere a specific block is used with one click
* **Post Type Browser** — View all Gutenberg-enabled post types and their block frequency
* **Unused Blocks** — Find registered blocks that aren't used in any content
* **Settings** — Configure auto-indexing, batch sizes, and debug logging
* **REST API** — Programmatic access to block data (premium)
* **CSV Export** — Export block usage data to CSV (premium)

### Block Provider Support

SherBlock automatically discovers blocks from:

* **WordPress Core** — All blocks registered via `WP_Block_Type_Registry`
* **Advanced Custom Fields (ACF)** — Blocks registered via `acf_register_block_type()`
* **Carbon Fields** — Blocks prefixed with `carbon-fields/`
* **Lazy Blocks** — Blocks stored as `lazyblocks` post type entries

### REST API (Premium)

Access block data programmatically via the WordPress REST API:

* `GET /wp-json/sherblock/v1/blocks` — List all blocks
* `GET /wp-json/sherblock/v1/blocks/{name}` — Single block details
* `GET /wp-json/sherblock/v1/blocks/{name}/usage` — Posts using a block
* `GET /wp-json/sherblock/v1/post-types` — Gutenberg post types
* `GET /wp-json/sherblock/v1/post-types/{slug}/blocks` — Block frequency per post type
* `GET /wp-json/sherblock/v1/index/status` — Index health

== Installation ==

1. Upload the `sherblock` folder to `/wp-content/plugins/`.
2. Activate **SherBlock** through the **Plugins** menu in WordPress.
3. Open the **SherBlock** menu in the admin sidebar.
4. The plugin will automatically index all your content on activation.

== Frequently Asked Questions ==

= Does SherBlock modify my content? =

No. SherBlock is a read-only inspection tool. It indexes block usage in a custom database table but never modifies your posts, pages, or any other content.

= Does it work with custom post types? =

Yes. SherBlock automatically detects all public post types that support the block editor.

= What block builders are supported? =

SherBlock discovers blocks from WordPress core, Advanced Custom Fields (ACF), Carbon Fields, and Lazy Blocks. Additional providers can be added via the plugin's provider system.

= Do I need to configure anything? =

No. SherBlock works out of the box. It indexes your content on activation and updates the index whenever you save a post. You can optionally configure settings under SherBlock > Settings.

== Screenshots ==

1. Dashboard overview with block usage stats and charts
2. Block list with search, filter by category/provider
3. Block detail showing metadata and usage by post
4. Post types list showing Gutenberg-enabled CPTs
5. CPT detail with block frequency breakdown
6. Unused blocks detection
7. Settings page

== Changelog ==

= 1.0.0 =
* Initial release
* Block discovery from core, ACF, Carbon Fields, and Lazy Blocks
* Block usage indexing via custom database table
* Dashboard with stats, most used blocks chart, and provider status
* Block list with search, category filter, and provider filter
* Block detail with usage-by-post table
* Post type list and detail views
* Unused blocks detection
* AJAX re-indexing with progress bar
* Settings page for auto-indexing, batch size, and debug logging
* REST API endpoints for programmatic access
* CSV export of block usage data
* Modern WP-native admin UI
* Full i18n support

== Upgrade Notice ==

= 1.0.0 =
Initial release. Install and activate to start tracking Gutenberg block usage.
