<?php

declare (strict_types=1);
namespace PoPAPI\API;

use PoP\Root\App;
use PoP\Root\Module\AbstractModuleConfiguration;
use PoP\ComponentModel\Module as ComponentModelModule;
use PoP\ComponentModel\ModuleConfiguration as ComponentModelModuleConfiguration;
use PoP\Root\Module\EnvironmentValueHelpers;
class ModuleConfiguration extends AbstractModuleConfiguration
{
    public function useSchemaDefinitionCache() : bool
    {
        // First check that the Component Model cache is enabled
        /** @var ComponentModelModuleConfiguration */
        $moduleConfiguration = App::getModule(ComponentModelModule::class)->getConfiguration();
        if (!$moduleConfiguration->enableComponentModelCache()) {
            return \false;
        }
        $envVariable = \PoPAPI\API\Environment::USE_SCHEMA_DEFINITION_CACHE;
        $defaultValue = \false;
        $callback = \Closure::fromCallable([EnvironmentValueHelpers::class, 'toBool']);
        return $this->retrieveConfigurationValueOrUseDefault($envVariable, $defaultValue, $callback);
    }
    public function skipExposingGlobalFieldsInFullSchema() : bool
    {
        $envVariable = \PoPAPI\API\Environment::SKIP_EXPOSING_GLOBAL_FIELDS_IN_FULL_SCHEMA;
        $defaultValue = \false;
        $callback = \Closure::fromCallable([EnvironmentValueHelpers::class, 'toBool']);
        return $this->retrieveConfigurationValueOrUseDefault($envVariable, $defaultValue, $callback);
    }
    public function sortFullSchemaAlphabetically() : bool
    {
        $envVariable = \PoPAPI\API\Environment::SORT_FULL_SCHEMA_ALPHABETICALLY;
        $defaultValue = \true;
        $callback = \Closure::fromCallable([EnvironmentValueHelpers::class, 'toBool']);
        return $this->retrieveConfigurationValueOrUseDefault($envVariable, $defaultValue, $callback);
    }
    public function addFullSchemaFieldToSchema() : bool
    {
        $envVariable = \PoPAPI\API\Environment::ADD_FULLSCHEMA_FIELD_TO_SCHEMA;
        $defaultValue = \false;
        $callback = \Closure::fromCallable([EnvironmentValueHelpers::class, 'toBool']);
        return $this->retrieveConfigurationValueOrUseDefault($envVariable, $defaultValue, $callback);
    }
    public function enablePassingPersistedQueryNameViaURLParam() : bool
    {
        $envVariable = \PoPAPI\API\Environment::ENABLE_PASSING_PERSISTED_QUERY_NAME_VIA_URL_PARAM;
        $defaultValue = \false;
        $callback = \Closure::fromCallable([EnvironmentValueHelpers::class, 'toBool']);
        return $this->retrieveConfigurationValueOrUseDefault($envVariable, $defaultValue, $callback);
    }
}
