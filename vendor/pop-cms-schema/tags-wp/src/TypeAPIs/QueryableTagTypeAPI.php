<?php

declare(strict_types=1);

namespace PoPCMSSchema\TagsWP\TypeAPIs;

use PoPCMSSchema\TagsWP\TypeAPIs\AbstractTagTypeAPI;
use PoPCMSSchema\Tags\Module;
use PoPCMSSchema\Tags\ModuleConfiguration;
use PoPCMSSchema\Tags\TypeAPIs\QueryableTagTypeAPIInterface;
use PoP\ComponentModel\App;
use WP_Term;

class QueryableTagTypeAPI extends AbstractTagTypeAPI implements QueryableTagTypeAPIInterface
{
    public const HOOK_QUERY = __CLASS__ . ':query';

    /**
     * There will be more than 1 taxonomies, but this value
     * will get replaced in the query below
     */
    protected function getTagTaxonomyName(): string
    {
        return '';
    }

    /**
     * @param string|int $tagID
     */
    public function getTag($tagID)
    {
        $tag = parent::getTag($tagID);
        if ($tag === null) {
            return null;
        }
        /** @var WP_Term $tag */
        if (!$this->isQueryableTagTaxonomy($tag)) {
            return null;
        }
        return $tag;
    }

    /**
     * @param \WP_Term $taxonomyTerm
     */
    protected function isQueryableTagTaxonomy($taxonomyTerm): bool
    {
        /** @var ModuleConfiguration */
        $moduleConfiguration = App::getModule(Module::class)->getConfiguration();
        return in_array($taxonomyTerm->taxonomy, $moduleConfiguration->getQueryableTagTaxonomies());
    }

    /**
     * Indicates if the passed object is of type Tag
     * @param object $object
     */
    public function isInstanceOfTagType($object): bool
    {
        if (!$this->isInstanceOfTaxonomyTermType($object)) {
            return false;
        }
        /** @var WP_Term $object */
        return $this->isQueryableTagTaxonomy($object);
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
        return $this->isQueryableTagTaxonomy($taxonomyTerm) ? $taxonomyTerm : null;
    }

    /**
     * @param string $tagName
     */
    public function getTagByName($tagName)
    {
        $tag = parent::getTagByName($tagName);
        if ($tag === null) {
            return null;
        }
        /** @var WP_Term $tag */
        return $this->isQueryableTagTaxonomy($tag) ? $tag : null;
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
         * Eg: { customPosts { tags(taxonomy: nav_menu) { id } }
         */
        if (!isset($query['taxonomy'])) {
            /** @var ModuleConfiguration */
            $moduleConfiguration = App::getModule(Module::class)->getConfiguration();
            $query['taxonomy'] = $moduleConfiguration->getQueryableTagTaxonomies();
        }
        $query = parent::convertTaxonomyTermsQuery($query, $options);
        return App::applyFilters(
            self::HOOK_QUERY,
            $query,
            $options
        );
    }
}
