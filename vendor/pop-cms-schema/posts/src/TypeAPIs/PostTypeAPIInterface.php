<?php

declare (strict_types=1);
namespace PoPCMSSchema\Posts\TypeAPIs;

use PoPCMSSchema\CustomPosts\TypeAPIs\CustomPostTypeAPIInterface;
/**
 * Methods to interact with the Type, to be implemented by the underlying CMS
 */
interface PostTypeAPIInterface extends CustomPostTypeAPIInterface
{
    /**
     * Indicates if the passed object is of type Post
     * @param object $object
     */
    public function isInstanceOfPostType($object) : bool;
    /**
     * Indicate if an post with provided ID exists
     * @param int|string $id
     */
    public function postExists($id) : bool;
    /**
     * Get the post with provided ID or, if it doesn't exist, null
     * @param int|string $id
     */
    public function getPost($id);
    /**
     * Get the list of posts.
     * If param "status" in $query is not passed, it defaults to "publish"
     *
     * @return array<string|int>|object[]
     * @param array<string,mixed> $query
     * @param array<string,mixed> $options
     */
    public function getPosts($query, $options = []) : array;
    /**
     * Get the number of posts.
     * If param "status" in $query is not passed, it defaults to "publish"
     * @param array<string,mixed> $query
     * @param array<string,mixed> $options
     */
    public function getPostCount($query, $options = []) : int;
    /**
     * Post custom post type
     */
    public function getPostCustomPostType() : string;
}
