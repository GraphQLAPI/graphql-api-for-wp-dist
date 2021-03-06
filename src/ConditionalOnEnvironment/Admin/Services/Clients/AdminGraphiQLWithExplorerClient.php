<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\ConditionalOnEnvironment\Admin\Services\Clients;

use GraphQLAPI\GraphQLAPI\General\EndpointHelpers;
use GraphQLByPoP\GraphQLClientsForWP\ConditionalOnEnvironment\UseGraphiQLExplorer\Overrides\Services\Clients\GraphiQLWithExplorerClient;

class AdminGraphiQLWithExplorerClient extends GraphiQLWithExplorerClient
{
    /**
     * Endpoint URL
     */
    protected function getEndpointURL(): string
    {
        return EndpointHelpers::getAdminGraphQLEndpoint();
    }
}
