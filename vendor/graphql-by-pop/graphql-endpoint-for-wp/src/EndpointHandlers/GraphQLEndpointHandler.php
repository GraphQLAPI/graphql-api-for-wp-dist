<?php

declare(strict_types=1);

namespace GraphQLByPoP\GraphQLEndpointForWP\EndpointHandlers;

use PoP\GraphQLAPI\Component;
use PoP\ComponentModel\Constants\Params;
use PoP\GraphQLAPI\DataStructureFormatters\GraphQLDataStructureFormatter;
use PoP\APIEndpointsForWP\EndpointHandlers\AbstractEndpointHandler;
use GraphQLByPoP\GraphQLEndpointForWP\ComponentConfiguration;
use PoP\API\Response\Schemes as APISchemes;

class GraphQLEndpointHandler extends AbstractEndpointHandler
{
    /**
     * Initialize the endpoints
     *
     * @return void
     */
    public function initialize(): void
    {
        if ($this->isGraphQLAPIEnabled()) {
            parent::initialize();
        }
    }

    /**
     * Provide the endpoint
     *
     * @var string
     */
    protected function getEndpoint(): string
    {
        return ComponentConfiguration::getGraphQLAPIEndpoint();
    }

    /**
     * Check if GrahQL has been enabled
     *
     * @return boolean
     */
    protected function isGraphQLAPIEnabled(): bool
    {
        return
            class_exists('\PoP\GraphQLAPI\Component')
            && Component::isEnabled()
            && !ComponentConfiguration::isGraphQLAPIEndpointDisabled();
    }

    /**
     * Indicate this is a GraphQL request
     *
     * @return void
     */
    protected function executeEndpoint(): void
    {
        // Set the params on the request, to emulate that they were added by the user
        $_REQUEST[Params::SCHEME] = APISchemes::API;
        // Include qualified namespace here (instead of `use`) since we do didn't know if component is installed
        $_REQUEST[Params::DATASTRUCTURE] = GraphQLDataStructureFormatter::getName();
        // Enable hooks
        \do_action('EndpointHandler:setDoingGraphQL');
    }
}
