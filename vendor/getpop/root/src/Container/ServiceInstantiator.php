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
     * @var AutomaticallyInstantiatedServiceInterface[]
     */
    protected $services = [];
    /**
     * @param \PoP\Root\Services\AutomaticallyInstantiatedServiceInterface $service
     */
    public function addService($service) : void
    {
        $this->services[] = $service;
    }
    /**
     * The SystemContainer requires no events => pass null
     * The ApplicationContainer has 4 events (moduleLoaded, preBoot, boot, afterBoot)
     * @param string|null $event
     */
    public function initializeServices($event = null) : void
    {
        $servicesForEvent = $this->services;
        /**
         * For ApplicationContainer:
         * Filter all the services that must be instantiated during the passed event
         */
        if ($event !== null) {
            $servicesForEvent = \array_filter($this->services, function ($service) use($event) {
                return $service->getInstantiationEvent() === $event;
            });
        }
        foreach ($servicesForEvent as $service) {
            if ($service->isServiceEnabled()) {
                $service->initialize();
            }
        }
    }
}
