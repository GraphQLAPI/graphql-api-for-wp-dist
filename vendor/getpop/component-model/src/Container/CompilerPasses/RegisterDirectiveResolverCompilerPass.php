<?php

declare (strict_types=1);
namespace PoP\ComponentModel\Container\CompilerPasses;

use PoP\ComponentModel\ComponentConfiguration;
use PoP\ComponentModel\DirectiveResolvers\DirectiveResolverInterface;
use PoP\ComponentModel\Registries\DirectiveRegistryInterface;
use PoP\Root\Container\CompilerPasses\AbstractInjectServiceIntoRegistryCompilerPass;
class RegisterDirectiveResolverCompilerPass extends \PoP\Root\Container\CompilerPasses\AbstractInjectServiceIntoRegistryCompilerPass
{
    protected function getRegistryServiceDefinition() : string
    {
        return \PoP\ComponentModel\Registries\DirectiveRegistryInterface::class;
    }
    protected function getServiceClass() : string
    {
        return \PoP\ComponentModel\DirectiveResolvers\DirectiveResolverInterface::class;
    }
    protected function getRegistryMethodCallName() : string
    {
        return 'addDirectiveResolver';
    }
    protected function enabled() : bool
    {
        return \PoP\ComponentModel\ComponentConfiguration::enableSchemaEntityRegistries();
    }
}
