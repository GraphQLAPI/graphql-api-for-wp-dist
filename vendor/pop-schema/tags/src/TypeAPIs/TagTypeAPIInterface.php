<?php

declare (strict_types=1);
namespace PoPSchema\Tags\TypeAPIs;

use PoPSchema\Taxonomies\TypeAPIs\TaxonomyTypeAPIInterface;
/**
 * Methods to interact with the Type, to be implemented by the underlying CMS
 */
interface TagTypeAPIInterface extends TaxonomyTypeAPIInterface
{
    /**
     * Indicates if the passed object is of type Tag
     * @param object $object
     */
    public function isInstanceOfTagType($object) : bool;
    /**
     * @return string|int
     * @param object $tag
     */
    public function getTagID($tag);
    /**
     * @param string|int $tagID
     * @return object
     */
    public function getTag($tagID);
    /**
     * @return object
     */
    public function getTagByName(string $tagName);
    public function getTags(array $query, array $options = []) : array;
    public function getTagCount(array $query = [], array $options = []) : int;
    /**
     * @param string|int $tagID
     */
    public function getTagURL($tagID) : string;
    /**
     * @param string|int|object $tagObjectOrID
     */
    public function getTagName($tagObjectOrID) : string;
    /**
     * @param string|int|object $tagObjectOrID
     */
    public function getTagSlug($tagObjectOrID) : string;
    /**
     * @param string|int|object $tagObjectOrID
     */
    public function getTagDescription($tagObjectOrID) : string;
    /**
     * @param string|int|object $tagObjectOrID
     */
    public function getTagItemCount($tagObjectOrID) : int;
    /**
     * @param string|int $customPostID
     */
    public function getCustomPostTags($customPostID, array $query = [], array $options = []) : array;
    /**
     * @param string|int $customPostID
     */
    public function getCustomPostTagCount($customPostID, array $query = [], array $options = []) : int;
}
