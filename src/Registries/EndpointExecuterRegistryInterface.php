<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\Registries;

use GraphQLAPI\GraphQLAPI\Services\EndpointExecuters\EndpointExecuterInterface;

interface EndpointExecuterRegistryInterface
{
    /**
     * @param \GraphQLAPI\GraphQLAPI\Services\EndpointExecuters\EndpointExecuterInterface $endpointExecuter
     */
    public function addEndpointExecuter($endpointExecuter): void;
    /**
     * @return EndpointExecuterInterface[]
     */
    public function getEndpointExecuters(): array;
    /**
     * @return EndpointExecuterInterface[]
     */
    public function getEnabledEndpointExecuters(): array;
}
