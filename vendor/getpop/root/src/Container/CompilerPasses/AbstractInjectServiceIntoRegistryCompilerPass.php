<?php

declare (strict_types=1);
namespace PoP\Root\Container\CompilerPasses;

use PrefixedByPoP\Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use PrefixedByPoP\Symfony\Component\DependencyInjection\ContainerBuilder;
use PrefixedByPoP\Symfony\Component\DependencyInjection\Reference;
abstract class AbstractInjectServiceIntoRegistryCompilerPass implements \PrefixedByPoP\Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface
{
    public function process(\PrefixedByPoP\Symfony\Component\DependencyInjection\ContainerBuilder $containerBuilder) : void
    {
        if (!$this->enabled()) {
            return;
        }
        $registryDefinition = $containerBuilder->getDefinition($this->getRegistryServiceDefinition());
        $definitions = $containerBuilder->getDefinitions();
        $serviceClass = $this->getServiceClass();
        foreach ($definitions as $definitionID => $definition) {
            $definitionClass = $definition->getClass();
            if ($definitionClass === null || !\is_a($definitionClass, $serviceClass, \true)) {
                continue;
            }
            // Register the service in the corresponding registry
            $registryDefinition->addMethodCall($this->getRegistryMethodCallName(), [new \PrefixedByPoP\Symfony\Component\DependencyInjection\Reference($definitionID)]);
        }
    }
    protected abstract function getRegistryServiceDefinition() : string;
    protected abstract function getServiceClass() : string;
    protected abstract function getRegistryMethodCallName() : string;
    protected function enabled() : bool
    {
        return \true;
    }
}
