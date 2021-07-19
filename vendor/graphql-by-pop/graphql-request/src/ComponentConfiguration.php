<?php

declare (strict_types=1);
namespace GraphQLByPoP\GraphQLRequest;

use PoP\ComponentModel\ComponentConfiguration\EnvironmentValueHelpers;
use PoP\ComponentModel\ComponentConfiguration\ComponentConfigurationTrait;
class ComponentConfiguration
{
    use ComponentConfigurationTrait;
    /**
     * @var bool
     */
    private static $disableGraphQLAPIForPoP = \false;
    /**
     * @var bool
     */
    private static $enableMultipleQueryExecution = \false;
    public static function disableGraphQLAPIForPoP() : bool
    {
        // Define properties
        $envVariable = \GraphQLByPoP\GraphQLRequest\Environment::DISABLE_GRAPHQL_API_FOR_POP;
        $selfProperty =& self::$disableGraphQLAPIForPoP;
        $defaultValue = \false;
        $callback = [EnvironmentValueHelpers::class, 'toBool'];
        // Initialize property from the environment/hook
        self::maybeInitializeConfigurationValue($envVariable, $selfProperty, $defaultValue, $callback);
        return $selfProperty;
    }
    /**
     * Disable hook, because it is invoked by `export-directive`
     * on its Component's `resolveEnabled` function.
     */
    public static function enableMultipleQueryExecution() : bool
    {
        // Define properties
        $envVariable = \GraphQLByPoP\GraphQLRequest\Environment::ENABLE_MULTIPLE_QUERY_EXECUTION;
        $selfProperty =& self::$enableMultipleQueryExecution;
        $defaultValue = \false;
        $callback = [EnvironmentValueHelpers::class, 'toBool'];
        // Initialize property from the environment
        self::maybeInitializeConfigurationValue($envVariable, $selfProperty, $defaultValue, $callback);
        return $selfProperty;
    }
}
