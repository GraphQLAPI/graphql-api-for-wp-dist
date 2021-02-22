<?php

declare (strict_types=1);
namespace PoP\ComponentModel\Container\CompilerPasses;

use PoP\ComponentModel\ModuleFiltering\ModuleFilterManagerInterface;
use PoP\ComponentModel\ModuleFilters\ModuleFilterInterface;
use PoP\Root\Container\CompilerPasses\AbstractInjectServiceIntoRegistryCompilerPass;
class RegisterModuleFilterCompilerPass extends \PoP\Root\Container\CompilerPasses\AbstractInjectServiceIntoRegistryCompilerPass
{
    protected function getRegistryServiceDefinition() : string
    {
        return \PoP\ComponentModel\ModuleFiltering\ModuleFilterManagerInterface::class;
    }
    protected function getServiceClass() : string
    {
        return \PoP\ComponentModel\ModuleFilters\ModuleFilterInterface::class;
    }
    protected function getRegistryMethodCallName() : string
    {
        return 'addModuleFilter';
    }
}
