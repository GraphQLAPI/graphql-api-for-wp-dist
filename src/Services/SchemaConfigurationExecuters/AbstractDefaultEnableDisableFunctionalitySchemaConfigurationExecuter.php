<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\Services\SchemaConfigurationExecuters;

use GraphQLAPI\GraphQLAPI\Constants\BlockAttributeNames;
use GraphQLAPI\GraphQLAPI\Constants\BlockAttributeValues;
use PoP\Root\Module\ModuleConfigurationHelpers;

abstract class AbstractDefaultEnableDisableFunctionalitySchemaConfigurationExecuter extends AbstractSchemaConfigurationExecuter implements PersistedQueryEndpointSchemaConfigurationExecuterServiceTagInterface, EndpointSchemaConfigurationExecuterServiceTagInterface
{
    public function getSchemaConfigBlockAttributeName(): string
    {
        return BlockAttributeNames::ENABLED_CONST;
    }

    abstract public function getHookModuleClass(): string;

    abstract public function getHookEnvironmentClass(): string;

    /**
     * @param int $schemaConfigurationID
     */
    public function executeSchemaConfiguration($schemaConfigurationID): void
    {
        $schemaConfigBlockDataItem = $this->getSchemaConfigBlockDataItem($schemaConfigurationID);
        if ($schemaConfigBlockDataItem === null) {
            return;
        }
        /**
         * Default value (if not defined in DB): `default`. Then do nothing
         */
        $enableFunctionality = $schemaConfigBlockDataItem['attrs'][$this->getSchemaConfigBlockAttributeName()] ?? null;
        /**
         * Only execute if it has any selectable value (no null, no invented).
         * If "default", then the general settings will already take effect,
         * so do nothing.
         */
        if (
            !in_array($enableFunctionality, [
                BlockAttributeValues::ENABLED,
                BlockAttributeValues::DISABLED,
            ])
        ) {
            return;
        }
        /**
         * Define the settings value through a hook.
         * Execute last so it overrides the default settings
         */
        $hookName = ModuleConfigurationHelpers::getHookName($this->getHookModuleClass(), $this->getHookEnvironmentClass());
        \add_filter(
            $hookName,
            function () use ($enableFunctionality) {
                return $enableFunctionality === BlockAttributeValues::ENABLED;
            },
            PHP_INT_MAX
        );
    }
}
