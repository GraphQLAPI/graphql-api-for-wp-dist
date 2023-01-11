<?php

declare (strict_types=1);
namespace PoP\ComponentModel\EntryComponent;

use PoP\ComponentModel\Component\Component;
use PoP\ComponentModel\EntryComponent\EntryComponentManagerInterface;
use PoP\Root\Services\BasicServiceTrait;
use PoP\ComponentRouting\ComponentRoutingGroups;
use PoP\ComponentRouting\ComponentRoutingProcessorManagerInterface;
class EntryComponentManager implements EntryComponentManagerInterface
{
    use BasicServiceTrait;
    /**
     * @var \PoP\ComponentRouting\ComponentRoutingProcessorManagerInterface|null
     */
    private $routeComponentProcessorManager;
    /**
     * @param \PoP\ComponentRouting\ComponentRoutingProcessorManagerInterface $routeComponentProcessorManager
     */
    public final function setComponentRoutingProcessorManager($routeComponentProcessorManager) : void
    {
        $this->routeComponentProcessorManager = $routeComponentProcessorManager;
    }
    protected final function getComponentRoutingProcessorManager() : ComponentRoutingProcessorManagerInterface
    {
        /** @var ComponentRoutingProcessorManagerInterface */
        return $this->routeComponentProcessorManager = $this->routeComponentProcessorManager ?? $this->instanceManager->getInstance(ComponentRoutingProcessorManagerInterface::class);
    }
    public function getEntryComponent() : ?Component
    {
        return $this->getComponentRoutingProcessorManager()->getRoutingComponentByMostAllMatchingStateProperties(ComponentRoutingGroups::ENTRYCOMPONENT);
    }
}
