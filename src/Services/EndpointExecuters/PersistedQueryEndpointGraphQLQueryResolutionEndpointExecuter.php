<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\Services\EndpointExecuters;

use GraphQLAPI\GraphQLAPI\ModuleResolvers\EndpointFunctionalityModuleResolver;
use GraphQLAPI\GraphQLAPI\Services\Blocks\PersistedQueryEndpointOptionsBlock;
use GraphQLAPI\GraphQLAPI\Services\CustomPostTypes\GraphQLEndpointCustomPostTypeInterface;
use GraphQLAPI\GraphQLAPI\Services\CustomPostTypes\GraphQLPersistedQueryEndpointCustomPostType;
use GraphQLAPI\GraphQLAPI\Services\Helpers\GraphQLQueryPostTypeHelpers;
use WP_Post;

class PersistedQueryEndpointGraphQLQueryResolutionEndpointExecuter extends AbstractGraphQLQueryResolutionEndpointExecuter
{
    /**
     * @var \GraphQLAPI\GraphQLAPI\Services\CustomPostTypes\GraphQLPersistedQueryEndpointCustomPostType|null
     */
    private $graphQLPersistedQueryEndpointCustomPostType;
    /**
     * @var \GraphQLAPI\GraphQLAPI\Services\Helpers\GraphQLQueryPostTypeHelpers|null
     */
    private $graphQLQueryPostTypeHelpers;

    /**
     * @param \GraphQLAPI\GraphQLAPI\Services\CustomPostTypes\GraphQLPersistedQueryEndpointCustomPostType $graphQLPersistedQueryEndpointCustomPostType
     */
    final public function setGraphQLPersistedQueryEndpointCustomPostType($graphQLPersistedQueryEndpointCustomPostType): void
    {
        $this->graphQLPersistedQueryEndpointCustomPostType = $graphQLPersistedQueryEndpointCustomPostType;
    }
    final protected function getGraphQLPersistedQueryEndpointCustomPostType(): GraphQLPersistedQueryEndpointCustomPostType
    {
        /** @var GraphQLPersistedQueryEndpointCustomPostType */
        return $this->graphQLPersistedQueryEndpointCustomPostType = $this->graphQLPersistedQueryEndpointCustomPostType ?? $this->instanceManager->getInstance(GraphQLPersistedQueryEndpointCustomPostType::class);
    }
    /**
     * @param \GraphQLAPI\GraphQLAPI\Services\Helpers\GraphQLQueryPostTypeHelpers $graphQLQueryPostTypeHelpers
     */
    final public function setGraphQLQueryPostTypeHelpers($graphQLQueryPostTypeHelpers): void
    {
        $this->graphQLQueryPostTypeHelpers = $graphQLQueryPostTypeHelpers;
    }
    final protected function getGraphQLQueryPostTypeHelpers(): GraphQLQueryPostTypeHelpers
    {
        /** @var GraphQLQueryPostTypeHelpers */
        return $this->graphQLQueryPostTypeHelpers = $this->graphQLQueryPostTypeHelpers ?? $this->instanceManager->getInstance(GraphQLQueryPostTypeHelpers::class);
    }

    public function getEnablingModule(): ?string
    {
        return EndpointFunctionalityModuleResolver::PERSISTED_QUERIES;
    }

    protected function getCustomPostType(): GraphQLEndpointCustomPostTypeInterface
    {
        return $this->getGraphQLPersistedQueryEndpointCustomPostType();
    }

    /**
     * Provide the query to execute and its variables
     *
     * @return array{0:?string,1:?array<string,mixed>} Array of 2 elements: [query, variables]
     * @param \WP_Post|null $graphQLQueryPost
     */
    public function getGraphQLQueryAndVariables($graphQLQueryPost): array
    {
        /**
         * Extract the query from the post (or from its parents), and set it in the application state
         */
        return $this->getGraphQLQueryPostTypeHelpers()->getGraphQLQueryPostAttributes($graphQLQueryPost, true);
    }

    /**
     * Indicate if the GraphQL variables must override the URL params
     * @param \WP_Post|null $customPost
     */
    public function doURLParamsOverrideGraphQLVariables($customPost): bool
    {
        if ($customPost === null) {
            return parent::doURLParamsOverrideGraphQLVariables($customPost);
        }
        $default = true;
        $optionsBlockDataItem = $this->getCustomPostType()->getOptionsBlockDataItem($customPost);
        if ($optionsBlockDataItem === null) {
            return $default;
        }

        // `true` is the default option in Gutenberg, so it's not saved to the DB!
        return $optionsBlockDataItem['attrs'][PersistedQueryEndpointOptionsBlock::ATTRIBUTE_NAME_ACCEPT_VARIABLES_AS_URL_PARAMS] ?? $default;
    }
}
