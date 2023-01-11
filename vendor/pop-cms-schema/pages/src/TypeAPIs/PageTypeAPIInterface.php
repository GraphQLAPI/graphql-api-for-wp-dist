<?php

declare (strict_types=1);
namespace PoPCMSSchema\Pages\TypeAPIs;

use PoPCMSSchema\CustomPosts\TypeAPIs\CustomPostTypeAPIInterface;
/**
 * Methods to interact with the Type, to be implemented by the underlying CMS
 */
interface PageTypeAPIInterface extends CustomPostTypeAPIInterface
{
    /**
     * Indicates if the passed object is of type Page
     * @param object $object
     */
    public function isInstanceOfPageType($object) : bool;
    /**
     * Indicate if an page with provided ID exists
     * @param int|string $id
     */
    public function pageExists($id) : bool;
    /**
     * Get the page with provided ID or, if it doesn't exist, null
     * @param int|string $id
     */
    public function getPage($id);
    /**
     * @param int|string|object $pageObjectOrID
     */
    public function getParentPage($pageObjectOrID);
    /**
     * @param int|string|object $pageObjectOrID
     * @return int|string|null
     */
    public function getParentPageID($pageObjectOrID);
    /**
     * Get the list of pages.
     * If param "status" in $query is not passed, it defaults to "publish"
     *
     * @return array<string|int>|object[]
     * @param array<string,mixed> $query
     * @param array<string,mixed> $options
     */
    public function getPages($query, $options = []) : array;
    /**
     * Get the number of pages.
     * If param "status" in $query is not passed, it defaults to "publish"
     * @param array<string,mixed> $query
     * @param array<string,mixed> $options
     */
    public function getPageCount($query, $options = []) : int;
    /**
     * Page custom post type
     */
    public function getPageCustomPostType() : string;
    /**
     * @return string|int
     * @param object $page
     */
    public function getPageID($page);
}
