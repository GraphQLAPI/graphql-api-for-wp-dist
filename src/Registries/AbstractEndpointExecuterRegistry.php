<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\Registries;

use GraphQLAPI\GraphQLAPI\Services\EndpointExecuters\EndpointExecuterInterface;
use PoP\Root\Services\ServiceInterface;

abstract class AbstractEndpointExecuterRegistry implements EndpointExecuterRegistryInterface
{
    /**
     * @var EndpointExecuterInterface[]
     */
    protected $endpointExecuters = [];

    /**
     * @param \GraphQLAPI\GraphQLAPI\Services\EndpointExecuters\EndpointExecuterInterface $endpointExecuter
     */
    public function addEndpointExecuter($endpointExecuter): void
    {
        $this->endpointExecuters[] = $endpointExecuter;
    }

    /**
     * @return EndpointExecuterInterface[]
     */
    public function getEndpointExecuters(): array
    {
        return $this->endpointExecuters;
    }

    /**
     * @return EndpointExecuterInterface[]
     */
    public function getEnabledEndpointExecuters(): array
    {
        return array_values(array_filter(
            $this->getEndpointExecuters(),
            function (ServiceInterface $service) {
                return $service->isServiceEnabled();
            }
        ));
    }
}
