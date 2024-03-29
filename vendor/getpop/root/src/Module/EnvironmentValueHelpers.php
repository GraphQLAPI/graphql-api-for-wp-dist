<?php

declare (strict_types=1);
namespace PoP\Root\Module;

/**
 * Helpers to convert environment values
 */
class EnvironmentValueHelpers
{
    /**
     * Convert the environment value from string to boolean.
     * A string is true if its lowercase value is either "true" or "on", or if it's "1"
     *
     * @param string $value environment value
     */
    public static function toBool($value) : bool
    {
        return \in_array(\strtolower($value), ['true', 'on', '1']);
    }
    /**
     * Convert the environment value from string to int
     *
     * @param string $value environment value
     */
    public static function toInt($value) : int
    {
        return (int) $value;
    }
    /**
     * Convert the environment value from a comma separated string to array
     *
     * @param string $value environment value
     * @return string[]
     */
    public static function commaSeparatedStringToArray($value) : array
    {
        return \array_map(\Closure::fromCallable('trim'), \explode(',', $value));
    }
}
