<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\State;

use GraphQLAPI\GraphQLAPI\Services\EndpointExecuters\GraphQLQueryResolutionEndpointExecuterInterface;
use GraphQLAPI\GraphQLAPI\Services\EndpointExecuters\CustomEndpointGraphQLQueryResolutionEndpointExecuter;

class CustomEndpointGraphQLQueryResolutionEndpointExecuterAppStateProvider extends AbstractGraphQLQueryResolutionEndpointExecuterAppStateProvider
{
    /**
     * @var \GraphQLAPI\GraphQLAPI\Services\EndpointExecuters\CustomEndpointGraphQLQueryResolutionEndpointExecuter|null
     */
    private $customEndpointGraphQLQueryResolutionEndpointExecuter;

    /**
     * @param \GraphQLAPI\GraphQLAPI\Services\EndpointExecuters\CustomEndpointGraphQLQueryResolutionEndpointExecuter $customEndpointGraphQLQueryResolutionEndpointExecuter
     */
    final public function setCustomEndpointGraphQLQueryResolutionEndpointExecuter($customEndpointGraphQLQueryResolutionEndpointExecuter): void
    {
        $this->customEndpointGraphQLQueryResolutionEndpointExecuter = $customEndpointGraphQLQueryResolutionEndpointExecuter;
    }
    final protected function getCustomEndpointGraphQLQueryResolutionEndpointExecuter(): CustomEndpointGraphQLQueryResolutionEndpointExecuter
    {
        /** @var CustomEndpointGraphQLQueryResolutionEndpointExecuter */
        return $this->customEndpointGraphQLQueryResolutionEndpointExecuter = $this->customEndpointGraphQLQueryResolutionEndpointExecuter ?? $this->instanceManager->getInstance(CustomEndpointGraphQLQueryResolutionEndpointExecuter::class);
    }

    protected function getGraphQLQueryResolutionEndpointExecuter(): GraphQLQueryResolutionEndpointExecuterInterface
    {
        return $this->getCustomEndpointGraphQLQueryResolutionEndpointExecuter();
    }
}
