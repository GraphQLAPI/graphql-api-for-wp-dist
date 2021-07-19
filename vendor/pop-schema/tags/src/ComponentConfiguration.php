<?php

declare (strict_types=1);
namespace PoPSchema\Tags;

use PoP\ComponentModel\ComponentConfiguration\EnvironmentValueHelpers;
use PoP\ComponentModel\ComponentConfiguration\ComponentConfigurationTrait;
class ComponentConfiguration
{
    use ComponentConfigurationTrait;
    /**
     * @var int|null
     */
    private static $getTagListDefaultLimit;
    /**
     * @var int|null
     */
    private static $getTagListMaxLimit;
    public static function getTagListDefaultLimit() : ?int
    {
        // Define properties
        $envVariable = \PoPSchema\Tags\Environment::TAG_LIST_DEFAULT_LIMIT;
        $selfProperty =& self::$getTagListDefaultLimit;
        $defaultValue = 10;
        $callback = [EnvironmentValueHelpers::class, 'toInt'];
        // Initialize property from the environment/hook
        self::maybeInitializeConfigurationValue($envVariable, $selfProperty, $defaultValue, $callback);
        return $selfProperty;
    }
    public static function getTagListMaxLimit() : ?int
    {
        // Define properties
        $envVariable = \PoPSchema\Tags\Environment::TAG_LIST_MAX_LIMIT;
        $selfProperty =& self::$getTagListMaxLimit;
        $defaultValue = -1;
        // Unlimited
        $callback = [EnvironmentValueHelpers::class, 'toInt'];
        // Initialize property from the environment/hook
        self::maybeInitializeConfigurationValue($envVariable, $selfProperty, $defaultValue, $callback);
        return $selfProperty;
    }
}
