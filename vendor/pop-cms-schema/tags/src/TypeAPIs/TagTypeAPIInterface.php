<?php

declare (strict_types=1);
namespace PoPCMSSchema\Tags\TypeAPIs;

use PoPCMSSchema\Taxonomies\TypeAPIs\TaxonomyTypeAPIInterface;
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
     */
    public function getTag($tagID);
    /**
     * @param int|string $id
     */
    public function tagExists($id) : bool;
    /**
     * @param string $tagName
     */
    public function getTagByName($tagName);
    /**
     * @return array<string|int>|object[]
     * @param array<string,mixed> $query
     * @param array<string,mixed> $options
     */
    public function getTags($query, $options = []) : array;
    /**
     * @param array<string,mixed> $query
     * @param array<string,mixed> $options
     */
    public function getTagCount($query = [], $options = []) : int;
    /**
     * @param string|int|object $tagObjectOrID
     */
    public function getTagURL($tagObjectOrID) : ?string;
    /**
     * @param string|int|object $tagObjectOrID
     */
    public function getTagURLPath($tagObjectOrID) : ?string;
    /**
     * @param string|int|object $tagObjectOrID
     */
    public function getTagName($tagObjectOrID) : ?string;
    /**
     * @param string|int|object $tagObjectOrID
     */
    public function getTagSlug($tagObjectOrID) : ?string;
    /**
     * @param string|int|object $tagObjectOrID
     */
    public function getTagDescription($tagObjectOrID) : ?string;
    /**
     * @param string|int|object $tagObjectOrID
     */
    public function getTagItemCount($tagObjectOrID) : ?int;
    /**
     * @param array<string,mixed> $query
     * @param array<string,mixed> $options
     * @return array<string|int>|object[]
     * @param string|int|object $customPostObjectOrID
     */
    public function getCustomPostTags($customPostObjectOrID, $query = [], $options = []) : array;
    /**
     * @param array<string,mixed> $query
     * @param array<string,mixed> $options
     * @param string|int|object $customPostObjectOrID
     */
    public function getCustomPostTagCount($customPostObjectOrID, $query = [], $options = []) : ?int;
}
