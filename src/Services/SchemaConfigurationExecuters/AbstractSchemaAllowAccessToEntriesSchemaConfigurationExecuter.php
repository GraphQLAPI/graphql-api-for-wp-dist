<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\Services\SchemaConfigurationExecuters;

use GraphQLAPI\GraphQLAPI\Constants\BlockAttributeNames;
use GraphQLAPI\GraphQLAPI\PluginEnvironment;
use PoPSchema\SchemaCommons\Constants\Behaviors;

abstract class AbstractSchemaAllowAccessToEntriesSchemaConfigurationExecuter extends AbstractCustomizableConfigurationSchemaConfigurationExecuter implements PersistedQueryEndpointSchemaConfigurationExecuterServiceTagInterface, EndpointSchemaConfigurationExecuterServiceTagInterface
{
    /**
     * @param int $schemaConfigurationID
     */
    protected function doExecuteSchemaConfiguration($schemaConfigurationID): void
    {
        $schemaConfigBlockDataItem = $this->getSchemaConfigBlockDataItem($schemaConfigurationID);
        if ($schemaConfigBlockDataItem === null) {
            return;
        }
        $entries = $schemaConfigBlockDataItem['attrs'][BlockAttributeNames::ENTRIES] ?? [];
        /**
         * Define the settings value through a hook.
         * Execute last so it overrides the default settings
         */
        $hookName = $this->getEntriesHookName();
        \add_filter(
            $hookName,
            function () use ($entries) {
                return $entries;
            },
            PHP_INT_MAX
        );
        $behavior = $schemaConfigBlockDataItem['attrs'][BlockAttributeNames::BEHAVIOR] ?? $this->getDefaultBehavior();
        /**
         * Define the settings value through a hook.
         * Execute last so it overrides the default settings
         */
        $hookName = $this->getBehaviorHookName();
        \add_filter(
            $hookName,
            function () use ($behavior) {
                return $behavior;
            },
            PHP_INT_MAX
        );
    }

    abstract protected function getEntriesHookName(): string;
    abstract protected function getBehaviorHookName(): string;

    protected function getDefaultBehavior(): string
    {
        return PluginEnvironment::areUnsafeDefaultsEnabled()
            ? Behaviors::DENY
            : Behaviors::ALLOW;
    }
}
