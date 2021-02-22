<?php

declare (strict_types=1);
namespace PoP\Root\Container;

use PoP\Root\Services\AutomaticallyInstantiatedServiceInterface;
/**
 * Collect the services that must be automatically instantiated,
 * i.e. that no piece of code will explicitly reference, but whose
 * services must always be executed. Eg: hooks.
 */
class ServiceInstantiator implements \PoP\Root\Container\ServiceInstantiatorInterface
{
    /**
     * @var string[]
     */
    protected $serviceDefinitions = [];
    public function addServiceDefinition(string $serviceDefinition) : void
    {
        $this->serviceDefinitions[] = $serviceDefinition;
    }
    public function initializeServices() : void
    {
        $containerBuilder = \PoP\Root\Container\ContainerBuilderFactory::getInstance();
        foreach ($this->serviceDefinitions as $serviceDefinition) {
            /** @var AutomaticallyInstantiatedServiceInterface */
            $service = $containerBuilder->get($serviceDefinition);
            $service->initialize();
        }
    }
}
