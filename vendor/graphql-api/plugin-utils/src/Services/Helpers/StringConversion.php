<?php

declare (strict_types=1);
namespace GraphQLAPI\PluginUtils\Services\Helpers;

class StringConversion
{
    /**
     * Convert a string with dashes into camelCase mode
     *
     * @see https://stackoverflow.com/a/2792045
     * @param string $string
     * @param bool $capitalizeFirstCharacter
     */
    public function dashesToCamelCase($string, $capitalizeFirstCharacter = \false) : string
    {
        $str = \str_replace('-', '', \ucwords($string, '-'));
        if (!$capitalizeFirstCharacter) {
            $str = \lcfirst($str);
        }
        return $str;
    }
}
