<?php

declare (strict_types=1);
namespace PoP\Root\Container;

use PoP\Root\Services\AutomaticallyInstantiatedServiceInterface;
/**
 * Collect the services that must be automatically instantiated,
 * i.e. that no piece of code will explicitly reference, but whose
 * services must always be executed. Eg: hooks.
 */
interface ServiceInstantiatorInterface
{
    /**
     * @param \PoP\Root\Services\AutomaticallyInstantiatedServiceInterface $service
     */
    public function addService($service) : void;
    /**
     * The SystemContainer requires no events => pass null
     * The ApplicationContainer has 3 events (moduleLoaded, boot, afterBoot)
     * @param string|null $event
     */
    public function initializeServices($event = null) : void;
}
