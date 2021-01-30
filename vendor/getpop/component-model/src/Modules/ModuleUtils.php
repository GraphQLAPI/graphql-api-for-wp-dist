<?php

declare (strict_types=1);
namespace PoP\ComponentModel\Modules;

use PoP\ComponentModel\ItemProcessors\ProcessorItemUtils;
class ModuleUtils
{
    public static function getModuleFullName(array $module) : string
    {
        return \PoP\ComponentModel\ItemProcessors\ProcessorItemUtils::getItemFullName($module);
    }
    public static function getModuleFromFullName(string $moduleFullName) : ?array
    {
        return \PoP\ComponentModel\ItemProcessors\ProcessorItemUtils::getItemFromFullName($moduleFullName);
    }
    public static function getModuleOutputName(array $module) : string
    {
        return \PoP\ComponentModel\ItemProcessors\ProcessorItemUtils::getItemOutputName($module, \PoP\ComponentModel\Modules\DefinitionGroups::MODULES);
    }
    public static function getModuleFromOutputName(string $moduleOutputName) : ?array
    {
        return \PoP\ComponentModel\ItemProcessors\ProcessorItemUtils::getItemFromOutputName($moduleOutputName, \PoP\ComponentModel\Modules\DefinitionGroups::MODULES);
    }
}
