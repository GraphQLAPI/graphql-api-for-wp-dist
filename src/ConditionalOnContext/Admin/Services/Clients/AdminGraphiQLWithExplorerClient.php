<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\ConditionalOnContext\Admin\Services\Clients;

use GraphQLAPI\GraphQLAPI\Services\Helpers\EndpointHelpers;
use GraphQLByPoP\GraphQLClientsForWP\ConditionalOnContext\UseGraphiQLExplorer\Overrides\Services\Clients\GraphiQLWithExplorerClient;

class AdminGraphiQLWithExplorerClient extends GraphiQLWithExplorerClient
{
    /**
     * @var \GraphQLAPI\GraphQLAPI\Services\Helpers\EndpointHelpers|null
     */
    private $endpointHelpers;

    /**
     * @param \GraphQLAPI\GraphQLAPI\Services\Helpers\EndpointHelpers $endpointHelpers
     */
    final public function setEndpointHelpers($endpointHelpers): void
    {
        $this->endpointHelpers = $endpointHelpers;
    }
    final protected function getEndpointHelpers(): EndpointHelpers
    {
        /** @var EndpointHelpers */
        return $this->endpointHelpers = $this->endpointHelpers ?? $this->instanceManager->getInstance(EndpointHelpers::class);
    }

    /**
     * Endpoint URL or URL Path
     */
    protected function getEndpointURLOrURLPath(): ?string
    {
        return $this->getEndpointHelpers()->getAdminConfigurableSchemaGraphQLEndpoint();
    }
}
