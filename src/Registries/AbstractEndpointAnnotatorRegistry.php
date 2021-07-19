<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\Registries;

use GraphQLAPI\GraphQLAPI\Services\EndpointAnnotators\EndpointAnnotatorInterface;
use PoP\Root\Services\ServiceInterface;

abstract class AbstractEndpointAnnotatorRegistry implements EndpointAnnotatorRegistryInterface
{
    /**
     * @var EndpointAnnotatorInterface[]
     */
    protected $endpointAnnotators = [];

    public function addEndpointAnnotator(EndpointAnnotatorInterface $endpointAnnotator): void
    {
        $this->endpointAnnotators[] = $endpointAnnotator;
    }
    /**
     * @return EndpointAnnotatorInterface[]
     */
    public function getEndpointAnnotators(): array
    {
        return $this->endpointAnnotators;
    }
    /**
     * @return EndpointAnnotatorInterface[]
     */
    public function getEnabledEndpointAnnotators(): array
    {
        return array_filter(
            $this->getEndpointAnnotators(),
            function (ServiceInterface $service) {
                return $service->isServiceEnabled();
            }
        );
    }
}
