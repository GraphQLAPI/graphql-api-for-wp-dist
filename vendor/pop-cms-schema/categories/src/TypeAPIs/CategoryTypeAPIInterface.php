<?php

declare (strict_types=1);
namespace PoPCMSSchema\Categories\TypeAPIs;

use PoPCMSSchema\Taxonomies\TypeAPIs\TaxonomyTypeAPIInterface;
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
    /**
     * @param string|int $categoryID
     */
    public function getCategory($categoryID);
    /**
     * @param int|string $id
     */
    public function categoryExists($id) : bool;
    /**
     * @return array<string|int>|object[]
     * @param array<string,mixed> $query
     * @param array<string,mixed> $options
     */
    public function getCategories($query, $options = []) : array;
    /**
     * @param array<string,mixed> $query
     * @param array<string,mixed> $options
     */
    public function getCategoryCount($query, $options = []) : int;
    /**
     * @param array<string,mixed> $query
     * @param array<string,mixed> $options
     * @return array<string|int>|object[]
     * @param string|int|object $customPostObjectOrID
     */
    public function getCustomPostCategories($customPostObjectOrID, $query = [], $options = []) : array;
    /**
     * @param array<string,mixed> $query
     * @param array<string,mixed> $options
     * @param string|int|object $customPostObjectOrID
     */
    public function getCustomPostCategoryCount($customPostObjectOrID, $query, $options = []) : ?int;
    /**
     * @param string|int|object $catObjectOrID
     */
    public function getCategorySlug($catObjectOrID) : ?string;
    /**
     * @param string|int|object $catObjectOrID
     */
    public function getCategoryName($catObjectOrID) : ?string;
    /**
     * @param string|int|object $catObjectOrID
     * @return string|int|null
     */
    public function getCategoryParentID($catObjectOrID);
    /**
     * @return array<string|int>|null
     * @param string|int|object $catObjectOrID
     */
    public function getCategoryChildIDs($catObjectOrID) : ?array;
    /**
     * @param string|int|object $catObjectOrID
     */
    public function getCategoryURL($catObjectOrID) : ?string;
    /**
     * @param string|int|object $catObjectOrID
     */
    public function getCategoryURLPath($catObjectOrID) : ?string;
    /**
     * @param string|int|object $catObjectOrID
     */
    public function getCategoryDescription($catObjectOrID) : ?string;
    /**
     * @param string|int|object $catObjectOrID
     */
    public function getCategoryItemCount($catObjectOrID) : ?int;
}
