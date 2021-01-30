<?php

declare (strict_types=1);
namespace PoP\ComponentModel\Config;

use PoP\ComponentModel\Configuration\Request;
use PoP\Definitions\DefinitionManagerInterface;
use PoP\Root\Component\PHPServiceConfigurationTrait;
use PoP\ComponentModel\Container\ContainerBuilderUtils;
use PoP\ComponentModel\Engine\DataloadingEngineInterface;
use PoP\ComponentModel\DirectiveResolvers\ValidateDirectiveResolver;
use PoP\ComponentModel\ModuleFiltering\ModuleFilterManagerInterface;
use PoP\ComponentModel\DirectiveResolvers\ResolveValueAndMergeDirectiveResolver;
class ServiceConfiguration
{
    use PHPServiceConfigurationTrait;
    protected static function configure() : void
    {
        // If `isMangled`, disable the definitions
        if (!\PoP\ComponentModel\Configuration\Request::isMangled()) {
            \PoP\ComponentModel\Container\ContainerBuilderUtils::injectValuesIntoService(\PoP\Definitions\DefinitionManagerInterface::class, 'setEnabled', \false);
        }
        // Add ModuleFilters to the ModuleFilterManager
        \PoP\ComponentModel\Container\ContainerBuilderUtils::injectServicesIntoService(\PoP\ComponentModel\ModuleFiltering\ModuleFilterManagerInterface::class, 'PoP\\ComponentModel\\ModuleFilters', 'add');
        // Inject the mandatory root directives
        \PoP\ComponentModel\Container\ContainerBuilderUtils::injectValuesIntoService(\PoP\ComponentModel\Engine\DataloadingEngineInterface::class, 'addMandatoryDirectiveClasses', [\PoP\ComponentModel\DirectiveResolvers\ValidateDirectiveResolver::class, \PoP\ComponentModel\DirectiveResolvers\ResolveValueAndMergeDirectiveResolver::class]);
    }
}
