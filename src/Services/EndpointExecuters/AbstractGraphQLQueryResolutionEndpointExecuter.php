<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\Services\EndpointExecuters;

use GraphQLAPI\GraphQLAPI\Services\EndpointExecuters\EndpointExecuterServiceTagInterface;
use GraphQLByPoP\GraphQLRequest\Execution\QueryRetrieverInterface;
use PoPAPI\GraphQLAPI\DataStructureFormatters\GraphQLDataStructureFormatter;
use WP_Post;

abstract class AbstractGraphQLQueryResolutionEndpointExecuter extends AbstractCPTEndpointExecuter implements GraphQLQueryResolutionEndpointExecuterInterface, EndpointExecuterServiceTagInterface
{
    /**
     * @var \PoPAPI\GraphQLAPI\DataStructureFormatters\GraphQLDataStructureFormatter|null
     */
    private $graphQLDataStructureFormatter;
    /**
     * @var \GraphQLByPoP\GraphQLRequest\Execution\QueryRetrieverInterface|null
     */
    private $queryRetriever;

    /**
     * @param \PoPAPI\GraphQLAPI\DataStructureFormatters\GraphQLDataStructureFormatter $graphQLDataStructureFormatter
     */
    final public function setGraphQLDataStructureFormatter($graphQLDataStructureFormatter): void
    {
        $this->graphQLDataStructureFormatter = $graphQLDataStructureFormatter;
    }
    final protected function getGraphQLDataStructureFormatter(): GraphQLDataStructureFormatter
    {
        /** @var GraphQLDataStructureFormatter */
        return $this->graphQLDataStructureFormatter = $this->graphQLDataStructureFormatter ?? $this->instanceManager->getInstance(GraphQLDataStructureFormatter::class);
    }
    /**
     * @param \GraphQLByPoP\GraphQLRequest\Execution\QueryRetrieverInterface $queryRetriever
     */
    final public function setQueryRetriever($queryRetriever): void
    {
        $this->queryRetriever = $queryRetriever;
    }
    final protected function getQueryRetriever(): QueryRetrieverInterface
    {
        /** @var QueryRetrieverInterface */
        return $this->queryRetriever = $this->queryRetriever ?? $this->instanceManager->getInstance(QueryRetrieverInterface::class);
    }

    protected function getView(): string
    {
        return '';
    }

    public function executeEndpoint(): void
    {
        // Nothing to do, required application state already set
        // in the corresponding AppStateProvider
    }

    /**
     * Indicate if the GraphQL variables must override the URL params
     * @param \WP_Post|null $customPost
     */
    public function doURLParamsOverrideGraphQLVariables($customPost): bool
    {
        // If null, we are in the admin (eg: editing a Persisted Query),
        // and there's no need to override params
        return $customPost !== null;
    }
}
