<?php

declare (strict_types=1);
namespace GraphQLByPoP\GraphQLRequest\State;

use GraphQLByPoP\GraphQLRequest\StaticHelpers\GraphQLQueryPayloadRetriever;
use PoPAPI\API\Response\Schemes as APISchemes;
use PoPAPI\API\Routing\RequestNature;
use PoPAPI\GraphQLAPI\DataStructureFormatters\GraphQLDataStructureFormatter;
use PoP\Root\State\AbstractAppStateProvider;
class AppStateProvider extends AbstractAppStateProvider
{
    /**
     * @var \PoPAPI\GraphQLAPI\DataStructureFormatters\GraphQLDataStructureFormatter|null
     */
    private $graphQLDataStructureFormatter;
    /**
     * @param \PoPAPI\GraphQLAPI\DataStructureFormatters\GraphQLDataStructureFormatter $graphQLDataStructureFormatter
     */
    public final function setGraphQLDataStructureFormatter($graphQLDataStructureFormatter) : void
    {
        $this->graphQLDataStructureFormatter = $graphQLDataStructureFormatter;
    }
    protected final function getGraphQLDataStructureFormatter() : GraphQLDataStructureFormatter
    {
        /** @var GraphQLDataStructureFormatter */
        return $this->graphQLDataStructureFormatter = $this->graphQLDataStructureFormatter ?? $this->instanceManager->getInstance(GraphQLDataStructureFormatter::class);
    }
    /**
     * @param array<string,mixed> $state
     */
    public function consolidate(&$state) : void
    {
        if (!($state['scheme'] === APISchemes::API && $state['datastructure'] === $this->getGraphQLDataStructureFormatter()->getName())) {
            return;
        }
        // Single endpoint, starting at the Root object
        $state['nature'] = RequestNature::QUERY_ROOT;
        // Get the GraphQL payload from POST
        $payload = GraphQLQueryPayloadRetriever::getGraphQLQueryPayload();
        if ($payload === null) {
            return;
        }
        // Set state with the GraphQL query from the body
        $state['query'] = $payload['query'] ?? null;
        $state['variables'] = $payload['variables'] ?? [];
        $state['operation-name'] = $payload['operationName'] ?? null;
    }
}
