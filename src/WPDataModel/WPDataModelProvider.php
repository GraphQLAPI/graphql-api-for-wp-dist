<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\WPDataModel;

use GraphQLAPI\GraphQLAPI\Constants\HookNames;
use GraphQLAPI\GraphQLAPI\Registries\CustomPostTypeRegistryInterface;
use GraphQLAPI\GraphQLAPI\Registries\TaxonomyRegistryInterface;
use GraphQLAPI\GraphQLAPI\Services\CustomPostTypes\CustomPostTypeInterface;
use GraphQLAPI\GraphQLAPI\Services\Taxonomies\TaxonomyInterface;
use PoPCMSSchema\CustomPosts\Module as CustomPostsModule;
use PoPCMSSchema\CustomPosts\ModuleConfiguration as CustomPostsModuleConfiguration;
use PoP\ComponentModel\App;
use PoP\Root\Services\BasicServiceTrait;
use WP_Taxonomy;

class WPDataModelProvider implements WPDataModelProviderInterface
{
    use BasicServiceTrait;

    /** @var array<string,WP_Taxonomy>|null */
    protected $hierarchicalQueryableCustomPostsAssociatedTaxonomies;

    /** @var array<string,WP_Taxonomy>|null */
    protected $nonHierarchicalQueryableCustomPostsAssociatedTaxonomies;

    /**
     * @var \GraphQLAPI\GraphQLAPI\Registries\CustomPostTypeRegistryInterface|null
     */
    private $customPostTypeRegistry;
    /**
     * @var \GraphQLAPI\GraphQLAPI\Registries\TaxonomyRegistryInterface|null
     */
    private $taxonomyRegistry;

    /**
     * @param \GraphQLAPI\GraphQLAPI\Registries\CustomPostTypeRegistryInterface $customPostTypeRegistry
     */
    final public function setCustomPostTypeRegistry($customPostTypeRegistry): void
    {
        $this->customPostTypeRegistry = $customPostTypeRegistry;
    }
    final protected function getCustomPostTypeRegistry(): CustomPostTypeRegistryInterface
    {
        /** @var CustomPostTypeRegistryInterface */
        return $this->customPostTypeRegistry = $this->customPostTypeRegistry ?? $this->instanceManager->getInstance(CustomPostTypeRegistryInterface::class);
    }
    /**
     * @param \GraphQLAPI\GraphQLAPI\Registries\TaxonomyRegistryInterface $taxonomyRegistry
     */
    final public function setTaxonomyRegistry($taxonomyRegistry): void
    {
        $this->taxonomyRegistry = $taxonomyRegistry;
    }
    final protected function getTaxonomyRegistry(): TaxonomyRegistryInterface
    {
        /** @var TaxonomyRegistryInterface */
        return $this->taxonomyRegistry = $this->taxonomyRegistry ?? $this->instanceManager->getInstance(TaxonomyRegistryInterface::class);
    }

    /**
     * @return string[]
     */
    public function getFilteredNonGraphQLAPIPluginCustomPostTypes(): array
    {
        // Get the list of custom post types from the system
        $possibleCustomPostTypes = \get_post_types();
        /**
         * Not all custom post types make sense or are allowed.
         * Remove the ones that do not
         */
        $pluginCustomPostTypes = array_map(
            function (CustomPostTypeInterface $customPostType) {
                return $customPostType->getCustomPostType();
            },
            $this->getCustomPostTypeRegistry()->getCustomPostTypes()
        );
        $rejectedQueryableCustomPostTypes = \apply_filters(
            HookNames::HOOK_REJECTED_QUERYABLE_CUSTOMPOST_TYPES,
            array_merge(
                /**
                 * Post Types from GraphQL API are just for configuration
                 * and contain private data
                 */
                $pluginCustomPostTypes,
                /**
                 * WordPress internal CPTs.
                 *
                 * Watch out: Attachment has post_status "inherit",
                 * which is by default not included in the "status"
                 * filter, so the query must make it explicit:
                 * `filter: { status: ["inherit"] }`.
                 *
                 * Similar with Revision and status "auto-draft"
                 */
                $this->removeWordPressInternalCustomPostTypes()
                    ? $this->getWordPressInternalCustomPostTypes()
                    : []
            )
        );
        $possibleCustomPostTypes = array_values(array_diff(
            $possibleCustomPostTypes,
            $rejectedQueryableCustomPostTypes
        ));
        // Allow plugins to further remove unwanted custom post types
        $possibleCustomPostTypes = \apply_filters(
            HookNames::HOOK_QUERYABLE_CUSTOMPOST_TYPES,
            $possibleCustomPostTypes
        );
        sort($possibleCustomPostTypes);

        return $possibleCustomPostTypes;
    }

    protected function removeWordPressInternalCustomPostTypes(): bool
    {
        return false;
    }

    /**
     * @return string[]
     */
    protected function getWordPressInternalCustomPostTypes(): array
    {
        return [
            'attachment',
            'custom_css',
            'customize_changeset',
            'nav_menu_item',
            'oembed_cache',
            'revision',
            'user_request',
            'wp_area',
            'wp_block',
            'wp_global_styles',
            'wp_navigation',
            'wp_template_part',
            'wp_template',
        ];
    }
    /**
     * @return string[]
     */
    public function getFilteredNonGraphQLAPIPluginTagTaxonomies(): array
    {
        // Get the list of tag taxonomies from the system
        $queryableTagTaxonomyNameObjects = $this->getQueryableCustomPostsAssociatedTaxonomies(false);
        /**
         * Possibly not all tag taxonomies must be allowed.
         * Remove the ones that do not
         */
        $pluginTagTaxonomies = array_map(
            function (TaxonomyInterface $taxonomy) {
                return $taxonomy->getTaxonomy();
            },
            $this->getTaxonomyRegistry()->getTaxonomies(false)
        );
        $rejectedQueryableTagTaxonomies = \apply_filters(
            HookNames::HOOK_REJECTED_QUERYABLE_TAG_TAXONOMIES,
            []
        );
        $possibleTagTaxonomies = array_values(array_diff(
            array_keys($queryableTagTaxonomyNameObjects),
            $pluginTagTaxonomies,
            $rejectedQueryableTagTaxonomies
        ));
        // Allow plugins to further remove unwanted custom post types
        $possibleTagTaxonomies = \apply_filters(
            HookNames::HOOK_QUERYABLE_TAG_TAXONOMIES,
            $possibleTagTaxonomies
        );
        sort($possibleTagTaxonomies);
        return $possibleTagTaxonomies;
    }

    /**
     * @return string[]
     */
    public function getFilteredNonGraphQLAPIPluginCategoryTaxonomies(): array
    {
        // Get the list of category taxonomies from the system
        $queryableCategoryTaxonomyNameObjects = $this->getQueryableCustomPostsAssociatedTaxonomies(true);
        /**
         * Possibly not all category taxonomies must be allowed.
         * Remove the ones that do not
         */
        $pluginCategoryTaxonomies = array_map(
            function (TaxonomyInterface $taxonomy) {
                return $taxonomy->getTaxonomy();
            },
            $this->getTaxonomyRegistry()->getTaxonomies(true)
        );
        $rejectedQueryableCategoryTaxonomies = \apply_filters(
            HookNames::HOOK_REJECTED_QUERYABLE_CATEGORY_TAXONOMIES,
            []
        );
        $possibleCategoryTaxonomies = array_values(array_diff(
            array_keys($queryableCategoryTaxonomyNameObjects),
            $pluginCategoryTaxonomies,
            $rejectedQueryableCategoryTaxonomies
        ));
        // Allow plugins to further remove unwanted custom post types
        $possibleCategoryTaxonomies = \apply_filters(
            HookNames::HOOK_QUERYABLE_CATEGORY_TAXONOMIES,
            $possibleCategoryTaxonomies
        );
        sort($possibleCategoryTaxonomies);
        return $possibleCategoryTaxonomies;
    }

    /**
     * Retrieve the taxonomies which are associated to custom posts
     * which have been enabled as queryable.
     *
     * Please notice all entries in "object_type" must be in the whitelist.
     *
     * @return array<string,WP_Taxonomy> Taxonomy name => taxonomy object
     * @param bool $isHierarchical
     */
    public function getQueryableCustomPostsAssociatedTaxonomies($isHierarchical): array
    {
        if ($isHierarchical && $this->hierarchicalQueryableCustomPostsAssociatedTaxonomies !== null) {
            return $this->hierarchicalQueryableCustomPostsAssociatedTaxonomies;
        }
        if (!$isHierarchical && $this->nonHierarchicalQueryableCustomPostsAssociatedTaxonomies !== null) {
            return $this->nonHierarchicalQueryableCustomPostsAssociatedTaxonomies;
        }

        /** @var CustomPostsModuleConfiguration */
        $moduleConfiguration = App::getModule(CustomPostsModule::class)->getConfiguration();
        $queryableCustomPostTypes = $moduleConfiguration->getQueryableCustomPostTypes();

        /** @var WP_Taxonomy[] */
        $possibleTaxonomyObjects = \get_taxonomies(
            [
                'hierarchical' => $isHierarchical,
            ],
            'objects'
        );

        $possibleTaxonomyObjects = array_filter(
            $possibleTaxonomyObjects,
            function (WP_Taxonomy $taxonomy) use ($queryableCustomPostTypes) {
                return array_diff(
                    $taxonomy->object_type,
                    $queryableCustomPostTypes
                ) === [];
            }
        );

        $possibleTaxonomyNameObjects = [];
        foreach ($possibleTaxonomyObjects as $taxonomyObject) {
            $possibleTaxonomyNameObjects[$taxonomyObject->name] = $taxonomyObject;
        }

        if ($isHierarchical) {
            $this->hierarchicalQueryableCustomPostsAssociatedTaxonomies = $possibleTaxonomyNameObjects;
        } else {
            $this->nonHierarchicalQueryableCustomPostsAssociatedTaxonomies = $possibleTaxonomyNameObjects;
        }

        return $possibleTaxonomyNameObjects;
    }
}
