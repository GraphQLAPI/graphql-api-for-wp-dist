<?php

declare (strict_types=1);
namespace PoPSchema\Pages;

use PoP\ComponentModel\ComponentConfiguration\EnvironmentValueHelpers;
use PoP\ComponentModel\ComponentConfiguration\ComponentConfigurationTrait;
class ComponentConfiguration
{
    use ComponentConfigurationTrait;
    private static $getPageListDefaultLimit;
    private static $getPageListMaxLimit;
    private static $addPageTypeToCustomPostUnionTypes;
    public static function getPageListDefaultLimit() : ?int
    {
        // Define properties
        $envVariable = \PoPSchema\Pages\Environment::PAGE_LIST_DEFAULT_LIMIT;
        $selfProperty =& self::$getPageListDefaultLimit;
        $defaultValue = 10;
        $callback = [\PoP\ComponentModel\ComponentConfiguration\EnvironmentValueHelpers::class, 'toInt'];
        // Initialize property from the environment/hook
        self::maybeInitializeConfigurationValue($envVariable, $selfProperty, $defaultValue, $callback);
        return $selfProperty;
    }
    public static function getPageListMaxLimit() : ?int
    {
        // Define properties
        $envVariable = \PoPSchema\Pages\Environment::PAGE_LIST_MAX_LIMIT;
        $selfProperty =& self::$getPageListMaxLimit;
        $defaultValue = -1;
        // Unlimited
        $callback = [\PoP\ComponentModel\ComponentConfiguration\EnvironmentValueHelpers::class, 'toInt'];
        // Initialize property from the environment/hook
        self::maybeInitializeConfigurationValue($envVariable, $selfProperty, $defaultValue, $callback);
        return $selfProperty;
    }
    public static function addPageTypeToCustomPostUnionTypes() : bool
    {
        // Define properties
        $envVariable = \PoPSchema\Pages\Environment::ADD_PAGE_TYPE_TO_CUSTOMPOST_UNION_TYPES;
        $selfProperty =& self::$addPageTypeToCustomPostUnionTypes;
        $defaultValue = \false;
        $callback = [\PoP\ComponentModel\ComponentConfiguration\EnvironmentValueHelpers::class, 'toBool'];
        // Initialize property from the environment/hook
        self::maybeInitializeConfigurationValue($envVariable, $selfProperty, $defaultValue, $callback);
        return $selfProperty;
    }
}
