<?php

declare (strict_types=1);
namespace PoP\Engine\Config;

use PoP\Engine\ComponentConfiguration;
use PoP\Root\Component\PHPServiceConfigurationTrait;
use PoP\ComponentModel\Container\ContainerBuilderUtils;
use PoP\CacheControl\Component as CacheControlComponent;
use PoP\ComponentModel\Engine\DataloadingEngineInterface;
use PoP\ModuleRouting\RouteModuleProcessorManagerInterface;
use PoP\ComponentModel\DataStructure\DataStructureManagerInterface;
use PoP\ComponentModel\ModuleFiltering\ModuleFilterManagerInterface;
use PoP\CacheControl\DirectiveResolvers\CacheControlDirectiveResolver;
use PoP\Engine\DirectiveResolvers\SetSelfAsExpressionDirectiveResolver;
class ServiceConfiguration
{
    use PHPServiceConfigurationTrait;
    protected static function configure() : void
    {
        // Add ModuleFilters to the ModuleFilterManager
        \PoP\ComponentModel\Container\ContainerBuilderUtils::injectServicesIntoService(\PoP\ComponentModel\ModuleFiltering\ModuleFilterManagerInterface::class, 'PoP\\Engine\\ModuleFilters', 'add');
        // Add RouteModuleProcessors to the Manager
        \PoP\ComponentModel\Container\ContainerBuilderUtils::injectServicesIntoService(\PoP\ModuleRouting\RouteModuleProcessorManagerInterface::class, 'PoP\\Engine\\RouteModuleProcessors', 'add');
        \PoP\ComponentModel\Container\ContainerBuilderUtils::injectServicesIntoService(\PoP\ComponentModel\DataStructure\DataStructureManagerInterface::class, 'PoP\\Engine\\DataStructureFormatters', 'add');
        // Inject the mandatory root directives
        \PoP\ComponentModel\Container\ContainerBuilderUtils::injectValuesIntoService(\PoP\ComponentModel\Engine\DataloadingEngineInterface::class, 'addMandatoryDirectiveClass', \PoP\Engine\DirectiveResolvers\SetSelfAsExpressionDirectiveResolver::class);
        if (\PoP\Engine\ComponentConfiguration::addMandatoryCacheControlDirective()) {
            static::configureCacheControl();
        }
    }
    public static function configureCacheControl()
    {
        if (\PoP\CacheControl\Component::isEnabled() && $_SERVER['REQUEST_METHOD'] == 'GET') {
            \PoP\ComponentModel\Container\ContainerBuilderUtils::injectValuesIntoService(\PoP\ComponentModel\Engine\DataloadingEngineInterface::class, 'addMandatoryDirectives', [\PoP\CacheControl\DirectiveResolvers\CacheControlDirectiveResolver::getDirectiveName()]);
        }
    }
}
