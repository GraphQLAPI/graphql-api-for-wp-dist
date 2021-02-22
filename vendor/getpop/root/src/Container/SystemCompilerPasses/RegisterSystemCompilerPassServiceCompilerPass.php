<?php

declare (strict_types=1);
namespace PoP\Root\Container\SystemCompilerPasses;

use PoP\Root\Container\CompilerPasses\AbstractInjectServiceIntoRegistryCompilerPass;
use PoP\Root\Registries\CompilerPassRegistryInterface;
use PrefixedByPoP\Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
class RegisterSystemCompilerPassServiceCompilerPass extends \PoP\Root\Container\CompilerPasses\AbstractInjectServiceIntoRegistryCompilerPass
{
    protected function getRegistryServiceDefinition() : string
    {
        return \PoP\Root\Registries\CompilerPassRegistryInterface::class;
    }
    protected function getServiceClass() : string
    {
        return \PrefixedByPoP\Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface::class;
    }
    protected function getRegistryMethodCallName() : string
    {
        return 'addCompilerPass';
    }
}
