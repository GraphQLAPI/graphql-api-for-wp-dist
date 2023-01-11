<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\Registries;

use GraphQLAPI\GraphQLAPI\Services\EndpointAnnotators\EndpointAnnotatorInterface;

interface EndpointAnnotatorRegistryInterface
{
    /**
     * @param \GraphQLAPI\GraphQLAPI\Services\EndpointAnnotators\EndpointAnnotatorInterface $endpointAnnotator
     */
    public function addEndpointAnnotator($endpointAnnotator): void;
    /**
     * @return EndpointAnnotatorInterface[]
     */
    public function getEndpointAnnotators(): array;
    /**
     * @return EndpointAnnotatorInterface[]
     */
    public function getEnabledEndpointAnnotators(): array;
}
