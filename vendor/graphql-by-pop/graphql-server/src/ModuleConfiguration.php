<?php

declare (strict_types=1);
namespace GraphQLByPoP\GraphQLServer;

use PoP\Root\App;
use PoP\Root\Module\AbstractModuleConfiguration;
use PoPAPI\API\Module as APIModule;
use PoPAPI\API\ModuleConfiguration as APIModuleConfiguration;
use PoP\Root\Module\EnvironmentValueHelpers;
class ModuleConfiguration extends AbstractModuleConfiguration
{
    public function sortGraphQLSchemaAlphabetically() : bool
    {
        $envVariable = \GraphQLByPoP\GraphQLServer\Environment::SORT_GRAPHQL_SCHEMA_ALPHABETICALLY;
        $defaultValue = \true;
        $callback = \Closure::fromCallable([EnvironmentValueHelpers::class, 'toBool']);
        return $this->retrieveConfigurationValueOrUseDefault($envVariable, $defaultValue, $callback);
    }
    public function sortGlobalFieldsAfterNormalFieldsInGraphQLSchema() : bool
    {
        $envVariable = \GraphQLByPoP\GraphQLServer\Environment::SORT_GLOBAL_FIELDS_AFTER_NORMAL_FIELDS_IN_GRAPHQL_SCHEMA;
        $defaultValue = \true;
        $callback = \Closure::fromCallable([EnvironmentValueHelpers::class, 'toBool']);
        return $this->retrieveConfigurationValueOrUseDefault($envVariable, $defaultValue, $callback);
    }
    public function enableProactiveFeedback() : bool
    {
        $envVariable = \GraphQLByPoP\GraphQLServer\Environment::ENABLE_PROACTIVE_FEEDBACK;
        $defaultValue = \true;
        $callback = \Closure::fromCallable([EnvironmentValueHelpers::class, 'toBool']);
        return $this->retrieveConfigurationValueOrUseDefault($envVariable, $defaultValue, $callback);
    }
    public function enableNestedMutations() : bool
    {
        $envVariable = \GraphQLByPoP\GraphQLServer\Environment::ENABLE_NESTED_MUTATIONS;
        $defaultValue = \false;
        $callback = \Closure::fromCallable([EnvironmentValueHelpers::class, 'toBool']);
        return $this->retrieveConfigurationValueOrUseDefault($envVariable, $defaultValue, $callback);
    }
    public function enableGraphQLIntrospection() : ?bool
    {
        if (!\GraphQLByPoP\GraphQLServer\Environment::enableEnablingGraphQLIntrospectionByURLParam()) {
            return null;
        }
        $envVariable = \GraphQLByPoP\GraphQLServer\Environment::ENABLE_GRAPHQL_INTROSPECTION;
        $defaultValue = null;
        $callback = \Closure::fromCallable([EnvironmentValueHelpers::class, 'toBool']);
        return $this->retrieveConfigurationValueOrUseDefault($envVariable, $defaultValue, $callback);
    }
    public function addVersionToGraphQLSchemaFieldDescription() : bool
    {
        $envVariable = \GraphQLByPoP\GraphQLServer\Environment::ADD_VERSION_TO_GRAPHQL_SCHEMA_FIELD_DESCRIPTION;
        $defaultValue = \false;
        $callback = \Closure::fromCallable([EnvironmentValueHelpers::class, 'toBool']);
        return $this->retrieveConfigurationValueOrUseDefault($envVariable, $defaultValue, $callback);
    }
    public function addGraphQLIntrospectionPersistedQuery() : bool
    {
        $envVariable = \GraphQLByPoP\GraphQLServer\Environment::ADD_GRAPHQL_INTROSPECTION_PERSISTED_QUERY;
        $defaultValue = \false;
        $callback = \Closure::fromCallable([EnvironmentValueHelpers::class, 'toBool']);
        return $this->retrieveConfigurationValueOrUseDefault($envVariable, $defaultValue, $callback);
    }
    public function addConnectionFromRootToQueryRootAndMutationRoot() : bool
    {
        $envVariable = \GraphQLByPoP\GraphQLServer\Environment::ADD_CONNECTION_FROM_ROOT_TO_QUERYROOT_AND_MUTATIONROOT;
        $defaultValue = \false;
        $callback = \Closure::fromCallable([EnvironmentValueHelpers::class, 'toBool']);
        return $this->retrieveConfigurationValueOrUseDefault($envVariable, $defaultValue, $callback);
    }
    public function exposeSchemaIntrospectionFieldInSchema() : bool
    {
        $envVariable = \GraphQLByPoP\GraphQLServer\Environment::EXPOSE_SCHEMA_INTROSPECTION_FIELD_IN_SCHEMA;
        $defaultValue = \false;
        $callback = \Closure::fromCallable([EnvironmentValueHelpers::class, 'toBool']);
        return $this->retrieveConfigurationValueOrUseDefault($envVariable, $defaultValue, $callback);
    }
    public function exposeGlobalFieldsInGraphQLSchema() : bool
    {
        /** @var APIModuleConfiguration */
        $moduleConfiguration = App::getModule(APIModule::class)->getConfiguration();
        if ($moduleConfiguration->skipExposingGlobalFieldsInFullSchema()) {
            return \false;
        }
        $envVariable = \GraphQLByPoP\GraphQLServer\Environment::EXPOSE_GLOBAL_FIELDS_IN_GRAPHQL_SCHEMA;
        $defaultValue = \false;
        $callback = \Closure::fromCallable([EnvironmentValueHelpers::class, 'toBool']);
        return $this->retrieveConfigurationValueOrUseDefault($envVariable, $defaultValue, $callback);
    }
    public function exposeGlobalFieldsInRootTypeOnlyInGraphQLSchema() : bool
    {
        if (!$this->exposeGlobalFieldsInGraphQLSchema()) {
            return \false;
        }
        $envVariable = \GraphQLByPoP\GraphQLServer\Environment::EXPOSE_GLOBAL_FIELDS_IN_ROOT_TYPE_ONLY_IN_GRAPHQL_SCHEMA;
        $defaultValue = \false;
        $callback = \Closure::fromCallable([EnvironmentValueHelpers::class, 'toBool']);
        return $this->retrieveConfigurationValueOrUseDefault($envVariable, $defaultValue, $callback);
    }
}
