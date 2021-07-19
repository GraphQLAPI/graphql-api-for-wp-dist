<?php

declare (strict_types=1);
namespace PoPSchema\CustomPosts\Types;

interface CustomPostTypeInterface
{
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
    public function getPlainTextContent($customPostObjectOrID) : string;
    /**
     * @param string|int|object $customPostObjectOrID
     */
    public function getPermalink($customPostObjectOrID) : ?string;
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
     */
    public function getPublishedDate($customPostObjectOrID) : ?string;
    /**
     * @param string|int|object $customPostObjectOrID
     */
    public function getModifiedDate($customPostObjectOrID) : ?string;
    /**
     * @param string|int|object $customPostObjectOrID
     */
    public function getTitle($customPostObjectOrID) : ?string;
    /**
     * @param string|int|object $customPostObjectOrID
     */
    public function getExcerpt($customPostObjectOrID) : ?string;
}
