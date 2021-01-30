<?php

declare (strict_types=1);
namespace PoP\ModuleRouting\Facades;

use PoP\ModuleRouting\RouteModuleProcessorManagerInterface;
use PoP\Root\Container\ContainerBuilderFactory;
class RouteModuleProcessorManagerFacade
{
    public static function getInstance() : \PoP\ModuleRouting\RouteModuleProcessorManagerInterface
    {
        /**
         * @var RouteModuleProcessorManagerInterface
         */
        $service = \PoP\Root\Container\ContainerBuilderFactory::getInstance()->get(\PoP\ModuleRouting\RouteModuleProcessorManagerInterface::class);
        return $service;
    }
}
