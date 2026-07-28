<?php
/**
 * REST API route registration.
 *
 * @package SherBlock\Api
 */

declare(strict_types=1);

namespace SherBlock\Api;

use SherBlock\Blocks\BlockRepositoryInterface;
use SherBlock\Blocks\BlockUsageFinder;
use SherBlock\Index\IndexRepositoryInterface;
use SherBlock\PostTypes\PostTypeRepositoryInterface;

/**
 * Registers REST API routes under /sherblock/v1.
 */
final class RestApiController {

	private const NAMESPACE = 'sherblock/v1';

	public function __construct(
		private readonly BlockRepositoryInterface $blockRepository,
		private readonly PostTypeRepositoryInterface $postTypeRepository,
		private readonly IndexRepositoryInterface $indexRepository,
		private readonly BlockUsageFinder $blockUsageFinder,
	) {
	}

	public function register(): void {
		add_action( 'rest_api_init', [ $this, 'registerRoutes' ] );
	}

	public function registerRoutes(): void {
		register_rest_route(
			self::NAMESPACE,
			'/blocks',
			[
				'methods'             => 'GET',
				'callback'            => [ $this, 'getBlocks' ],
				'permission_callback' => [ $this, 'checkPermission' ],
			]
		);

		register_rest_route(
			self::NAMESPACE,
			'/blocks/(?P<name>[a-z0-9\-/]+)',
			[
				'methods'             => 'GET',
				'callback'            => [ $this, 'getBlock' ],
				'permission_callback' => [ $this, 'checkPermission' ],
			]
		);

		register_rest_route(
			self::NAMESPACE,
			'/blocks/(?P<name>[a-z0-9\-/]+)/usage',
			[
				'methods'             => 'GET',
				'callback'            => [ $this, 'getBlockUsage' ],
				'permission_callback' => [ $this, 'checkPermission' ],
			]
		);

		register_rest_route(
			self::NAMESPACE,
			'/post-types',
			[
				'methods'             => 'GET',
				'callback'            => [ $this, 'getPostTypes' ],
				'permission_callback' => [ $this, 'checkPermission' ],
			]
		);

		register_rest_route(
			self::NAMESPACE,
			'/post-types/(?P<slug>[a-z0-9\-_]+)/blocks',
			[
				'methods'             => 'GET',
				'callback'            => [ $this, 'getPostTypeBlocks' ],
				'permission_callback' => [ $this, 'checkPermission' ],
			]
		);

		register_rest_route(
			self::NAMESPACE,
			'/index/status',
			[
				'methods'             => 'GET',
				'callback'            => [ $this, 'getIndexStatus' ],
				'permission_callback' => [ $this, 'checkPermission' ],
			]
		);
	}

	public function checkPermission(): bool {
		return current_user_can( 'manage_options' );
	}

	public function getBlocks(): \WP_REST_Response {
		$blocks = $this->blockRepository->findAll();

		$data = array_map(
			static fn( $block ): array => [
				'name'     => $block->getName(),
				'title'    => $block->getTitle(),
				'category' => $block->getCategory(),
				'provider' => $block->getProvider(),
			],
			$blocks
		);

		return new \WP_REST_Response( $data );
	}

	public function getBlock( \WP_REST_Request $request ): \WP_REST_Response {
		$name  = $request->get_param( 'name' );
		$block = $this->blockRepository->findByName( $name );

		if ( null === $block ) {
			return new \WP_REST_Response( [ 'message' => 'Block not found.' ], 404 );
		}

		return new \WP_REST_Response( [
			'name'       => $block->getName(),
			'title'      => $block->getTitle(),
			'category'   => $block->getCategory(),
			'provider'   => $block->getProvider(),
			'usage_count' => $this->blockUsageFinder->countUsages( $name ),
		] );
	}

	public function getBlockUsage( \WP_REST_Request $request ): \WP_REST_Response {
		$name   = $request->get_param( 'name' );
		$usages = $this->blockUsageFinder->findPostsUsingBlock( $name );

		$data = array_map(
			static fn( $usage ): array => [
				'post_id'            => $usage->getPostId(),
				'title'              => $usage->getTitle(),
				'post_type'          => $usage->getPostType(),
				'post_type_label'    => $usage->getPostTypeLabel(),
				'status'             => $usage->getStatus(),
				'block_occurrences'  => $usage->getBlockOccurrences(),
				'total_block_types'  => $usage->getTotalBlockTypes(),
				'edit_link'          => $usage->getEditLink(),
			],
			$usages
		);

		return new \WP_REST_Response( $data );
	}

	public function getPostTypes(): \WP_REST_Response {
		$post_types = $this->postTypeRepository->findAllBlockEnabled();

		$data = array_map(
			static fn( $pt ): array => [
				'name'          => $pt->getName(),
				'label'         => $pt->getLabel(),
				'supports_blocks' => $pt->supportsBlocks(),
				'is_public'     => $pt->isPublic(),
			],
			$post_types
		);

		return new \WP_REST_Response( $data );
	}

	public function getPostTypeBlocks( \WP_REST_Request $request ): \WP_REST_Response {
		$slug  = $request->get_param( 'slug' );
		$stats = $this->indexRepository->findByPostType( $slug );

		return new \WP_REST_Response( $stats );
	}

	public function getIndexStatus(): \WP_REST_Response {
		return new \WP_REST_Response( [
			'total_posts' => $this->indexRepository->countDistinctPosts(),
		] );
	}
}
