<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\Services\SchemaConfigurators;

use GraphQLAPI\GraphQLAPI\ModuleResolvers\EndpointFunctionalityModuleResolver;
use GraphQLAPI\GraphQLAPI\Registries\EndpointSchemaConfigurationExecuterRegistryInterface;
use GraphQLAPI\GraphQLAPI\Registries\SchemaConfigurationExecuterRegistryInterface;

class SingleEndpointSchemaConfigurator extends AbstractSchemaConfigurationEndpointSchemaConfigurator
{
    /**
     * @var \GraphQLAPI\GraphQLAPI\Registries\EndpointSchemaConfigurationExecuterRegistryInterface|null
     */
    private $endpointSchemaConfigurationExecuterRegistry;

    /**
     * @param \GraphQLAPI\GraphQLAPI\Registries\EndpointSchemaConfigurationExecuterRegistryInterface $endpointSchemaConfigurationExecuterRegistry
     */
    final public function setEndpointSchemaConfigurationExecuterRegistry($endpointSchemaConfigurationExecuterRegistry): void
    {
        $this->endpointSchemaConfigurationExecuterRegistry = $endpointSchemaConfigurationExecuterRegistry;
    }
    final protected function getEndpointSchemaConfigurationExecuterRegistry(): EndpointSchemaConfigurationExecuterRegistryInterface
    {
        /** @var EndpointSchemaConfigurationExecuterRegistryInterface */
        return $this->endpointSchemaConfigurationExecuterRegistry = $this->endpointSchemaConfigurationExecuterRegistry ?? $this->instanceManager->getInstance(EndpointSchemaConfigurationExecuterRegistryInterface::class);
    }

    /**
     * Only enable the service, if the corresponding module is also enabled
     */
    public function isServiceEnabled(): bool
    {
        return $this->getModuleRegistry()->isModuleEnabled(EndpointFunctionalityModuleResolver::SINGLE_ENDPOINT)
            && parent::isServiceEnabled();
    }

    protected function getSchemaConfigurationExecuterRegistry(): SchemaConfigurationExecuterRegistryInterface
    {
        return $this->getEndpointSchemaConfigurationExecuterRegistry();
    }
}
