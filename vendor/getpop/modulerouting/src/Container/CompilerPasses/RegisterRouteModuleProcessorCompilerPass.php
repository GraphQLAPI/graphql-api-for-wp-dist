<?php

declare (strict_types=1);
namespace PoP\ModuleRouting\Container\CompilerPasses;

use PoP\ModuleRouting\AbstractRouteModuleProcessor;
use PoP\ModuleRouting\RouteModuleProcessorManagerInterface;
use PoP\Root\Container\CompilerPasses\AbstractInjectServiceIntoRegistryCompilerPass;
class RegisterRouteModuleProcessorCompilerPass extends \PoP\Root\Container\CompilerPasses\AbstractInjectServiceIntoRegistryCompilerPass
{
    protected function getRegistryServiceDefinition() : string
    {
        return \PoP\ModuleRouting\RouteModuleProcessorManagerInterface::class;
    }
    protected function getServiceClass() : string
    {
        return \PoP\ModuleRouting\AbstractRouteModuleProcessor::class;
    }
    protected function getRegistryMethodCallName() : string
    {
        return 'addRouteModuleProcessor';
    }
}
