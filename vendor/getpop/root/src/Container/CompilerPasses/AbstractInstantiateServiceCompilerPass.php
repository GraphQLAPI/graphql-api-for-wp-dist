<?php

declare (strict_types=1);
namespace PoP\Root\Container\CompilerPasses;

use PoP\Root\Container\ContainerBuilderWrapperInterface;
use PoP\Root\Container\ServiceInstantiatorInterface;
abstract class AbstractInstantiateServiceCompilerPass extends \PoP\Root\Container\CompilerPasses\AbstractCompilerPass
{
    use \PoP\Root\Container\CompilerPasses\AutoconfigurableServicesCompilerPassTrait;
    /**
     * @param \PoP\Root\Container\ContainerBuilderWrapperInterface $containerBuilderWrapper
     */
    protected function doProcess($containerBuilderWrapper) : void
    {
        $serviceInstantiatorDefinition = $containerBuilderWrapper->getDefinition(ServiceInstantiatorInterface::class);
        $serviceClass = $this->getServiceClass();
        $definitions = $containerBuilderWrapper->getDefinitions();
        foreach ($definitions as $definitionID => $definition) {
            $definitionClass = $definition->getClass();
            if ($definitionClass === null || !\is_a($definitionClass, $serviceClass, \true)) {
                continue;
            }
            $onlyProcessAutoconfiguredServices = $this->onlyProcessAutoconfiguredServices();
            if (!$onlyProcessAutoconfiguredServices || $definition->isAutoconfigured()) {
                $serviceInstantiatorDefinition->addMethodCall('addService', [$this->createReference($definitionID)]);
            }
        }
    }
    protected abstract function getServiceClass() : string;
}
