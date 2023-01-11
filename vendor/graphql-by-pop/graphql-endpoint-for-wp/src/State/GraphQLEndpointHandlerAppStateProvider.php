<?php

declare(strict_types=1);

namespace GraphQLByPoP\GraphQLEndpointForWP\State;

use GraphQLByPoP\GraphQLEndpointForWP\EndpointHandlers\GraphQLEndpointHandler;
use PoPAPI\APIEndpoints\EndpointHandlerInterface;
use PoPAPI\APIEndpointsForWP\State\AbstractAPIEndpointHandlerAppStateProvider;
use PoPAPI\GraphQLAPI\DataStructureFormatters\GraphQLDataStructureFormatter;

class GraphQLEndpointHandlerAppStateProvider extends AbstractAPIEndpointHandlerAppStateProvider
{
    /**
     * @var \PoPAPI\GraphQLAPI\DataStructureFormatters\GraphQLDataStructureFormatter|null
     */
    private $graphQLDataStructureFormatter;
    /**
     * @var \GraphQLByPoP\GraphQLEndpointForWP\EndpointHandlers\GraphQLEndpointHandler|null
     */
    private $graphQLEndpointHandler;

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
     * @param \GraphQLByPoP\GraphQLEndpointForWP\EndpointHandlers\GraphQLEndpointHandler $graphQLEndpointHandler
     */
    final public function setGraphQLEndpointHandler($graphQLEndpointHandler): void
    {
        $this->graphQLEndpointHandler = $graphQLEndpointHandler;
    }
    final protected function getGraphQLEndpointHandler(): GraphQLEndpointHandler
    {
        /** @var GraphQLEndpointHandler */
        return $this->graphQLEndpointHandler = $this->graphQLEndpointHandler ?? $this->instanceManager->getInstance(GraphQLEndpointHandler::class);
    }

    protected function getEndpointHandler(): EndpointHandlerInterface
    {
        return $this->getGraphQLEndpointHandler();
    }

    /**
     * @param array<string,mixed> $state
     */
    public function initialize(&$state): void
    {
        parent::initialize($state);
        $state['datastructure'] = $this->getGraphQLDataStructureFormatter()->getName();
    }
}
