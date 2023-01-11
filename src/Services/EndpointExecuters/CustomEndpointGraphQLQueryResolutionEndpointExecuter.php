<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\Services\EndpointExecuters;

use GraphQLAPI\GraphQLAPI\ModuleResolvers\EndpointFunctionalityModuleResolver;
use GraphQLAPI\GraphQLAPI\Services\CustomPostTypes\GraphQLCustomEndpointCustomPostType;
use GraphQLAPI\GraphQLAPI\Services\CustomPostTypes\GraphQLEndpointCustomPostTypeInterface;
use WP_Post;

class CustomEndpointGraphQLQueryResolutionEndpointExecuter extends AbstractGraphQLQueryResolutionEndpointExecuter
{
    /**
     * @var \GraphQLAPI\GraphQLAPI\Services\CustomPostTypes\GraphQLCustomEndpointCustomPostType|null
     */
    private $graphQLCustomEndpointCustomPostType;

    /**
     * @param \GraphQLAPI\GraphQLAPI\Services\CustomPostTypes\GraphQLCustomEndpointCustomPostType $graphQLCustomEndpointCustomPostType
     */
    final public function setGraphQLCustomEndpointCustomPostType($graphQLCustomEndpointCustomPostType): void
    {
        $this->graphQLCustomEndpointCustomPostType = $graphQLCustomEndpointCustomPostType;
    }
    final protected function getGraphQLCustomEndpointCustomPostType(): GraphQLCustomEndpointCustomPostType
    {
        /** @var GraphQLCustomEndpointCustomPostType */
        return $this->graphQLCustomEndpointCustomPostType = $this->graphQLCustomEndpointCustomPostType ?? $this->instanceManager->getInstance(GraphQLCustomEndpointCustomPostType::class);
    }

    public function getEnablingModule(): ?string
    {
        return EndpointFunctionalityModuleResolver::CUSTOM_ENDPOINTS;
    }

    protected function getCustomPostType(): GraphQLEndpointCustomPostTypeInterface
    {
        return $this->getGraphQLCustomEndpointCustomPostType();
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
         * Extract the query from the BODY through standard GraphQL endpoint execution
         */
        $graphQLQueryPayload = $this->getQueryRetriever()->extractRequestedGraphQLQueryPayload();
        return [
            $graphQLQueryPayload->query,
            $graphQLQueryPayload->variables,
        ];
    }
}
