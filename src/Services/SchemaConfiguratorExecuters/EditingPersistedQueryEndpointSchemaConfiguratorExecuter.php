<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\Services\SchemaConfiguratorExecuters;

use GraphQLAPI\GraphQLAPI\Services\Helpers\EndpointHelpers;
use GraphQLAPI\GraphQLAPI\Services\SchemaConfigurators\PersistedQueryEndpointSchemaConfigurator;
use GraphQLAPI\GraphQLAPI\Services\SchemaConfigurators\SchemaConfiguratorInterface;

class EditingPersistedQueryEndpointSchemaConfiguratorExecuter extends AbstractSchemaConfiguratorExecuter
{
    /**
     * @var \GraphQLAPI\GraphQLAPI\Services\Helpers\EndpointHelpers|null
     */
    private $endpointHelpers;
    /**
     * @var \GraphQLAPI\GraphQLAPI\Services\SchemaConfigurators\PersistedQueryEndpointSchemaConfigurator|null
     */
    private $persistedQueryEndpointSchemaConfigurator;

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
     * @param \GraphQLAPI\GraphQLAPI\Services\SchemaConfigurators\PersistedQueryEndpointSchemaConfigurator $persistedQueryEndpointSchemaConfigurator
     */
    final public function setPersistedQueryEndpointSchemaConfigurator($persistedQueryEndpointSchemaConfigurator): void
    {
        $this->persistedQueryEndpointSchemaConfigurator = $persistedQueryEndpointSchemaConfigurator;
    }
    final protected function getPersistedQueryEndpointSchemaConfigurator(): PersistedQueryEndpointSchemaConfigurator
    {
        /** @var PersistedQueryEndpointSchemaConfigurator */
        return $this->persistedQueryEndpointSchemaConfigurator = $this->persistedQueryEndpointSchemaConfigurator ?? $this->instanceManager->getInstance(PersistedQueryEndpointSchemaConfigurator::class);
    }

    /**
     * Initialize the configuration if editing a persisted query
     */
    protected function getCustomPostID(): ?int
    {
        if ($this->getEndpointHelpers()->isRequestingAdminPersistedQueryGraphQLEndpoint()) {
            return (int) $this->getEndpointHelpers()->getAdminPersistedQueryCustomPostID();
        }
        return null;
    }

    protected function getSchemaConfigurator(): SchemaConfiguratorInterface
    {
        return $this->getPersistedQueryEndpointSchemaConfigurator();
    }
}
