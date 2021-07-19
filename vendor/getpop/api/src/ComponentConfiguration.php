<?php

declare (strict_types=1);
namespace PoP\API;

use PoP\ComponentModel\ComponentConfiguration\EnvironmentValueHelpers;
use PoP\ComponentModel\ComponentConfiguration\ComponentConfigurationTrait;
use PoP\ComponentModel\ComponentConfiguration as ComponentModelComponentConfiguration;
class ComponentConfiguration
{
    use ComponentConfigurationTrait;
    /**
     * @var bool
     */
    private static $useSchemaDefinitionCache = \false;
    /**
     * @var bool
     */
    private static $executeQueryBatchInStrictOrder = \true;
    /**
     * @var bool
     */
    private static $enableEmbeddableFields = \false;
    /**
     * @var bool
     */
    private static $enableMutations = \true;
    /**
     * @var bool
     */
    private static $overrideRequestURI = \false;
    public static function useSchemaDefinitionCache() : bool
    {
        // First check that the Component Model cache is enabled
        if (!ComponentModelComponentConfiguration::useComponentModelCache()) {
            return \false;
        }
        // Define properties
        $envVariable = \PoP\API\Environment::USE_SCHEMA_DEFINITION_CACHE;
        $selfProperty =& self::$useSchemaDefinitionCache;
        $defaultValue = \false;
        $callback = [EnvironmentValueHelpers::class, 'toBool'];
        // Initialize property from the environment/hook
        self::maybeInitializeConfigurationValue($envVariable, $selfProperty, $defaultValue, $callback);
        return $selfProperty;
    }
    public static function executeQueryBatchInStrictOrder() : bool
    {
        // Define properties
        $envVariable = \PoP\API\Environment::EXECUTE_QUERY_BATCH_IN_STRICT_ORDER;
        $selfProperty =& self::$executeQueryBatchInStrictOrder;
        $defaultValue = \true;
        $callback = [EnvironmentValueHelpers::class, 'toBool'];
        // Initialize property from the environment/hook
        self::maybeInitializeConfigurationValue($envVariable, $selfProperty, $defaultValue, $callback);
        return $selfProperty;
    }
    public static function enableEmbeddableFields() : bool
    {
        // Define properties
        $envVariable = \PoP\API\Environment::ENABLE_EMBEDDABLE_FIELDS;
        $selfProperty =& self::$enableEmbeddableFields;
        $defaultValue = \false;
        $callback = [EnvironmentValueHelpers::class, 'toBool'];
        // Initialize property from the environment/hook
        self::maybeInitializeConfigurationValue($envVariable, $selfProperty, $defaultValue, $callback);
        return $selfProperty;
    }
    public static function enableMutations() : bool
    {
        // Define properties
        $envVariable = \PoP\API\Environment::ENABLE_MUTATIONS;
        $selfProperty =& self::$enableMutations;
        $defaultValue = \true;
        $callback = [EnvironmentValueHelpers::class, 'toBool'];
        // Initialize property from the environment/hook
        self::maybeInitializeConfigurationValue($envVariable, $selfProperty, $defaultValue, $callback);
        return $selfProperty;
    }
    /**
     * Remove unwanted data added to the REQUEST_URI, replacing
     * it with the website home URL.
     * Eg: the language information from qTranslate (https://domain.com/en/...)
     */
    public static function overrideRequestURI() : bool
    {
        // Define properties
        $envVariable = \PoP\API\Environment::OVERRIDE_REQUEST_URI;
        $selfProperty =& self::$overrideRequestURI;
        $defaultValue = \false;
        $callback = [EnvironmentValueHelpers::class, 'toBool'];
        // Initialize property from the environment/hook
        self::maybeInitializeConfigurationValue($envVariable, $selfProperty, $defaultValue, $callback);
        return $selfProperty;
    }
}
