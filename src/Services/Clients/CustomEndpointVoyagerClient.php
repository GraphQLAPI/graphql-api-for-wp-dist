<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\Services\Clients;

use GraphQLAPI\GraphQLAPI\Services\CustomPostTypes\GraphQLCustomEndpointCustomPostType;
use GraphQLByPoP\GraphQLClientsForWP\Clients\VoyagerClient;
use PoP\ComponentModel\HelperServices\RequestHelperServiceInterface;

class CustomEndpointVoyagerClient extends VoyagerClient
{
    use CustomEndpointClientTrait;

    /**
     * @var \GraphQLAPI\GraphQLAPI\Services\CustomPostTypes\GraphQLCustomEndpointCustomPostType|null
     */
    private $graphQLCustomEndpointCustomPostType;
    /**
     * @var \PoP\ComponentModel\HelperServices\RequestHelperServiceInterface|null
     */
    private $requestHelperService;

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
    /**
     * @param \PoP\ComponentModel\HelperServices\RequestHelperServiceInterface $requestHelperService
     */
    final public function setRequestHelperService($requestHelperService): void
    {
        $this->requestHelperService = $requestHelperService;
    }
    final protected function getRequestHelperService(): RequestHelperServiceInterface
    {
        /** @var RequestHelperServiceInterface */
        return $this->requestHelperService = $this->requestHelperService ?? $this->instanceManager->getInstance(RequestHelperServiceInterface::class);
    }
}
