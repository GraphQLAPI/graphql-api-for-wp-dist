<?php

declare (strict_types=1);
namespace PoP\Root\Module;

/**
 * Helpers for the ModuleConfiguration class
 */
class ModuleConfigurationHelpers
{
    /**
     * @param string $class
     * @param string $envVariable
     */
    public static function getHookName($class, $envVariable) : string
    {
        return \sprintf('%s:configuration:%s', $class, $envVariable);
    }
}
