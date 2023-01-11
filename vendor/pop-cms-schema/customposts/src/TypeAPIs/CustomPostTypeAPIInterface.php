<?php

declare (strict_types=1);
namespace PoPCMSSchema\CustomPosts\TypeAPIs;

/**
 * Methods to interact with the Type, to be implemented by the underlying CMS
 */
interface CustomPostTypeAPIInterface
{
    /**
     * Indicates if the passed object is of type (Generic)CustomPost
     * @param object $object
     */
    public function isInstanceOfCustomPostType($object) : bool;
    /**
     * Indicate if an post with provided ID exists
     * @param int|string $id
     */
    public function customPostExists($id) : bool;
    /**
     * Return the object's ID
     * @return string|int
     * @param object $customPostObject
     */
    public function getID($customPostObject);
    /**
     * @param string|int|object $customPostObjectOrID
     */
    public function getContent($customPostObjectOrID) : ?string;
    /**
     * @param string|int|object $customPostObjectOrID
     */
    public function getRawContent($customPostObjectOrID) : ?string;
    /**
     * @param string|int|object $customPostObjectOrID
     */
    public function getPermalink($customPostObjectOrID) : ?string;
    /**
     * @param string|int|object $customPostObjectOrID
     */
    public function getPermalinkPath($customPostObjectOrID) : ?string;
    /**
     * @param string|int|object $customPostObjectOrID
     */
    public function getSlug($customPostObjectOrID) : ?string;
    /**
     * @param string|int|object $customPostObjectOrID
     */
    public function getStatus($customPostObjectOrID) : ?string;
    /**
     * @param string|int|object $customPostObjectOrID
     * @param bool $gmt
     */
    public function getPublishedDate($customPostObjectOrID, $gmt = \false) : ?string;
    /**
     * @param string|int|object $customPostObjectOrID
     * @param bool $gmt
     */
    public function getModifiedDate($customPostObjectOrID, $gmt = \false) : ?string;
    /**
     * @param string|int|object $customPostObjectOrID
     */
    public function getTitle($customPostObjectOrID) : ?string;
    /**
     * @param string|int|object $customPostObjectOrID
     */
    public function getExcerpt($customPostObjectOrID) : ?string;
    /**
     * Get the custom post with provided ID or, if it doesn't exist, null
     * @param int|string $id
     */
    public function getCustomPost($id);
    /**
     * @param string|int|object $customPostObjectOrID
     */
    public function getCustomPostType($customPostObjectOrID) : ?string;
    /**
     * If param "status" in $query is not passed, it defaults to "publish"
     *
     * @param array<string,mixed> $query
     * @param array<string,mixed> $options
     * @return array<string|int>|object[]
     */
    public function getCustomPosts($query, $options = []) : array;
    /**
     * If param "status" in $query is not passed, it defaults to "publish"
     * @param array<string,mixed> $query
     * @param array<string,mixed> $options
     */
    public function getCustomPostCount($query, $options = []) : int;
    /**
     * @return string[]
     * @param array<string,mixed> $query
     */
    public function getCustomPostTypes($query = array()) : array;
}
