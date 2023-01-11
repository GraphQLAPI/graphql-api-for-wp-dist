<?php

declare (strict_types=1);
namespace PoP\ComponentRouting;

use PoP\ComponentModel\Component\Component;
interface ComponentRoutingProcessorManagerInterface
{
    /**
     * @param \PoP\ComponentRouting\ComponentRoutingProcessorInterface $processor
     */
    public function addComponentRoutingProcessor($processor) : void;
    /**
     * @return ComponentRoutingProcessorInterface[]
     * @param string|null $group
     */
    public function getComponentRoutingProcessors($group = null) : array;
    public function getDefaultGroup() : string;
    /**
     * @param string|null $group
     */
    public function getRoutingComponentByMostAllMatchingStateProperties($group = null) : ?Component;
}
