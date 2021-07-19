<?php

declare (strict_types=1);
namespace PoPSchema\Pages\TypeAPIs;

use PoPSchema\CustomPosts\TypeAPIs\CustomPostTypeAPIInterface;
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
     * @return object|null
     */
    public function getPage($id);
    /**
     * Get the list of pages
     */
    public function getPages(array $query, array $options = []) : array;
    /**
     * Get the number of pages
     */
    public function getPageCount(array $query = [], array $options = []) : int;
    /**
     * Page custom post type
     */
    public function getPageCustomPostType() : string;
    /**
     * @return string|int
     * @param object $page
     */
    public function getPageId($page);
}
