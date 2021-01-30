<?php

declare (strict_types=1);
namespace PoPSchema\Users\Config;

use PoP\API\Component;
use PoP\Root\Component\PHPServiceConfigurationTrait;
use PoP\ComponentModel\Container\ContainerBuilderUtils;
use PoP\ModuleRouting\RouteModuleProcessorManagerInterface;
class ServiceConfiguration
{
    use PHPServiceConfigurationTrait;
    protected static function configure() : void
    {
        // Load API and RESTAPI conditional classes
        if (\class_exists('\\PoP\\API\\Component') && \PoP\API\Component::isEnabled()) {
            \PoP\ComponentModel\Container\ContainerBuilderUtils::injectServicesIntoService(\PoP\ModuleRouting\RouteModuleProcessorManagerInterface::class, 'PoPSchema\\Users\\Conditional\\API\\RouteModuleProcessors', 'add');
        }
        if (\class_exists('\\PoP\\RESTAPI\\Component') && \PoP\RESTAPI\Component::isEnabled()) {
            \PoP\ComponentModel\Container\ContainerBuilderUtils::injectServicesIntoService(\PoP\ModuleRouting\RouteModuleProcessorManagerInterface::class, 'PoPSchema\\Users\\Conditional\\RESTAPI\\RouteModuleProcessors', 'add');
        }
    }
}
