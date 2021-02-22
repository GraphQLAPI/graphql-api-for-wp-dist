<?php

declare (strict_types=1);
namespace PoP\Root\Container\CompilerPasses;

use PoP\Root\Container\ServiceInstantiatorInterface;
use PrefixedByPoP\Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use PrefixedByPoP\Symfony\Component\DependencyInjection\ContainerBuilder;
abstract class AbstractInstantiateServiceCompilerPass implements \PrefixedByPoP\Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface
{
    public function process(\PrefixedByPoP\Symfony\Component\DependencyInjection\ContainerBuilder $containerBuilder) : void
    {
        $serviceInstantiatorDefinition = $containerBuilder->getDefinition(\PoP\Root\Container\ServiceInstantiatorInterface::class);
        $serviceClass = $this->getServiceClass();
        $definitions = $containerBuilder->getDefinitions();
        foreach ($definitions as $definitionID => $definition) {
            $definitionClass = $definition->getClass();
            if ($definitionClass === null || !\is_a($definitionClass, $serviceClass, \true)) {
                continue;
            }
            $serviceInstantiatorDefinition->addMethodCall('addServiceDefinition', [$definitionID]);
        }
    }
    protected abstract function getServiceClass() : string;
}
