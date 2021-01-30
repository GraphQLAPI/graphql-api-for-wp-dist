<?php

declare (strict_types=1);
namespace GraphQLByPoP\GraphQLRequest;

use PoP\ComponentModel\ComponentConfiguration\EnvironmentValueHelpers;
use PoP\ComponentModel\ComponentConfiguration\ComponentConfigurationTrait;
class ComponentConfiguration
{
    use ComponentConfigurationTrait;
    private static $disableGraphQLAPIForPoP;
    private static $enableMultipleQueryExecution;
    public static function disableGraphQLAPIForPoP() : bool
    {
        // Define properties
        $envVariable = \GraphQLByPoP\GraphQLRequest\Environment::DISABLE_GRAPHQL_API_FOR_POP;
        $selfProperty =& self::$disableGraphQLAPIForPoP;
        $defaultValue = \false;
        $callback = [\PoP\ComponentModel\ComponentConfiguration\EnvironmentValueHelpers::class, 'toBool'];
        // Initialize property from the environment/hook
        self::maybeInitializeConfigurationValue($envVariable, $selfProperty, $defaultValue, $callback);
        return $selfProperty;
    }
    public static function enableMultipleQueryExecution() : bool
    {
        // Define properties
        $envVariable = \GraphQLByPoP\GraphQLRequest\Environment::ENABLE_MULTIPLE_QUERY_EXECUTION;
        $selfProperty =& self::$enableMultipleQueryExecution;
        $defaultValue = \false;
        $callback = [\PoP\ComponentModel\ComponentConfiguration\EnvironmentValueHelpers::class, 'toBool'];
        // Initialize property from the environment/hook
        self::maybeInitializeConfigurationValue($envVariable, $selfProperty, $defaultValue, $callback);
        return $selfProperty;
    }
}
