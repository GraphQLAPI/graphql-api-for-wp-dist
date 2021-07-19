<?php

declare (strict_types=1);
namespace PoPSchema\CustomPostMeta;

use PoP\ComponentModel\ComponentConfiguration\EnvironmentValueHelpers;
use PoP\ComponentModel\ComponentConfiguration\ComponentConfigurationTrait;
use PoPSchema\SchemaCommons\Constants\Behaviors;
class ComponentConfiguration
{
    use ComponentConfigurationTrait;
    /**
     * @var mixed[]
     */
    private static $getCustomPostMetaEntries = [];
    /**
     * @var string
     */
    private static $getCustomPostMetaBehavior = Behaviors::ALLOWLIST;
    public static function getCustomPostMetaEntries() : array
    {
        // Define properties
        $envVariable = \PoPSchema\CustomPostMeta\Environment::CUSTOMPOST_META_ENTRIES;
        $selfProperty =& self::$getCustomPostMetaEntries;
        $defaultValue = [];
        $callback = [EnvironmentValueHelpers::class, 'commaSeparatedStringToArray'];
        // Initialize property from the environment/hook
        self::maybeInitializeConfigurationValue($envVariable, $selfProperty, $defaultValue, $callback);
        return $selfProperty;
    }
    public static function getCustomPostMetaBehavior() : string
    {
        // Define properties
        $envVariable = \PoPSchema\CustomPostMeta\Environment::CUSTOMPOST_META_BEHAVIOR;
        $selfProperty =& self::$getCustomPostMetaBehavior;
        $defaultValue = Behaviors::ALLOWLIST;
        // Initialize property from the environment/hook
        self::maybeInitializeConfigurationValue($envVariable, $selfProperty, $defaultValue);
        return $selfProperty;
    }
}
