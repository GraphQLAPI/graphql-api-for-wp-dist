<?php

declare (strict_types=1);
namespace PoP\ComponentModel\Container\CompilerPasses;

use PoP\ComponentModel\ComponentConfiguration;
use PoP\ComponentModel\FieldInterfaceResolvers\FieldInterfaceResolverInterface;
use PoP\ComponentModel\Registries\FieldInterfaceRegistryInterface;
use PoP\Root\Container\CompilerPasses\AbstractInjectServiceIntoRegistryCompilerPass;
class RegisterFieldInterfaceResolverCompilerPass extends \PoP\Root\Container\CompilerPasses\AbstractInjectServiceIntoRegistryCompilerPass
{
    protected function getRegistryServiceDefinition() : string
    {
        return \PoP\ComponentModel\Registries\FieldInterfaceRegistryInterface::class;
    }
    protected function getServiceClass() : string
    {
        return \PoP\ComponentModel\FieldInterfaceResolvers\FieldInterfaceResolverInterface::class;
    }
    protected function getRegistryMethodCallName() : string
    {
        return 'addFieldInterfaceResolver';
    }
    protected function enabled() : bool
    {
        return \PoP\ComponentModel\ComponentConfiguration::enableSchemaEntityRegistries();
    }
}
