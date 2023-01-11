<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\Services\SchemaConfiguratorExecuters;

use GraphQLAPI\GraphQLAPI\Constants\ModuleSettingOptions;
use GraphQLAPI\GraphQLAPI\Constants\ModuleSettingOptionValues;
use GraphQLAPI\GraphQLAPI\Facades\UserSettingsManagerFacade;
use GraphQLAPI\GraphQLAPI\ModuleResolvers\SchemaConfigurationFunctionalityModuleResolver;
use GraphQLAPI\GraphQLAPI\Registries\ModuleRegistryInterface;
use GraphQLAPI\GraphQLAPI\Services\SchemaConfigurators\SchemaConfiguratorInterface;
use GraphQLAPI\GraphQLAPI\Services\SchemaConfigurators\SingleEndpointSchemaConfigurator;
use GraphQLAPI\GraphQLAPI\Settings\UserSettingsManagerInterface;
use GraphQLByPoP\GraphQLEndpointForWP\EndpointHandlers\GraphQLEndpointHandler;

class SingleEndpointSchemaConfiguratorExecuter extends AbstractSchemaConfiguratorExecuter
{
    /**
     * @var \GraphQLAPI\GraphQLAPI\Settings\UserSettingsManagerInterface|null
     */
    private $userSettingsManager;
    /**
     * @var \GraphQLAPI\GraphQLAPI\Registries\ModuleRegistryInterface|null
     */
    private $moduleRegistry;
    /**
     * @var \GraphQLAPI\GraphQLAPI\Services\SchemaConfigurators\SingleEndpointSchemaConfigurator|null
     */
    private $singleEndpointSchemaConfigurator;
    /**
     * @var \GraphQLByPoP\GraphQLEndpointForWP\EndpointHandlers\GraphQLEndpointHandler|null
     */
    private $graphQLEndpointHandler;

    /**
     * @param \GraphQLAPI\GraphQLAPI\Settings\UserSettingsManagerInterface $userSettingsManager
     */
    public function setUserSettingsManager($userSettingsManager): void
    {
        $this->userSettingsManager = $userSettingsManager;
    }
    protected function getUserSettingsManager(): UserSettingsManagerInterface
    {
        return $this->userSettingsManager = $this->userSettingsManager ?? UserSettingsManagerFacade::getInstance();
    }
    /**
     * @param \GraphQLAPI\GraphQLAPI\Registries\ModuleRegistryInterface $moduleRegistry
     */
    final public function setModuleRegistry($moduleRegistry): void
    {
        $this->moduleRegistry = $moduleRegistry;
    }
    final protected function getModuleRegistry(): ModuleRegistryInterface
    {
        /** @var ModuleRegistryInterface */
        return $this->moduleRegistry = $this->moduleRegistry ?? $this->instanceManager->getInstance(ModuleRegistryInterface::class);
    }
    /**
     * @param \GraphQLAPI\GraphQLAPI\Services\SchemaConfigurators\SingleEndpointSchemaConfigurator $singleEndpointSchemaConfigurator
     */
    final public function setSingleEndpointSchemaConfigurator($singleEndpointSchemaConfigurator): void
    {
        $this->singleEndpointSchemaConfigurator = $singleEndpointSchemaConfigurator;
    }
    final protected function getSingleEndpointSchemaConfigurator(): SingleEndpointSchemaConfigurator
    {
        /** @var SingleEndpointSchemaConfigurator */
        return $this->singleEndpointSchemaConfigurator = $this->singleEndpointSchemaConfigurator ?? $this->instanceManager->getInstance(SingleEndpointSchemaConfigurator::class);
    }
    /**
     * @param \GraphQLByPoP\GraphQLEndpointForWP\EndpointHandlers\GraphQLEndpointHandler $graphQLEndpointHandler
     */
    final public function setGraphQLEndpointHandler($graphQLEndpointHandler): void
    {
        $this->graphQLEndpointHandler = $graphQLEndpointHandler;
    }
    final protected function getGraphQLEndpointHandler(): GraphQLEndpointHandler
    {
        /** @var GraphQLEndpointHandler */
        return $this->graphQLEndpointHandler = $this->graphQLEndpointHandler ?? $this->instanceManager->getInstance(GraphQLEndpointHandler::class);
    }

    /**
     * This is the Schema Configuration ID
     */
    protected function getCustomPostID(): ?int
    {
        // Only enable it when executing a query against the single endpoint
        if (!$this->getGraphQLEndpointHandler()->isEndpointRequested()) {
            return null;
        }
        return $this->getUserSettingSchemaConfigurationID();
    }

    /**
     * Return the stored Schema Configuration ID
     */
    protected function getUserSettingSchemaConfigurationID(): ?int
    {
        $schemaConfigurationID = $this->getUserSettingsManager()->getSetting(
            SchemaConfigurationFunctionalityModuleResolver::SCHEMA_CONFIGURATION,
            ModuleSettingOptions::VALUE_FOR_SINGLE_ENDPOINT
        );
        // `null` is stored as OPTION_VALUE_NO_VALUE_ID
        if ($schemaConfigurationID == ModuleSettingOptionValues::NO_VALUE_ID) {
            return null;
        }
        return $schemaConfigurationID;
    }

    protected function getSchemaConfigurator(): SchemaConfiguratorInterface
    {
        return $this->getSingleEndpointSchemaConfigurator();
    }
}
