# SherBlock

A WordPress admin tool for inspecting, auditing, and tracking Gutenberg block usage across your site.

## Overview

SherBlock gives developers and site builders a single dashboard to discover registered blocks, track where they're used, and understand how content is composed across post types.

## Features

### Dashboard
- Overview stats: total blocks, indexed posts, active providers, Gutenberg post types
- Most used blocks chart (CSS-only, no external dependencies)
- Provider status indicators
- Recently indexed content feed
- One-click re-index with AJAX progress bar

### Block Discovery
- Automatic discovery from WordPress core, ACF, Carbon Fields, and Lazy Blocks
- Pluggable provider system — add new sources by implementing `BlockProviderInterface`
- Searchable, filterable block list by category and provider

### Usage Indexing
- Custom database table for fast block-to-post queries
- Auto-indexes on post save (configurable)
- Full site re-index with batched AJAX processing
- Recursive innerBlocks parsing

### Admin Pages
- **Block List** — Browse all registered blocks with search, category/provider filters, pagination
- **Block Detail** — View block metadata and every post that uses it, with status badges
- **Post Types** — List all Gutenberg-enabled post types
- **Post Type Detail** — Block frequency breakdown per post type
- **Unused Blocks** — Find registered blocks with zero usage
- **Settings** — Configure auto-indexing, batch size, debug logging

### Premium Features (via Freemius)
- REST API endpoints for programmatic access to all block data
- CSV export of block usage data
- Block usage trends over time
- Bulk re-index by post type
- Orphaned index cleanup
- Multisite support

## Requirements

| Dependency | Version |
|------------|---------|
| WordPress  | 6.0+    |
| PHP        | 8.0+    |

## Installation

1. Copy the `sherblock` folder into `wp-content/plugins/`.
2. Run `composer install` inside the plugin directory.
3. Activate **SherBlock** in **Plugins → Installed Plugins**.
4. Open the **SherBlock** menu in the WordPress admin.

## Architecture

SherBlock follows a layered, WordPress-native architecture:

```
Admin Pages (controllers) → Views (PHP templates)
       ↓
Repositories & Finders (data access)
       ↓
Services (Indexer, ProviderManager, BlockSupportChecker)
       ↓
Value Objects (Block, PostType, PostBlockUsage)
       ↓
Providers (pluggable block discovery)
       ↓
WordPress APIs / $wpdb
```

Key design decisions:
- **Immutable value objects** — `Block`, `PostType`, `PostBlockUsage` are typed, readonly objects
- **Repository pattern** — Interfaces at all persistence boundaries
- **Manual DI** — All wiring in `Plugin::registerServices()`, no container
- **WordPress-native** — Uses `add_action`, `admin_menu`, `dbDelta`, `$wpdb`, transients
- **`declare(strict_types=1)`** on every PHP file

## Block Providers

| Provider | ID | Plugin Required |
|----------|----|-----------------|
| WordPress Core | `core` | None |
| Advanced Custom Fields | `acf` | ACF Pro |
| Carbon Fields | `carbon-fields` | Carbon Fields |
| Lazy Blocks | `lazy-blocks` | Lazy Blocks |

## REST API

SherBlock provides a REST API for programmatic access (premium):

| Endpoint | Description |
|----------|-------------|
| `GET /wp-json/sherblock/v1/blocks` | List all registered blocks |
| `GET /wp-json/sherblock/v1/blocks/{name}` | Single block with usage count |
| `GET /wp-json/sherblock/v1/blocks/{name}/usage` | Posts using a specific block |
| `GET /wp-json/sherblock/v1/post-types` | Gutenberg-enabled post types |
| `GET /wp-json/sherblock/v1/post-types/{slug}/blocks` | Block frequency for a post type |
| `GET /wp-json/sherblock/v1/index/status` | Index health and stats |

## Development

### Setup

```bash
cd wp-content/plugins/sherblock
composer install
```

### Key directories

| Directory | Purpose |
|-----------|---------|
| `src/` | All PHP application code (PSR-4: `SherBlock\`) |
| `views/admin/` | PHP view templates (presentation only) |
| `assets/css/` | Admin stylesheet |
| `assets/js/` | Admin JavaScript |
| `tests/` | PHPUnit tests |

## Author

**Taabeer Murtaza** — [GitHub](https://github.com/TaabeerMurtaza)

## License

GPL v2 or later.
