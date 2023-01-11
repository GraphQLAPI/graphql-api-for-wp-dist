<?php

declare(strict_types=1);

namespace PoPCMSSchema\CategoriesWP\TypeAPIs;

use PoPCMSSchema\CategoriesWP\TypeAPIs\AbstractCategoryTypeAPI;
use PoPCMSSchema\Categories\Module;
use PoPCMSSchema\Categories\ModuleConfiguration;
use PoPCMSSchema\Categories\TypeAPIs\QueryableCategoryTypeAPIInterface;
use PoP\ComponentModel\App;
use WP_Term;

class QueryableCategoryTypeAPI extends AbstractCategoryTypeAPI implements QueryableCategoryTypeAPIInterface
{
    public const HOOK_QUERY = __CLASS__ . ':query';

    /**
     * There will be more than 1 taxonomies, but this value
     * will get replaced in the query below
     */
    protected function getCategoryTaxonomyName(): string
    {
        return '';
    }

    /**
     * @param string|int $categoryID
     */
    public function getCategory($categoryID)
    {
        $category = parent::getCategory($categoryID);
        if ($category === null) {
            return null;
        }
        /** @var WP_Term $category */
        if (!$this->isQueryableCategoryTaxonomy($category)) {
            return null;
        }
        return $category;
    }

    /**
     * @param \WP_Term $taxonomyTerm
     */
    protected function isQueryableCategoryTaxonomy($taxonomyTerm): bool
    {
        /** @var ModuleConfiguration */
        $moduleConfiguration = App::getModule(Module::class)->getConfiguration();
        return in_array($taxonomyTerm->taxonomy, $moduleConfiguration->getQueryableCategoryTaxonomies());
    }

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
        return $this->isQueryableCategoryTaxonomy($object);
    }

    /**
     * @param string|int|\WP_Term $taxonomyTermObjectOrID
     * @param string $taxonomy
     */
    protected function getTaxonomyTermFromObjectOrID($taxonomyTermObjectOrID, $taxonomy = ''): ?WP_Term
    {
        $taxonomyTerm = parent::getTaxonomyTermFromObjectOrID($taxonomyTermObjectOrID, $taxonomy);
        if ($taxonomyTerm === null) {
            return $taxonomyTerm;
        }
        /** @var WP_Term $taxonomyTerm */
        return $this->isQueryableCategoryTaxonomy($taxonomyTerm) ? $taxonomyTerm : null;
    }

    /**
     * Replace the single taxonomy with the list of them.
     *
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
            /** @var ModuleConfiguration */
            $moduleConfiguration = App::getModule(Module::class)->getConfiguration();
            $query['taxonomy'] = $moduleConfiguration->getQueryableCategoryTaxonomies();
        }
        $query = parent::convertTaxonomyTermsQuery($query, $options);
        return App::applyFilters(
            self::HOOK_QUERY,
            $query,
            $options
        );
    }
}
