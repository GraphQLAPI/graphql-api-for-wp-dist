<?php

declare (strict_types=1);
namespace PoPSchema\TaxonomyMeta;

use PoP\ComponentModel\ComponentConfiguration\EnvironmentValueHelpers;
use PoP\ComponentModel\ComponentConfiguration\ComponentConfigurationTrait;
use PoPSchema\SchemaCommons\Constants\Behaviors;
class ComponentConfiguration
{
    use ComponentConfigurationTrait;
    /**
     * @var mixed[]
     */
    private static $getTaxonomyMetaEntries = [];
    /**
     * @var string
     */
    private static $getTaxonomyMetaBehavior = Behaviors::ALLOWLIST;
    public static function getTaxonomyMetaEntries() : array
    {
        // Define properties
        $envVariable = \PoPSchema\TaxonomyMeta\Environment::TAXONOMY_META_ENTRIES;
        $selfProperty =& self::$getTaxonomyMetaEntries;
        $defaultValue = [];
        $callback = [EnvironmentValueHelpers::class, 'commaSeparatedStringToArray'];
        // Initialize property from the environment/hook
        self::maybeInitializeConfigurationValue($envVariable, $selfProperty, $defaultValue, $callback);
        return $selfProperty;
    }
    public static function getTaxonomyMetaBehavior() : string
    {
        // Define properties
        $envVariable = \PoPSchema\TaxonomyMeta\Environment::TAXONOMY_META_BEHAVIOR;
        $selfProperty =& self::$getTaxonomyMetaBehavior;
        $defaultValue = Behaviors::ALLOWLIST;
        // Initialize property from the environment/hook
        self::maybeInitializeConfigurationValue($envVariable, $selfProperty, $defaultValue);
        return $selfProperty;
    }
}
