<?php

declare (strict_types=1);
namespace PoPSchema\CustomPosts\TypeAPIs;

use PoPSchema\CustomPosts\Types\CustomPostTypeInterface;
/**
 * Methods to interact with the Type, to be implemented by the underlying CMS
 */
interface CustomPostTypeAPIInterface extends CustomPostTypeInterface
{
    /**
     * Get the custom post with provided ID or, if it doesn't exist, null
     * @param int|string $id
     * @return object|null
     */
    public function getCustomPost($id);
    /**
     * @param string|int|object $objectOrID
     */
    public function getCustomPostType($objectOrID) : string;
    /**
     * @param array<string, mixed> $query
     * @param array<string, mixed> $options
     * @return object[]
     */
    public function getCustomPosts(array $query, array $options = []) : array;
    public function getCustomPostCount(array $query = [], array $options = []) : int;
    public function getCustomPostTypes(array $query = array()) : array;
}
