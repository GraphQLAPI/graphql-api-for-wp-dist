<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\SchemaConfigurators;

use WP_Post;
use PoP\AccessControl\Schema\SchemaModes;
use GraphQLAPI\GraphQLAPI\General\BlockHelpers;
use PoP\Engine\Environment as EngineEnvironment;
use GraphQLAPI\GraphQLAPI\Facades\ModuleRegistryFacade;
use GraphQLAPI\GraphQLAPI\Blocks\SchemaConfigOptionsBlock;
use GraphQLAPI\GraphQLAPI\Blocks\SchemaConfigurationBlock;
use GraphQLAPI\GraphQLAPI\Facades\UserSettingsManagerFacade;
use GraphQLByPoP\GraphQLServer\Configuration\MutationSchemes;
use PoP\AccessControl\Environment as AccessControlEnvironment;
use PoP\ComponentModel\Facades\Instances\InstanceManagerFacade;
use PoP\ComponentModel\Environment as ComponentModelEnvironment;
use GraphQLAPI\GraphQLAPI\Blocks\SchemaConfigAccessControlListBlock;
use PoP\Engine\ComponentConfiguration as EngineComponentConfiguration;
use GraphQLAPI\GraphQLAPI\Blocks\SchemaConfigFieldDeprecationListBlock;
use GraphQLByPoP\GraphQLServer\Environment as GraphQLServerEnvironment;
use PoP\ComponentModel\ComponentConfiguration\ComponentConfigurationHelpers;
use GraphQLAPI\GraphQLAPI\ModuleResolvers\EndpointFunctionalityModuleResolver;
use GraphQLAPI\GraphQLAPI\ModuleResolvers\VersioningFunctionalityModuleResolver;
use GraphQLAPI\GraphQLAPI\ModuleResolvers\OperationalFunctionalityModuleResolver;
use GraphQLAPI\GraphQLAPI\ModuleResolvers\AccessControlFunctionalityModuleResolver;
use GraphQLAPI\GraphQLAPI\SchemaConfigurators\AccessControlGraphQLQueryConfigurator;
use PoP\AccessControl\ComponentConfiguration as AccessControlComponentConfiguration;
use PoP\ComponentModel\ComponentConfiguration as ComponentModelComponentConfiguration;
use GraphQLAPI\GraphQLAPI\SchemaConfigurators\FieldDeprecationGraphQLQueryConfigurator;
use GraphQLAPI\GraphQLAPI\ModuleResolvers\SchemaConfigurationFunctionalityModuleResolver;
use GraphQLByPoP\GraphQLServer\ComponentConfiguration as GraphQLServerComponentConfiguration;

abstract class AbstractQueryExecutionSchemaConfigurator implements SchemaConfiguratorInterface
{
    /**
     * Extract the items defined in the Schema Configuration,
     * and inject them into the service as to take effect in the current GraphQL query
     */
    public function executeSchemaConfiguration(int $customPostID): void
    {
        // Check if it enabled by module
        $moduleRegistry = ModuleRegistryFacade::getInstance();
        if (!$moduleRegistry->isModuleEnabled(SchemaConfigurationFunctionalityModuleResolver::SCHEMA_CONFIGURATION)) {
            return;
        }

        if ($schemaConfigurationID = $this->getSchemaConfigurationID($customPostID)) {
            // Get that Schema Configuration, and load its settings
            $this->executeSchemaConfigurationItems($schemaConfigurationID);
        }
    }

    /**
     * Return the stored Schema Configuration ID
     */
    protected function getUserSettingSchemaConfigurationID(): ?int
    {
        $userSettingsManager = UserSettingsManagerFacade::getInstance();
        $schemaConfigurationID = $userSettingsManager->getSetting(SchemaConfigurationFunctionalityModuleResolver::SCHEMA_CONFIGURATION, SchemaConfigurationFunctionalityModuleResolver::OPTION_SCHEMA_CONFIGURATION_ID);
        // `null` is stored as OPTION_VALUE_NO_VALUE_ID
        if ($schemaConfigurationID == SchemaConfigurationFunctionalityModuleResolver::OPTION_VALUE_NO_VALUE_ID) {
            return null;
        }
        return $schemaConfigurationID;
    }

    /**
     * Extract the Schema Configuration ID from the block stored in the post
     */
    protected function getSchemaConfigurationID(int $customPostID): ?int
    {
        $instanceManager = InstanceManagerFacade::getInstance();
        /**
         * @var SchemaConfigurationBlock
         */
        $block = $instanceManager->getInstance(SchemaConfigurationBlock::class);
        $schemaConfigurationBlockDataItem = BlockHelpers::getSingleBlockOfTypeFromCustomPost($customPostID, $block);
        // If there was no schema configuration, then the default one has been selected
        // It is not saved in the DB, because it has been set as the default value in
        // blocks/schema-configuration/src/index.js
        if (is_null($schemaConfigurationBlockDataItem)) {
            return $this->getUserSettingSchemaConfigurationID();
        }

        $schemaConfiguration = $schemaConfigurationBlockDataItem['attrs'][SchemaConfigurationBlock::ATTRIBUTE_NAME_SCHEMA_CONFIGURATION] ?? null;
        // Check if $schemaConfiguration is one of the meta options (default, none, inherit)
        if ($schemaConfiguration == SchemaConfigurationBlock::ATTRIBUTE_VALUE_SCHEMA_CONFIGURATION_NONE) {
            return null;
        } elseif ($schemaConfiguration == SchemaConfigurationBlock::ATTRIBUTE_VALUE_SCHEMA_CONFIGURATION_DEFAULT) {
            return $this->getUserSettingSchemaConfigurationID();
        } elseif ($schemaConfiguration == SchemaConfigurationBlock::ATTRIBUTE_VALUE_SCHEMA_CONFIGURATION_INHERIT) {
            // If disabled by module, then return nothing
            $moduleRegistry = ModuleRegistryFacade::getInstance();
            if (!$moduleRegistry->isModuleEnabled(EndpointFunctionalityModuleResolver::API_HIERARCHY)) {
                return null;
            }
            // Return the schema configuration from the parent, or null if no parent exists
            /**
             * @var WP_Post|null
             */
            $customPost = \get_post($customPostID);
            if (!is_null($customPost) && $customPost->post_parent) {
                return $this->getSchemaConfigurationID($customPost->post_parent);
            }
            return null;
        }
        // It is already the ID, or null if blocks returned empty
        // (eg: because parent post was trashed)
        return $schemaConfiguration;
    }

    /**
     * Apply all the settings defined in the Schema Configuration:
     * - Options
     * - Access Control Lists
     * - Field Deprecation Lists
     */
    protected function executeSchemaConfigurationItems(int $schemaConfigurationID): void
    {
        $this->executeSchemaConfigurationOptions($schemaConfigurationID);
        $this->executeSchemaConfigurationAccessControlLists($schemaConfigurationID);
        $this->executeSchemaConfigurationFieldDeprecationLists($schemaConfigurationID);
    }

    /**
     * Apply all the settings defined in the Schema Configuration for:
     * - Namespacing
     * - Mutation Scheme
     * - Options
     */
    protected function executeSchemaConfigurationOptions(int $schemaConfigurationID): void
    {
        $this->executeSchemaConfigurationOptionsNamespacing($schemaConfigurationID);
        $this->executeSchemaConfigurationOptionsMutationScheme($schemaConfigurationID);
        $this->executeSchemaConfigurationOptionsDefaultSchemaMode($schemaConfigurationID);
    }

    /**
     * Apply the Namespacing settings
     */
    protected function executeSchemaConfigurationOptionsNamespacing(int $schemaConfigurationID): void
    {
        // Check if it enabled by module
        $moduleRegistry = ModuleRegistryFacade::getInstance();
        if (!$moduleRegistry->isModuleEnabled(SchemaConfigurationFunctionalityModuleResolver::SCHEMA_NAMESPACING)) {
            return;
        }

        $instanceManager = InstanceManagerFacade::getInstance();
        /**
         * @var SchemaConfigOptionsBlock
         */
        $block = $instanceManager->getInstance(SchemaConfigOptionsBlock::class);
        $schemaConfigOptionsBlockDataItem = BlockHelpers::getSingleBlockOfTypeFromCustomPost($schemaConfigurationID, $block);
        if (!is_null($schemaConfigOptionsBlockDataItem)) {
            /**
             * Default value (if not defined in DB): `default`. Then do nothing
             */
            $useNamespacing = $schemaConfigOptionsBlockDataItem['attrs'][SchemaConfigOptionsBlock::ATTRIBUTE_NAME_USE_NAMESPACING] ?? null;
            // Only execute if it has value "enabled" or "disabled".
            // If "default", then the general settings will already take effect, so do nothing
            // (And if any other unsupported value, also do nothing)
            if (
                !in_array($useNamespacing, [
                    SchemaConfigOptionsBlock::ATTRIBUTE_VALUE_USE_NAMESPACING_ENABLED,
                    SchemaConfigOptionsBlock::ATTRIBUTE_VALUE_USE_NAMESPACING_DISABLED,
                ])
            ) {
                return;
            }
            // Define the settings value through a hook. Execute last so it overrides the default settings
            $hookName = ComponentConfigurationHelpers::getHookName(ComponentModelComponentConfiguration::class, ComponentModelEnvironment::NAMESPACE_TYPES_AND_INTERFACES);
            \add_filter($hookName, function () use ($useNamespacing) {
                return $useNamespacing == SchemaConfigOptionsBlock::ATTRIBUTE_VALUE_USE_NAMESPACING_ENABLED;
            }, PHP_INT_MAX);
        }
    }

    /**
     * Apply the Mutation Scheme settings
     */
    protected function executeSchemaConfigurationOptionsMutationScheme(int $schemaConfigurationID): void
    {
        // Check if it enabled by module
        $moduleRegistry = ModuleRegistryFacade::getInstance();
        if (!$moduleRegistry->isModuleEnabled(OperationalFunctionalityModuleResolver::NESTED_MUTATIONS)) {
            return;
        }

        $instanceManager = InstanceManagerFacade::getInstance();
        /**
         * @var SchemaConfigOptionsBlock
         */
        $block = $instanceManager->getInstance(SchemaConfigOptionsBlock::class);
        $schemaConfigOptionsBlockDataItem = BlockHelpers::getSingleBlockOfTypeFromCustomPost($schemaConfigurationID, $block);
        if (!is_null($schemaConfigOptionsBlockDataItem)) {
            /**
             * Default value (if not defined in DB): `default`. Then do nothing
             */
            $mutationScheme = $schemaConfigOptionsBlockDataItem['attrs'][SchemaConfigOptionsBlock::ATTRIBUTE_NAME_MUTATION_SCHEME] ?? null;
            // Only execute if it has value "standard", "nested" or "lean_nested".
            // If "default", then the general settings will already take effect, so do nothing
            // (And if any other unsupported value, also do nothing)
            if (
                !in_array($mutationScheme, [
                    MutationSchemes::STANDARD,
                    MutationSchemes::NESTED_WITH_REDUNDANT_ROOT_FIELDS,
                    MutationSchemes::NESTED_WITHOUT_REDUNDANT_ROOT_FIELDS,
                ])
            ) {
                return;
            }
            // Define the settings value through a hook. Execute last so it overrides the default settings
            $hookName = ComponentConfigurationHelpers::getHookName(GraphQLServerComponentConfiguration::class, GraphQLServerEnvironment::ENABLE_NESTED_MUTATIONS);
            \add_filter($hookName, function () use ($mutationScheme) {
                return $mutationScheme != MutationSchemes::STANDARD;
            }, PHP_INT_MAX);
            $hookName = ComponentConfigurationHelpers::getHookName(EngineComponentConfiguration::class, EngineEnvironment::DISABLE_REDUNDANT_ROOT_TYPE_MUTATION_FIELDS);
            \add_filter($hookName, function () use ($mutationScheme) {
                return $mutationScheme == MutationSchemes::NESTED_WITHOUT_REDUNDANT_ROOT_FIELDS;
            }, PHP_INT_MAX);
        }
    }

    /**
     * Apply the default Schema mode settings
     */
    protected function executeSchemaConfigurationOptionsDefaultSchemaMode(int $schemaConfigurationID): void
    {
        // Check if it enabled by module
        $moduleRegistry = ModuleRegistryFacade::getInstance();
        if (!$moduleRegistry->isModuleEnabled(SchemaConfigurationFunctionalityModuleResolver::PUBLIC_PRIVATE_SCHEMA)) {
            return;
        }

        $instanceManager = InstanceManagerFacade::getInstance();
        /**
         * @var SchemaConfigOptionsBlock
         */
        $block = $instanceManager->getInstance(SchemaConfigOptionsBlock::class);
        $schemaConfigOptionsBlockDataItem = BlockHelpers::getSingleBlockOfTypeFromCustomPost($schemaConfigurationID, $block);
        if (!is_null($schemaConfigOptionsBlockDataItem)) {
            /**
             * Default value (if not defined in DB): `default`. Then do nothing
             */
            $defaultSchemaMode = $schemaConfigOptionsBlockDataItem['attrs'][SchemaConfigOptionsBlock::ATTRIBUTE_NAME_DEFAULT_SCHEMA_MODE] ?? null;
            // Only execute if it has value "public" or "private".
            // If "default", then the general settings will already take effect, so do nothing
            // (And if any other unsupported value, also do nothing)
            if (
                !in_array($defaultSchemaMode, [
                    SchemaModes::PUBLIC_SCHEMA_MODE,
                    SchemaModes::PRIVATE_SCHEMA_MODE,
                ])
            ) {
                return;
            }
            // Define the settings value through a hook. Execute last so it overrides the default settings
            $hookName = ComponentConfigurationHelpers::getHookName(AccessControlComponentConfiguration::class, AccessControlEnvironment::USE_PRIVATE_SCHEMA_MODE);
            \add_filter($hookName, function () use ($defaultSchemaMode) {
                return $defaultSchemaMode == SchemaModes::PRIVATE_SCHEMA_MODE;
            }, PHP_INT_MAX);
        }
    }

    /**
     * Apply all the settings defined in the Schema Configuration for:
     * - Access Control Lists
     */
    protected function executeSchemaConfigurationAccessControlLists(int $schemaConfigurationID): void
    {
        // Check it is enabled by module
        $moduleRegistry = ModuleRegistryFacade::getInstance();
        if (!$moduleRegistry->isModuleEnabled(AccessControlFunctionalityModuleResolver::ACCESS_CONTROL)) {
            return;
        }
        $instanceManager = InstanceManagerFacade::getInstance();
        /**
         * @var SchemaConfigAccessControlListBlock
         */
        $block = $instanceManager->getInstance(SchemaConfigAccessControlListBlock::class);
        $schemaConfigACLBlockDataItem = BlockHelpers::getSingleBlockOfTypeFromCustomPost($schemaConfigurationID, $block);
        if (!is_null($schemaConfigACLBlockDataItem)) {
            if ($accessControlLists = $schemaConfigACLBlockDataItem['attrs'][SchemaConfigAccessControlListBlock::ATTRIBUTE_NAME_ACCESS_CONTROL_LISTS] ?? null) {
                $configurator = new AccessControlGraphQLQueryConfigurator();
                foreach ($accessControlLists as $accessControlListID) {
                    $configurator->executeSchemaConfiguration($accessControlListID);
                }
            }
        }
    }

    /**
     * Apply all the settings defined in the Schema Configuration for:
     * - Field Deprecation Lists
     */
    protected function executeSchemaConfigurationFieldDeprecationLists(int $schemaConfigurationID): void
    {
        // Check it is enabled by module
        $moduleRegistry = ModuleRegistryFacade::getInstance();
        if (!$moduleRegistry->isModuleEnabled(VersioningFunctionalityModuleResolver::FIELD_DEPRECATION)) {
            return;
        }
        $instanceManager = InstanceManagerFacade::getInstance();
        /**
         * @var SchemaConfigFieldDeprecationListBlock
         */
        $block = $instanceManager->getInstance(SchemaConfigFieldDeprecationListBlock::class);
        $schemaConfigFDLBlockDataItem = BlockHelpers::getSingleBlockOfTypeFromCustomPost($schemaConfigurationID, $block);
        if (!is_null($schemaConfigFDLBlockDataItem)) {
            if ($fieldDeprecationLists = $schemaConfigFDLBlockDataItem['attrs'][SchemaConfigFieldDeprecationListBlock::ATTRIBUTE_NAME_FIELD_DEPRECATION_LISTS] ?? null) {
                $configurator = new FieldDeprecationGraphQLQueryConfigurator();
                foreach ($fieldDeprecationLists as $fieldDeprecationListID) {
                    $configurator->executeSchemaConfiguration($fieldDeprecationListID);
                }
            }
        }
    }
}
