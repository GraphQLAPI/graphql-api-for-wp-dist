<?php

declare(strict_types=1);

namespace PoPCMSSchema\PostsWP\TypeAPIs;

use PoP\Root\App;
use PoPCMSSchema\CustomPostsWP\TypeAPIs\AbstractCustomPostTypeAPI;
use PoPCMSSchema\Posts\TypeAPIs\PostTypeAPIInterface;
use WP_Post;

use function get_post;

/**
 * Methods to interact with the Type, to be implemented by the underlying CMS
 */
class PostTypeAPI extends AbstractCustomPostTypeAPI implements PostTypeAPIInterface
{
    public const HOOK_QUERY = __CLASS__ . ':query';

    /**
     * Add an extra hook just to modify posts
     *
     * @param array<string,mixed> $query
     * @param array<string,mixed> $options
     * @return array<string,mixed>
     */
    protected function convertCustomPostsQuery($query, $options = []): array
    {
        return App::applyFilters(
            self::HOOK_QUERY,
            parent::convertCustomPostsQuery($query, $options),
            $options
        );
    }

    /**
     * Query args that must always be in the query
     *
     * @return array<string,mixed>
     */
    public function getCustomPostQueryRequiredArgs(): array
    {
        return array_merge(
            parent::getCustomPostQueryRequiredArgs(),
            [
                'custompost-types' => ['post'],
            ]
        );
    }

    /**
     * Indicates if the passed object is of type Post
     * @param object $object
     */
    public function isInstanceOfPostType($object): bool
    {
        return ($object instanceof WP_Post) && $object->post_type === 'post';
    }

    /**
     * Get the post with provided ID or, if it doesn't exist, null
     * @param int|string $id
     */
    public function getPost($id)
    {
        $post = get_post((int)$id);
        if ($post === null || $post->post_type !== 'post') {
            return null;
        }
        return $post;
    }

    /**
     * Indicate if an post with provided ID exists
     * @param int|string $id
     */
    public function postExists($id): bool
    {
        return $this->getPost($id) !== null;
    }

    /**
     * @return array<string|int>|object[]
     * @param array<string,mixed> $query
     * @param array<string,mixed> $options
     */
    public function getPosts($query, $options = []): array
    {
        return $this->getCustomPosts($query, $options);
    }
    /**
     * @param array<string,mixed> $query
     * @param array<string,mixed> $options
     */
    public function getPostCount($query, $options = []): int
    {
        return $this->getCustomPostCount($query, $options);
    }
    public function getPostCustomPostType(): string
    {
        return 'post';
    }
}
