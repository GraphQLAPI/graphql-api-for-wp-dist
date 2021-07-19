<?php

declare (strict_types=1);
namespace PoPSchema\Categories\TypeAPIs;

use PoPSchema\Taxonomies\TypeAPIs\TaxonomyTypeAPIInterface;
/**
 * Methods to interact with the Type, to be implemented by the underlying CMS
 */
interface CategoryTypeAPIInterface extends TaxonomyTypeAPIInterface
{
    /**
     * Indicates if the passed object is of type Category
     * @param object $object
     */
    public function isInstanceOfCategoryType($object) : bool;
    /**
     * @return string|int
     * @param object $cat
     */
    public function getCategoryID($cat);
    public function getCategories(array $query, array $options = []) : array;
    public function getCategoryCount(array $query, array $options = []) : int;
    /**
     * @param string|int $customPostID
     */
    public function getCustomPostCategories($customPostID, array $query = [], array $options = []) : array;
    /**
     * @param string|int $customPostID
     */
    public function getCustomPostCategoryCount($customPostID, array $query, array $options = []) : int;
    /**
     * @param string|int|object $catObjectOrID
     */
    public function getCategorySlug($catObjectOrID) : string;
    /**
     * @param string|int|object $catObjectOrID
     */
    public function getCategoryName($catObjectOrID) : string;
    /**
     * @param string|int|object $catObjectOrID
     * @return string|int|null
     */
    public function getCategoryParentID($catObjectOrID);
    /**
     * @param string|int|object $catObjectOrID
     */
    public function getCategoryURL($catObjectOrID) : string;
    /**
     * @param string|int|object $catObjectOrID
     */
    public function getCategoryDescription($catObjectOrID) : string;
    /**
     * @param string|int|object $catObjectOrID
     */
    public function getCategoryItemCount($catObjectOrID) : int;
}
