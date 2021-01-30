<?php

declare (strict_types=1);
namespace PoP\Engine;

use PoP\ComponentModel\ComponentConfiguration\EnvironmentValueHelpers;
use PoP\ComponentModel\ComponentConfiguration\ComponentConfigurationTrait;
class ComponentConfiguration
{
    use ComponentConfigurationTrait;
    private static $addMandatoryCacheControlDirective;
    private static $disableRedundantRootTypeMutationFields;
    public static function addMandatoryCacheControlDirective() : bool
    {
        // Define properties
        $envVariable = \PoP\Engine\Environment::ADD_MANDATORY_CACHE_CONTROL_DIRECTIVE;
        $selfProperty =& self::$addMandatoryCacheControlDirective;
        $defaultValue = \true;
        $callback = [\PoP\ComponentModel\ComponentConfiguration\EnvironmentValueHelpers::class, 'toBool'];
        $useHook = \false;
        // Initialize property from the environment/hook
        self::maybeInitializeConfigurationValue($envVariable, $selfProperty, $defaultValue, $callback, $useHook);
        return $selfProperty;
    }
    public static function disableRedundantRootTypeMutationFields() : bool
    {
        // Define properties
        $envVariable = \PoP\Engine\Environment::DISABLE_REDUNDANT_ROOT_TYPE_MUTATION_FIELDS;
        $selfProperty =& self::$disableRedundantRootTypeMutationFields;
        $defaultValue = \false;
        $callback = [\PoP\ComponentModel\ComponentConfiguration\EnvironmentValueHelpers::class, 'toBool'];
        // Initialize property from the environment/hook
        self::maybeInitializeConfigurationValue($envVariable, $selfProperty, $defaultValue, $callback);
        return $selfProperty;
    }
}
