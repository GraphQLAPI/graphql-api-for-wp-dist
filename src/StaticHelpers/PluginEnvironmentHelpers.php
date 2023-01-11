<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\StaticHelpers;

class PluginEnvironmentHelpers
{
    /**
     * Determine if the environment variable was defined
     * as a constant in wp-config.php
     * @return mixed
     * @param string $envVariable
     */
    public static function getWPConfigConstantValue($envVariable)
    {
        return constant(self::getWPConfigConstantName($envVariable));
    }

    /**
     * Determine if the environment variable was defined
     * as a constant in wp-config.php
     * @param string $envVariable
     */
    public static function isWPConfigConstantDefined($envVariable): bool
    {
        return defined(self::getWPConfigConstantName($envVariable));
    }

    /**
     * Constants defined in wp-config.php must start with this prefix
     * to override GraphQL API environment variables
     * @param string $envVariable
     */
    public static function getWPConfigConstantName($envVariable): string
    {
        return 'GRAPHQL_API_' . $envVariable;
    }
}
