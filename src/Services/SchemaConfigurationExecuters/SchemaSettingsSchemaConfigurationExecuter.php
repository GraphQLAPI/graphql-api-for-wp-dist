<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\Services\SchemaConfigurationExecuters;

use GraphQLAPI\GraphQLAPI\ModuleResolvers\SchemaTypeModuleResolver;
use GraphQLAPI\GraphQLAPI\Services\Blocks\BlockInterface;
use GraphQLAPI\GraphQLAPI\Services\Blocks\SchemaConfigSchemaSettingsBlock;
use PoPCMSSchema\Settings\Environment as SettingsEnvironment;
use PoPCMSSchema\Settings\Module as SettingsModule;
use PoP\Root\Module\ModuleConfigurationHelpers;

class SchemaSettingsSchemaConfigurationExecuter extends AbstractSchemaAllowAccessToEntriesSchemaConfigurationExecuter implements PersistedQueryEndpointSchemaConfigurationExecuterServiceTagInterface, EndpointSchemaConfigurationExecuterServiceTagInterface
{
    /**
     * @var \GraphQLAPI\GraphQLAPI\Services\Blocks\SchemaConfigSchemaSettingsBlock|null
     */
    private $schemaConfigSchemaSettingsBlock;

    /**
     * @param \GraphQLAPI\GraphQLAPI\Services\Blocks\SchemaConfigSchemaSettingsBlock $schemaConfigSchemaSettingsBlock
     */
    final public function setSchemaConfigSchemaSettingsBlock($schemaConfigSchemaSettingsBlock): void
    {
        $this->schemaConfigSchemaSettingsBlock = $schemaConfigSchemaSettingsBlock;
    }
    final protected function getSchemaConfigSchemaSettingsBlock(): SchemaConfigSchemaSettingsBlock
    {
        /** @var SchemaConfigSchemaSettingsBlock */
        return $this->schemaConfigSchemaSettingsBlock = $this->schemaConfigSchemaSettingsBlock ?? $this->instanceManager->getInstance(SchemaConfigSchemaSettingsBlock::class);
    }

    public function getEnablingModule(): ?string
    {
        return SchemaTypeModuleResolver::SCHEMA_SETTINGS;
    }

    protected function getEntriesHookName(): string
    {
        return ModuleConfigurationHelpers::getHookName(
            SettingsModule::class,
            SettingsEnvironment::SETTINGS_ENTRIES
        );
    }

    protected function getBehaviorHookName(): string
    {
        return ModuleConfigurationHelpers::getHookName(
            SettingsModule::class,
            SettingsEnvironment::SETTINGS_BEHAVIOR
        );
    }

    protected function getBlock(): BlockInterface
    {
        return $this->getSchemaConfigSchemaSettingsBlock();
    }
}
