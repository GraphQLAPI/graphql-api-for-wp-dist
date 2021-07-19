<?php

declare (strict_types=1);
namespace PoP\Root\Container\CompilerPasses;

use PoP\Root\Container\ContainerBuilderWrapperInterface;
abstract class AbstractInjectServiceIntoRegistryCompilerPass extends \PoP\Root\Container\CompilerPasses\AbstractCompilerPass
{
    use AutoconfigurableServicesCompilerPassTrait;
    protected function doProcess(ContainerBuilderWrapperInterface $containerBuilderWrapper) : void
    {
        $registryDefinition = $containerBuilderWrapper->getDefinition($this->getRegistryServiceDefinition());
        $definitions = $containerBuilderWrapper->getDefinitions();
        $serviceClass = $this->getServiceClass();
        foreach ($definitions as $definitionID => $definition) {
            $definitionClass = $definition->getClass();
            if ($definitionClass === null || !\is_a($definitionClass, $serviceClass, \true)) {
                continue;
            }
            $onlyProcessAutoconfiguredServices = $this->onlyProcessAutoconfiguredServices();
            if (!$onlyProcessAutoconfiguredServices || $definition->isAutoconfigured()) {
                // Register the service in the corresponding registry
                $registryDefinition->addMethodCall($this->getRegistryMethodCallName(), [$this->createReference($definitionID), $definitionID]);
            }
        }
    }
    protected abstract function getRegistryServiceDefinition() : string;
    protected abstract function getServiceClass() : string;
    protected abstract function getRegistryMethodCallName() : string;
}
