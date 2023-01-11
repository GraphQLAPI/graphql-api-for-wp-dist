<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\State;

use GraphQLAPI\GraphQLAPI\Services\EndpointExecuters\GraphQLQueryResolutionEndpointExecuterInterface;
use GraphQLAPI\GraphQLAPI\Services\EndpointExecuters\PersistedQueryEndpointGraphQLQueryResolutionEndpointExecuter;

class PersistedQueryEndpointGraphQLQueryResolutionEndpointExecuterAppStateProvider extends AbstractGraphQLQueryResolutionEndpointExecuterAppStateProvider
{
    /**
     * @var \GraphQLAPI\GraphQLAPI\Services\EndpointExecuters\PersistedQueryEndpointGraphQLQueryResolutionEndpointExecuter|null
     */
    private $persistedQueryEndpointGraphQLQueryResolutionEndpointExecuter;

    /**
     * @param \GraphQLAPI\GraphQLAPI\Services\EndpointExecuters\PersistedQueryEndpointGraphQLQueryResolutionEndpointExecuter $persistedQueryEndpointGraphQLQueryResolutionEndpointExecuter
     */
    final public function setPersistedQueryEndpointGraphQLQueryResolutionEndpointExecuter($persistedQueryEndpointGraphQLQueryResolutionEndpointExecuter): void
    {
        $this->persistedQueryEndpointGraphQLQueryResolutionEndpointExecuter = $persistedQueryEndpointGraphQLQueryResolutionEndpointExecuter;
    }
    final protected function getPersistedQueryEndpointGraphQLQueryResolutionEndpointExecuter(): PersistedQueryEndpointGraphQLQueryResolutionEndpointExecuter
    {
        /** @var PersistedQueryEndpointGraphQLQueryResolutionEndpointExecuter */
        return $this->persistedQueryEndpointGraphQLQueryResolutionEndpointExecuter = $this->persistedQueryEndpointGraphQLQueryResolutionEndpointExecuter ?? $this->instanceManager->getInstance(PersistedQueryEndpointGraphQLQueryResolutionEndpointExecuter::class);
    }

    protected function getGraphQLQueryResolutionEndpointExecuter(): GraphQLQueryResolutionEndpointExecuterInterface
    {
        return $this->getPersistedQueryEndpointGraphQLQueryResolutionEndpointExecuter();
    }
}
