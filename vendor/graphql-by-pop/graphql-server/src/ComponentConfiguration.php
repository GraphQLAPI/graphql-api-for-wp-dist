<?php

declare (strict_types=1);
namespace GraphQLByPoP\GraphQLServer;

use PoP\ComponentModel\ComponentConfiguration\EnvironmentValueHelpers;
use PoP\ComponentModel\ComponentConfiguration\ComponentConfigurationTrait;
class ComponentConfiguration
{
    use ComponentConfigurationTrait;
    /**
     * @var bool
     */
    private static $addSelfFieldForRootTypeToSchema = \false;
    /**
     * @var bool
     */
    private static $sortSchemaAlphabetically = \true;
    /**
     * @var bool
     */
    private static $enableProactiveFeedback = \true;
    /**
     * @var bool
     */
    private static $enableProactiveFeedbackDeprecations = \true;
    /**
     * @var bool
     */
    private static $enableProactiveFeedbackNotices = \true;
    /**
     * @var bool
     */
    private static $enableProactiveFeedbackTraces = \true;
    /**
     * @var bool
     */
    private static $enableProactiveFeedbackLogs = \true;
    /**
     * @var bool
     */
    private static $enableNestedMutations = \false;
    /**
     * @var bool|null
     */
    private static $enableGraphQLIntrospection;
    public static function addSelfFieldForRootTypeToSchema() : bool
    {
        // Define properties
        $envVariable = \GraphQLByPoP\GraphQLServer\Environment::ADD_SELF_FIELD_FOR_ROOT_TYPE_TO_SCHEMA;
        $selfProperty =& self::$addSelfFieldForRootTypeToSchema;
        $defaultValue = \false;
        $callback = [EnvironmentValueHelpers::class, 'toBool'];
        // Initialize property from the environment/hook
        self::maybeInitializeConfigurationValue($envVariable, $selfProperty, $defaultValue, $callback);
        return $selfProperty;
    }
    public static function sortSchemaAlphabetically() : bool
    {
        // Define properties
        $envVariable = \GraphQLByPoP\GraphQLServer\Environment::SORT_SCHEMA_ALPHABETICALLY;
        $selfProperty =& self::$sortSchemaAlphabetically;
        $defaultValue = \true;
        $callback = [EnvironmentValueHelpers::class, 'toBool'];
        // Initialize property from the environment/hook
        self::maybeInitializeConfigurationValue($envVariable, $selfProperty, $defaultValue, $callback);
        return $selfProperty;
    }
    public static function enableProactiveFeedback() : bool
    {
        // Define properties
        $envVariable = \GraphQLByPoP\GraphQLServer\Environment::ENABLE_PROACTIVE_FEEDBACK;
        $selfProperty =& self::$enableProactiveFeedback;
        $defaultValue = \true;
        $callback = [EnvironmentValueHelpers::class, 'toBool'];
        // Initialize property from the environment/hook
        self::maybeInitializeConfigurationValue($envVariable, $selfProperty, $defaultValue, $callback);
        return $selfProperty;
    }
    public static function enableProactiveFeedbackDeprecations() : bool
    {
        // Define properties
        $envVariable = \GraphQLByPoP\GraphQLServer\Environment::ENABLE_PROACTIVE_FEEDBACK_DEPRECATIONS;
        $selfProperty =& self::$enableProactiveFeedbackDeprecations;
        $defaultValue = \true;
        $callback = [EnvironmentValueHelpers::class, 'toBool'];
        // Initialize property from the environment/hook
        self::maybeInitializeConfigurationValue($envVariable, $selfProperty, $defaultValue, $callback);
        return $selfProperty;
    }
    public static function enableProactiveFeedbackNotices() : bool
    {
        // Define properties
        $envVariable = \GraphQLByPoP\GraphQLServer\Environment::ENABLE_PROACTIVE_FEEDBACK_NOTICES;
        $selfProperty =& self::$enableProactiveFeedbackNotices;
        $defaultValue = \true;
        $callback = [EnvironmentValueHelpers::class, 'toBool'];
        // Initialize property from the environment/hook
        self::maybeInitializeConfigurationValue($envVariable, $selfProperty, $defaultValue, $callback);
        return $selfProperty;
    }
    public static function enableProactiveFeedbackTraces() : bool
    {
        // Define properties
        $envVariable = \GraphQLByPoP\GraphQLServer\Environment::ENABLE_PROACTIVE_FEEDBACK_TRACES;
        $selfProperty =& self::$enableProactiveFeedbackTraces;
        $defaultValue = \true;
        $callback = [EnvironmentValueHelpers::class, 'toBool'];
        // Initialize property from the environment/hook
        self::maybeInitializeConfigurationValue($envVariable, $selfProperty, $defaultValue, $callback);
        return $selfProperty;
    }
    public static function enableProactiveFeedbackLogs() : bool
    {
        // Define properties
        $envVariable = \GraphQLByPoP\GraphQLServer\Environment::ENABLE_PROACTIVE_FEEDBACK_LOGS;
        $selfProperty =& self::$enableProactiveFeedbackLogs;
        $defaultValue = \true;
        $callback = [EnvironmentValueHelpers::class, 'toBool'];
        // Initialize property from the environment/hook
        self::maybeInitializeConfigurationValue($envVariable, $selfProperty, $defaultValue, $callback);
        return $selfProperty;
    }
    public static function enableNestedMutations() : bool
    {
        // Define properties
        $envVariable = \GraphQLByPoP\GraphQLServer\Environment::ENABLE_NESTED_MUTATIONS;
        $selfProperty =& self::$enableNestedMutations;
        $defaultValue = \false;
        $callback = [EnvironmentValueHelpers::class, 'toBool'];
        // Initialize property from the environment/hook
        self::maybeInitializeConfigurationValue($envVariable, $selfProperty, $defaultValue, $callback);
        return $selfProperty;
    }
    public static function enableGraphQLIntrospection() : ?bool
    {
        // Define properties
        $envVariable = \GraphQLByPoP\GraphQLServer\Environment::ENABLE_GRAPHQL_INTROSPECTION;
        $selfProperty =& self::$enableGraphQLIntrospection;
        $defaultValue = null;
        $callback = [EnvironmentValueHelpers::class, 'toBool'];
        // Initialize property from the environment/hook
        self::maybeInitializeConfigurationValue($envVariable, $selfProperty, $defaultValue, $callback);
        return $selfProperty;
    }
}
