<?php

declare (strict_types=1);
namespace PoP\ComponentModel;

use PoP\Root\App;
use PoP\Root\Module\AbstractModuleConfiguration;
use PoP\Root\Module\EnvironmentValueHelpers;
use PoP\Root\Environment as RootEnvironment;
use PoP\Root\Module as RootModule;
use PoP\Root\ModuleConfiguration as RootModuleConfiguration;
class ModuleConfiguration extends AbstractModuleConfiguration
{
    public function enableComponentModelCache() : bool
    {
        $envVariable = \PoP\ComponentModel\Environment::ENABLE_COMPONENT_MODEL_CACHE;
        $defaultValue = \false;
        $callback = \Closure::fromCallable([EnvironmentValueHelpers::class, 'toBool']);
        return $this->retrieveConfigurationValueOrUseDefault($envVariable, $defaultValue, $callback);
    }
    public function useComponentModelCache() : bool
    {
        if (!$this->enableComponentModelCache()) {
            return \false;
        }
        /**
         * Component Model Cache is currently broken,
         * hence do not enable this functionality.
         *
         * @see https://github.com/leoloso/PoP/issues/1614
         */
        return \false;
        /** @phpstan-ignore-next-line */
        $envVariable = \PoP\ComponentModel\Environment::USE_COMPONENT_MODEL_CACHE;
        $defaultValue = \false;
        $callback = \Closure::fromCallable([EnvironmentValueHelpers::class, 'toBool']);
        return $this->retrieveConfigurationValueOrUseDefault($envVariable, $defaultValue, $callback);
    }
    /**
     * @param string $envVariable
     */
    protected function enableHook($envVariable) : bool
    {
        switch ($envVariable) {
            case \PoP\ComponentModel\Environment::ENABLE_COMPONENT_MODEL_CACHE:
            case \PoP\ComponentModel\Environment::USE_COMPONENT_MODEL_CACHE:
                return \false;
            default:
                return parent::enableHook($envVariable);
        }
    }
    public function mustNamespaceTypes() : bool
    {
        $envVariable = \PoP\ComponentModel\Environment::NAMESPACE_TYPES_AND_INTERFACES;
        $defaultValue = \false;
        $callback = \Closure::fromCallable([EnvironmentValueHelpers::class, 'toBool']);
        return $this->retrieveConfigurationValueOrUseDefault($envVariable, $defaultValue, $callback);
    }
    public function enableMutations() : bool
    {
        $envVariable = \PoP\ComponentModel\Environment::ENABLE_MUTATIONS;
        $defaultValue = \true;
        $callback = \Closure::fromCallable([EnvironmentValueHelpers::class, 'toBool']);
        return $this->retrieveConfigurationValueOrUseDefault($envVariable, $defaultValue, $callback);
    }
    public function exposeSensitiveDataInSchema() : bool
    {
        $envVariable = \PoP\ComponentModel\Environment::EXPOSE_SENSITIVE_DATA_IN_SCHEMA;
        $defaultValue = \false;
        $callback = \Closure::fromCallable([EnvironmentValueHelpers::class, 'toBool']);
        return $this->retrieveConfigurationValueOrUseDefault($envVariable, $defaultValue, $callback);
    }
    /**
     * By default, validate for DEV only
     */
    public function validateFieldTypeResponseWithSchemaDefinition() : bool
    {
        $envVariable = \PoP\ComponentModel\Environment::VALIDATE_FIELD_TYPE_RESPONSE_WITH_SCHEMA_DEFINITION;
        $defaultValue = RootEnvironment::isApplicationEnvironmentDev();
        $callback = \Closure::fromCallable([EnvironmentValueHelpers::class, 'toBool']);
        return $this->retrieveConfigurationValueOrUseDefault($envVariable, $defaultValue, $callback);
    }
    /**
     * Indicate: If a directive fails, then set those fields in `null`
     * and remove the affected IDs/fields from the upcoming stages
     * of the directive pipeline execution.
     */
    public function setFieldAsNullIfDirectiveFailed() : bool
    {
        $envVariable = \PoP\ComponentModel\Environment::SET_FIELD_AS_NULL_IF_DIRECTIVE_FAILED;
        $defaultValue = \true;
        $callback = \Closure::fromCallable([EnvironmentValueHelpers::class, 'toBool']);
        return $this->retrieveConfigurationValueOrUseDefault($envVariable, $defaultValue, $callback);
    }
    /**
     * Support passing a single value where a list is expected.
     * Defined in the GraphQL spec.
     *
     * @see https://spec.graphql.org/draft/#sec-List.Input-Coercion
     */
    public function convertInputValueFromSingleToList() : bool
    {
        $envVariable = \PoP\ComponentModel\Environment::CONVERT_INPUT_VALUE_FROM_SINGLE_TO_LIST;
        $defaultValue = \true;
        $callback = \Closure::fromCallable([EnvironmentValueHelpers::class, 'toBool']);
        return $this->retrieveConfigurationValueOrUseDefault($envVariable, $defaultValue, $callback);
    }
    /**
     * Support GraphQL RFC "Union types can implement interfaces".
     * It is disabled by default because it can lead to runtime exceptions.
     *
     * @see https://github.com/graphql/graphql-spec/issues/518
     */
    public function enableUnionTypeImplementingInterfaceType() : bool
    {
        $envVariable = \PoP\ComponentModel\Environment::ENABLE_UNION_TYPE_IMPLEMENTING_INTERFACE_TYPE;
        $defaultValue = \false;
        $callback = \Closure::fromCallable([EnvironmentValueHelpers::class, 'toBool']);
        return $this->retrieveConfigurationValueOrUseDefault($envVariable, $defaultValue, $callback);
    }
    /**
     * `DangerouslyNonSpecificScalar` is a special scalar type which is not coerced or validated.
     * In particular, it does not need to validate if it is an array or not,
     * as according to the applied WrappingType.
     *
     * This behavior is not compatible with the GraphQL spec!
     *
     * For instance, type `DangerouslyNonSpecificScalar` could have values
     * `"hello"` and `["hello"]`, but in GraphQL we must differentiate
     * these values by types `String` and `[String]`.
     *
     * This config enables to disable this behavior. In this case, all fields,
     * field arguments and directive arguments which use this type will
     * automatically not be added to the schema.
     */
    public function skipExposingDangerouslyNonSpecificScalarTypeTypeInSchema() : bool
    {
        $envVariable = \PoP\ComponentModel\Environment::SKIP_EXPOSING_DANGEROUSLY_NON_SPECIFIC_SCALAR_TYPE_IN_SCHEMA;
        $defaultValue = \false;
        $callback = \Closure::fromCallable([EnvironmentValueHelpers::class, 'toBool']);
        return $this->retrieveConfigurationValueOrUseDefault($envVariable, $defaultValue, $callback);
    }
    /**
     * Indicate if users can add URL params that modify the Engine's behavior.
     */
    public function enableModifyingEngineBehaviorViaRequest() : bool
    {
        /** @var RootModuleConfiguration */
        $rootModuleConfiguration = App::getModule(RootModule::class)->getConfiguration();
        if (!$rootModuleConfiguration->enablePassingStateViaRequest()) {
            return \false;
        }
        $envVariable = \PoP\ComponentModel\Environment::ENABLE_MODIFYING_ENGINE_BEHAVIOR_VIA_REQUEST;
        $defaultValue = \false;
        $callback = \Closure::fromCallable([EnvironmentValueHelpers::class, 'toBool']);
        return $this->retrieveConfigurationValueOrUseDefault($envVariable, $defaultValue, $callback);
    }
    /**
     * @return string[]
     */
    public function getEnabledFeedbackCategoryExtensions() : array
    {
        $envVariable = \PoP\ComponentModel\Environment::ENABLE_FEEDBACK_CATEGORY_EXTENSIONS;
        $defaultValue = [];
        $callback = \Closure::fromCallable([EnvironmentValueHelpers::class, 'commaSeparatedStringToArray']);
        return $this->retrieveConfigurationValueOrUseDefault($envVariable, $defaultValue, $callback);
    }
    public function logExceptionErrorMessagesAndTraces() : bool
    {
        $envVariable = \PoP\ComponentModel\Environment::LOG_EXCEPTION_ERROR_MESSAGES_AND_TRACES;
        $defaultValue = \false;
        $callback = \Closure::fromCallable([EnvironmentValueHelpers::class, 'toBool']);
        return $this->retrieveConfigurationValueOrUseDefault($envVariable, $defaultValue, $callback);
    }
    public function sendExceptionErrorMessages() : bool
    {
        $envVariable = \PoP\ComponentModel\Environment::SEND_EXCEPTION_ERROR_MESSAGES;
        $defaultValue = RootEnvironment::isApplicationEnvironmentDev();
        $callback = \Closure::fromCallable([EnvironmentValueHelpers::class, 'toBool']);
        return $this->retrieveConfigurationValueOrUseDefault($envVariable, $defaultValue, $callback);
    }
    public function sendExceptionTraces() : bool
    {
        if (!$this->sendExceptionErrorMessages()) {
            return \false;
        }
        $envVariable = \PoP\ComponentModel\Environment::SEND_EXCEPTION_TRACES;
        $defaultValue = RootEnvironment::isApplicationEnvironmentDev();
        $callback = \Closure::fromCallable([EnvironmentValueHelpers::class, 'toBool']);
        return $this->retrieveConfigurationValueOrUseDefault($envVariable, $defaultValue, $callback);
    }
    public function enableSelfField() : bool
    {
        $envVariable = \PoP\ComponentModel\Environment::ENABLE_SELF_FIELD;
        $defaultValue = \true;
        $callback = \Closure::fromCallable([EnvironmentValueHelpers::class, 'toBool']);
        return $this->retrieveConfigurationValueOrUseDefault($envVariable, $defaultValue, $callback);
    }
    public function enableTypeNameGlobalFields() : bool
    {
        $envVariable = \PoP\ComponentModel\Environment::ENABLE_TYPENAME_GLOBAL_FIELDS;
        $defaultValue = \true;
        $callback = \Closure::fromCallable([EnvironmentValueHelpers::class, 'toBool']);
        return $this->retrieveConfigurationValueOrUseDefault($envVariable, $defaultValue, $callback);
    }
    public function exposeCoreFunctionalityGlobalFields() : bool
    {
        $envVariable = \PoP\ComponentModel\Environment::EXPOSE_CORE_FUNCTIONALITY_GLOBAL_FIELDS;
        $defaultValue = \false;
        $callback = \Closure::fromCallable([EnvironmentValueHelpers::class, 'toBool']);
        return $this->retrieveConfigurationValueOrUseDefault($envVariable, $defaultValue, $callback);
    }
    public function exposeSchemaTypeDirectiveLocations() : bool
    {
        $envVariable = \PoP\ComponentModel\Environment::EXPOSE_SCHEMA_TYPE_DIRECTIVE_LOCATIONS;
        $defaultValue = \true;
        $callback = \Closure::fromCallable([EnvironmentValueHelpers::class, 'toBool']);
        return $this->retrieveConfigurationValueOrUseDefault($envVariable, $defaultValue, $callback);
    }
    /**
     * Indicate if to enable to restrict a field and directive by version,
     * using the same semantic versioning constraint rules used by Composer
     *
     * @see https://getcomposer.org/doc/articles/versions.md Composer's semver constraint rules
     */
    public function enableSemanticVersionConstraints() : bool
    {
        $envVariable = \PoP\ComponentModel\Environment::ENABLE_SEMANTIC_VERSION_CONSTRAINTS;
        $defaultValue = \true;
        $callback = \Closure::fromCallable([EnvironmentValueHelpers::class, 'toBool']);
        return $this->retrieveConfigurationValueOrUseDefault($envVariable, $defaultValue, $callback);
    }
}
