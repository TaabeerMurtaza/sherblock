# SherBlock

A WordPress admin helper for inspecting Gutenberg blocks and block-enabled content across your site.

SherBlock gives developers and site builders a single place to see what custom blocks are registered, which post types support the block editor, and how blocks and content relate to each other.

## Overview

Gutenberg blocks can be registered by themes, plugins, and third-party block builders. Over time it becomes hard to answer simple questions:

- Which custom blocks exist on this site?
- Which post types use the block editor?
- Where is a specific block used?
- Which blocks appear on a given post or CPT entry?

SherBlock is built to answer those questions from the WordPress admin.

## Features

### Current

- **All registered blocks** — Browse every custom Gutenberg block registered on the site.
- **Block detail view** — Open a single block to see where it is used (posts, pages, and other content that contains that block).

### Planned

- **Gutenberg-supported CPTs** — List all custom post types that support the block editor.
- **CPT detail view** — Open a single post type to see which blocks are used across its entries.
- **Broader block-builder support** — Integration with popular block registration sources, including:
  - [Advanced Custom Fields (ACF)](https://www.advancedcustomfields.com/)
  - [Carbon Fields](https://carbonfields.net/)
  - [Lazy Blocks](https://www.lazyblocks.com/)
  - Additional plugins and frameworks as support is added.

## Admin pages

SherBlock is organized around two main admin sections. Each section has list views and detail views for individual entries.

### 1. Gutenberg-supported CPTs

A directory of custom post types on the site that support the block editor.

| View | Description |
|------|-------------|
| **CPT list** | All Gutenberg-enabled custom post types registered on the website. |
| **CPT detail** | Deep dive into one post type: see which blocks are used across its entries and how content is composed. |

### 2. Registered blocks

A directory of all Gutenberg blocks available on the site.

| View | Description |
|------|-------------|
| **Block list** | All custom Gutenberg blocks registered on the website. |
| **Block detail** | Deep dive into one block: see which posts, pages, and other entries use it. |

## Requirements

- WordPress 6.0 or later (recommended)
- PHP 7.4 or later
- A site using the block editor (Gutenberg)

## Installation

1. Copy the `sherblock` folder into `wp-content/plugins/`.
2. In **Plugins → Installed Plugins**, activate **SherBlock**.
3. Open the SherBlock menu in the WordPress admin.

## Development status

SherBlock is in early development. The initial focus is surfacing all custom Gutenberg blocks registered on a site, with CPT browsing and third-party block-builder integrations to follow.

## Author

**Taabeer Murtaza** — [GitHub](https://github.com/TaabeerMurtaza)

## License

GPL v2 or later.
