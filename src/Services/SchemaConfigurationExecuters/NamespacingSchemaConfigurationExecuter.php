<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\Services\SchemaConfigurationExecuters;

use GraphQLAPI\GraphQLAPI\ModuleResolvers\SchemaConfigurationFunctionalityModuleResolver;
use GraphQLAPI\GraphQLAPI\Services\Blocks\BlockInterface;
use GraphQLAPI\GraphQLAPI\Services\Blocks\SchemaConfigNamespacingBlock;
use PoP\ComponentModel\Module as ComponentModelModule;
use PoP\ComponentModel\Environment as ComponentModelEnvironment;

class NamespacingSchemaConfigurationExecuter extends AbstractDefaultEnableDisableFunctionalitySchemaConfigurationExecuter implements PersistedQueryEndpointSchemaConfigurationExecuterServiceTagInterface, EndpointSchemaConfigurationExecuterServiceTagInterface
{
    /**
     * @var \GraphQLAPI\GraphQLAPI\Services\Blocks\SchemaConfigNamespacingBlock|null
     */
    private $schemaConfigNamespacingBlock;

    /**
     * @param \GraphQLAPI\GraphQLAPI\Services\Blocks\SchemaConfigNamespacingBlock $schemaConfigNamespacingBlock
     */
    final public function setSchemaConfigNamespacingBlock($schemaConfigNamespacingBlock): void
    {
        $this->schemaConfigNamespacingBlock = $schemaConfigNamespacingBlock;
    }
    final protected function getSchemaConfigNamespacingBlock(): SchemaConfigNamespacingBlock
    {
        /** @var SchemaConfigNamespacingBlock */
        return $this->schemaConfigNamespacingBlock = $this->schemaConfigNamespacingBlock ?? $this->instanceManager->getInstance(SchemaConfigNamespacingBlock::class);
    }

    public function getEnablingModule(): ?string
    {
        return SchemaConfigurationFunctionalityModuleResolver::SCHEMA_NAMESPACING;
    }

    protected function getBlock(): BlockInterface
    {
        return $this->getSchemaConfigNamespacingBlock();
    }

    public function getHookModuleClass(): string
    {
        return ComponentModelModule::class;
    }

    public function getHookEnvironmentClass(): string
    {
        return ComponentModelEnvironment::NAMESPACE_TYPES_AND_INTERFACES;
    }
}
