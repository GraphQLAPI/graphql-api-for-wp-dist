<?php

declare(strict_types=1);

namespace PoPCMSSchema\CategoriesWP\TypeAPIs;

use PoPCMSSchema\Categories\TypeAPIs\CategoryListTypeAPIInterface;
use PoPCMSSchema\Categories\TypeAPIs\CategoryTypeAPIInterface;
use PoPCMSSchema\TaxonomiesWP\TypeAPIs\AbstractTaxonomyTypeAPI;
use PoP\Root\App;
use WP_Post;
use WP_Term;

use function get_categories;

/**
 * Methods to interact with the Type, to be implemented by the underlying CMS
 */
abstract class AbstractCategoryTypeAPI extends AbstractTaxonomyTypeAPI implements CategoryTypeAPIInterface, CategoryListTypeAPIInterface
{
    public const HOOK_QUERY = __CLASS__ . ':query';

    /**
     * Indicates if the passed object is of type Category
     * @param object $object
     */
    public function isInstanceOfCategoryType($object): bool
    {
        if (!$this->isInstanceOfTaxonomyTermType($object)) {
            return false;
        }
        /** @var WP_Term $object */
        return $object->taxonomy === $this->getCategoryTaxonomyName();
    }

    protected function isHierarchical(): bool
    {
        return true;
    }

    /**
     * @return string|int
     * @param object $cat
     */
    public function getCategoryID($cat)
    {
        /** @var WP_Term $cat */
        return $this->getTaxonomyTermID($cat);
    }

    /**
     * @param string|int $categoryID
     */
    public function getCategory($categoryID)
    {
        return $this->getTaxonomyTerm($categoryID, $this->getCategoryTaxonomyName());
    }

    /**
     * @param int|string $id
     */
    public function categoryExists($id): bool
    {
        return $this->getCategory($id) !== null;
    }

    abstract protected function getCategoryTaxonomyName(): string;

    /**
     * @param string|int|WP_Post $customPostObjectOrID
     * @param array<string,mixed> $query
     * @param array<string,mixed> $options
     * @return array<string|int>|object[]
     */
    public function getCustomPostCategories($customPostObjectOrID, $query = [], $options = []): array
    {
        /**
         * Allow to set the taxonomy in advance via a fieldArg.
         * Eg: { customPosts { categories(taxonomy: some_category) { id } }
         */
        if (!isset($query['taxonomy'])) {
            $query['taxonomy'] = $this->getCategoryTaxonomyName();
        }

        /** @var array<string|int>|object[] */
        return $this->getCustomPostTaxonomyTerms($customPostObjectOrID, $query, $options);
    }
    /**
     * @param array<string,mixed> $query
     * @param array<string,mixed> $options
     * @param string|int|object $customPostObjectOrID
     */
    public function getCustomPostCategoryCount($customPostObjectOrID, $query = [], $options = []): ?int
    {
        /**
         * Allow to set the taxonomy in advance via a fieldArg.
         * Eg: { customPosts { categories(taxonomy: some_category) { id } }
         */
        if (!isset($query['taxonomy'])) {
            $query['taxonomy'] = $this->getCategoryTaxonomyName();
        }

        /** @var string|int|WP_Post $customPostObjectOrID */
        return $this->getCustomPostTaxonomyTermCount($customPostObjectOrID, $query, $options);
    }
    /**
     * @param array<string,mixed> $query
     * @param array<string,mixed> $options
     */
    public function getCategoryCount($query = [], $options = []): int
    {
        /** @var int */
        return $this->getTaxonomyCount($query, $options);
    }
    /**
     * @return array<string|int>|object[]
     * @param array<string,mixed> $query
     * @param array<string,mixed> $options
     */
    public function getCategories($query, $options = []): array
    {
        $query = $this->convertCategoriesQuery($query, $options);
        return get_categories($query);
    }

    /**
     * @return array<string,mixed>
     * @param array<string,mixed> $query
     * @param array<string,mixed> $options
     */
    protected function convertTaxonomyTermsQuery($query, $options = []): array
    {
        /**
         * Allow to set the taxonomy in advance via a fieldArg.
         * Eg: { customPosts { categories(taxonomy: some_category) { id } }
         */
        if (!isset($query['taxonomy'])) {
            $query['taxonomy'] = $this->getCategoryTaxonomyName();
        }
        $query = parent::convertTaxonomyTermsQuery($query, $options);
        return App::applyFilters(
            self::HOOK_QUERY,
            $query,
            $options
        );
    }

    /**
     * @return array<string,mixed>
     * @param array<string,mixed> $query
     * @param array<string,mixed> $options
     */
    final public function convertCategoriesQuery($query, $options = []): array
    {
        return $this->convertTaxonomyTermsQuery($query, $options);
    }

    /**
     * @param string|int|object $catObjectOrID
     */
    public function getCategoryURL($catObjectOrID): ?string
    {
        /** @var string|int|WP_Term $catObjectOrID */
        return $this->getTaxonomyTermURL($catObjectOrID, $this->getCategoryTaxonomyName());
    }

    /**
     * @param string|int|object $catObjectOrID
     */
    public function getCategoryURLPath($catObjectOrID): ?string
    {
        /** @var string|int|WP_Term $catObjectOrID */
        return $this->getTaxonomyTermURLPath($catObjectOrID, $this->getCategoryTaxonomyName());
    }

    /**
     * @param string|int|object $catObjectOrID
     */
    protected function getCategoryFromObjectOrID($catObjectOrID): ?WP_Term
    {
        /** @var string|int|WP_Term $catObjectOrID */
        return $this->getTaxonomyTermFromObjectOrID($catObjectOrID, $this->getCategoryTaxonomyName());
    }

    /**
     * @param string|int|object $catObjectOrID
     */
    public function getCategorySlug($catObjectOrID): ?string
    {
        /** @var string|int|WP_Term $catObjectOrID */
        return $this->getTaxonomyTermSlug($catObjectOrID, $this->getCategoryTaxonomyName());
    }

    /**
     * @param string|int|object $catObjectOrID
     */
    public function getCategoryName($catObjectOrID): ?string
    {
        /** @var string|int|WP_Term $catObjectOrID */
        return $this->getTaxonomyTermName($catObjectOrID, $this->getCategoryTaxonomyName());
    }

    /**
     * @param string|int|object $catObjectOrID
     * @return string|int|null
     */
    public function getCategoryParentID($catObjectOrID)
    {
        /** @var string|int|WP_Term $catObjectOrID */
        return $this->getTaxonomyTermParentID($catObjectOrID, $this->getCategoryTaxonomyName());
    }

    /**
     * @return array<string|int>|null
     * @param string|int|object $catObjectOrID
     */
    public function getCategoryChildIDs($catObjectOrID): ?array
    {
        /** @var string|int|WP_Term $catObjectOrID */
        return $this->getTaxonomyTermChildIDs($this->getCategoryTaxonomyName(), $catObjectOrID);
    }

    /**
     * @param string|int|object $catObjectOrID
     */
    public function getCategoryDescription($catObjectOrID): ?string
    {
        /** @var string|int|WP_Term $catObjectOrID */
        return $this->getTaxonomyTermDescription($catObjectOrID, $this->getCategoryTaxonomyName());
    }

    /**
     * @param string|int|object $catObjectOrID
     */
    public function getCategoryItemCount($catObjectOrID): ?int
    {
        /** @var string|int|WP_Term $catObjectOrID */
        return $this->getTaxonomyTermItemCount($catObjectOrID, $this->getCategoryTaxonomyName());
    }
}
