<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\Services\CustomPostTypes;

use GraphQLAPI\GraphQLAPI\Module;
use GraphQLAPI\GraphQLAPI\ModuleConfiguration;
use GraphQLAPI\GraphQLAPI\ModuleResolvers\EndpointFunctionalityModuleResolver;
use GraphQLAPI\GraphQLAPI\Registries\BlockRegistryInterface;
use GraphQLAPI\GraphQLAPI\Registries\EndpointAnnotatorRegistryInterface;
use GraphQLAPI\GraphQLAPI\Registries\PersistedQueryEndpointAnnotatorRegistryInterface;
use GraphQLAPI\GraphQLAPI\Registries\PersistedQueryEndpointBlockRegistryInterface;
use GraphQLAPI\GraphQLAPI\Services\Blocks\BlockInterface;
use GraphQLAPI\GraphQLAPI\Services\Blocks\PersistedQueryEndpointOptionsBlock;
use GraphQLAPI\GraphQLAPI\Services\Taxonomies\GraphQLEndpointCategoryTaxonomy;
use GraphQLAPI\GraphQLAPI\Services\Taxonomies\TaxonomyInterface;
use PoP\Root\App;

class GraphQLPersistedQueryEndpointCustomPostType extends AbstractGraphQLEndpointCustomPostType
{
    use WithBlockRegistryCustomPostTypeTrait;

    /**
     * @var \GraphQLAPI\GraphQLAPI\Registries\PersistedQueryEndpointBlockRegistryInterface|null
     */
    private $persistedQueryEndpointBlockRegistry;
    /**
     * @var \GraphQLAPI\GraphQLAPI\Registries\PersistedQueryEndpointAnnotatorRegistryInterface|null
     */
    private $persistedQueryEndpointAnnotatorRegistry;
    /**
     * @var \GraphQLAPI\GraphQLAPI\Services\Blocks\PersistedQueryEndpointOptionsBlock|null
     */
    private $persistedQueryEndpointOptionsBlock;
    /**
     * @var \GraphQLAPI\GraphQLAPI\Services\Taxonomies\GraphQLEndpointCategoryTaxonomy|null
     */
    private $graphQLEndpointCategoryTaxonomy;

    /**
     * @param \GraphQLAPI\GraphQLAPI\Registries\PersistedQueryEndpointBlockRegistryInterface $persistedQueryEndpointBlockRegistry
     */
    final public function setPersistedQueryEndpointBlockRegistry($persistedQueryEndpointBlockRegistry): void
    {
        $this->persistedQueryEndpointBlockRegistry = $persistedQueryEndpointBlockRegistry;
    }
    final protected function getPersistedQueryEndpointBlockRegistry(): PersistedQueryEndpointBlockRegistryInterface
    {
        /** @var PersistedQueryEndpointBlockRegistryInterface */
        return $this->persistedQueryEndpointBlockRegistry = $this->persistedQueryEndpointBlockRegistry ?? $this->instanceManager->getInstance(PersistedQueryEndpointBlockRegistryInterface::class);
    }
    /**
     * @param \GraphQLAPI\GraphQLAPI\Registries\PersistedQueryEndpointAnnotatorRegistryInterface $persistedQueryEndpointAnnotatorRegistry
     */
    final public function setPersistedQueryEndpointAnnotatorRegistry($persistedQueryEndpointAnnotatorRegistry): void
    {
        $this->persistedQueryEndpointAnnotatorRegistry = $persistedQueryEndpointAnnotatorRegistry;
    }
    final protected function getPersistedQueryEndpointAnnotatorRegistry(): PersistedQueryEndpointAnnotatorRegistryInterface
    {
        /** @var PersistedQueryEndpointAnnotatorRegistryInterface */
        return $this->persistedQueryEndpointAnnotatorRegistry = $this->persistedQueryEndpointAnnotatorRegistry ?? $this->instanceManager->getInstance(PersistedQueryEndpointAnnotatorRegistryInterface::class);
    }
    /**
     * @param \GraphQLAPI\GraphQLAPI\Services\Blocks\PersistedQueryEndpointOptionsBlock $persistedQueryEndpointOptionsBlock
     */
    final public function setPersistedQueryEndpointOptionsBlock($persistedQueryEndpointOptionsBlock): void
    {
        $this->persistedQueryEndpointOptionsBlock = $persistedQueryEndpointOptionsBlock;
    }
    final protected function getPersistedQueryEndpointOptionsBlock(): PersistedQueryEndpointOptionsBlock
    {
        /** @var PersistedQueryEndpointOptionsBlock */
        return $this->persistedQueryEndpointOptionsBlock = $this->persistedQueryEndpointOptionsBlock ?? $this->instanceManager->getInstance(PersistedQueryEndpointOptionsBlock::class);
    }
    /**
     * @param \GraphQLAPI\GraphQLAPI\Services\Taxonomies\GraphQLEndpointCategoryTaxonomy $graphQLEndpointCategoryTaxonomy
     */
    final public function setGraphQLEndpointCategoryTaxonomy($graphQLEndpointCategoryTaxonomy): void
    {
        $this->graphQLEndpointCategoryTaxonomy = $graphQLEndpointCategoryTaxonomy;
    }
    final protected function getGraphQLEndpointCategoryTaxonomy(): GraphQLEndpointCategoryTaxonomy
    {
        /** @var GraphQLEndpointCategoryTaxonomy */
        return $this->graphQLEndpointCategoryTaxonomy = $this->graphQLEndpointCategoryTaxonomy ?? $this->instanceManager->getInstance(GraphQLEndpointCategoryTaxonomy::class);
    }

    /**
     * Custom Post Type name
     */
    public function getCustomPostType(): string
    {
        return 'graphql-query';
    }

    /**
     * Module that enables this PostType
     */
    public function getEnablingModule(): ?string
    {
        return EndpointFunctionalityModuleResolver::PERSISTED_QUERIES;
    }

    protected function getEndpointAnnotatorRegistry(): EndpointAnnotatorRegistryInterface
    {
        return $this->getPersistedQueryEndpointAnnotatorRegistry();
    }

    /**
     * The position on which to add the CPT on the menu.
     */
    protected function getMenuPosition(): int
    {
        return 2;
    }

    /**
     * Access endpoints under /graphql-query, or wherever it is configured to
     */
    protected function getSlugBase(): ?string
    {
        /** @var ModuleConfiguration */
        $moduleConfiguration = App::getModule(Module::class)->getConfiguration();
        return $moduleConfiguration->getPersistedQuerySlugBase();
    }

    /**
     * Custom post type name
     */
    protected function getCustomPostTypeName(): string
    {
        return \__('GraphQL persisted query endpoint', 'graphql-api');
    }

    /**
     * Custom Post Type plural name
     *
     * @param bool $titleCase Indicate if the name must be title case (for starting a sentence) or, otherwise, lowercase
     */
    protected function getCustomPostTypePluralNames($titleCase): string
    {
        return \__('GraphQL persisted queries', 'graphql-api');
    }

    /**
     * Label to show on the "execute" action in the CPT table
     */
    protected function getExecuteActionLabel(): string
    {
        return __('Execute query', 'graphql-api');
    }

    /**
     * Labels for registering the post type
     *
     * @param string $name_uc Singular name uppercase
     * @param string $names_uc Plural name uppercase
     * @param string $names_lc Plural name lowercase
     * @return array<string,string>
     */
    protected function getCustomPostTypeLabels($name_uc, $names_uc, $names_lc): array
    {
        /**
         * Because the name is too long, shorten it for the admin menu only
         */
        return array_merge(
            parent::getCustomPostTypeLabels($name_uc, $names_uc, $names_lc),
            array(
                'all_items' => \__('Persisted Queries', 'graphql-api'),
            )
        );
    }

    /**
     * The Query is publicly accessible, and the permalink must be configurable
     */
    protected function isPublic(): bool
    {
        return true;
    }

    /**
     * Taxonomies
     *
     * @return TaxonomyInterface[]
     */
    protected function getTaxonomies(): array
    {
        return [
            $this->getGraphQLEndpointCategoryTaxonomy(),
        ];
    }

    /**
     * Hierarchical
     */
    protected function isHierarchical(): bool
    {
        return true;
    }

    // /**
    //  * Show in admin bar
    //  */
    // protected function showInAdminBar(): bool
    // {
    //     return true;
    // }

    protected function getBlockRegistry(): BlockRegistryInterface
    {
        return $this->getPersistedQueryEndpointBlockRegistry();
    }

    /**
     * Indicate if the excerpt must be used as the CPT's description and rendered when rendering the post
     */
    public function usePostExcerptAsDescription(): bool
    {
        return true;
    }

    public function getEndpointOptionsBlock(): BlockInterface
    {
        return $this->getPersistedQueryEndpointOptionsBlock();
    }
}
