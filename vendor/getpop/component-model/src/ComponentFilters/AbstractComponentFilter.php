<?php

declare (strict_types=1);
namespace PoP\ComponentModel\ComponentFilters;

use PoP\ComponentModel\Component\Component;
use PoP\ComponentModel\ComponentProcessors\ComponentProcessorManagerInterface;
use PoP\Root\Services\BasicServiceTrait;
abstract class AbstractComponentFilter implements \PoP\ComponentModel\ComponentFilters\ComponentFilterInterface
{
    use BasicServiceTrait;
    /**
     * @var \PoP\ComponentModel\ComponentProcessors\ComponentProcessorManagerInterface|null
     */
    private $componentProcessorManager;
    /**
     * @param \PoP\ComponentModel\ComponentProcessors\ComponentProcessorManagerInterface $componentProcessorManager
     */
    public final function setComponentProcessorManager($componentProcessorManager) : void
    {
        $this->componentProcessorManager = $componentProcessorManager;
    }
    protected final function getComponentProcessorManager() : ComponentProcessorManagerInterface
    {
        /** @var ComponentProcessorManagerInterface */
        return $this->componentProcessorManager = $this->componentProcessorManager ?? $this->instanceManager->getInstance(ComponentProcessorManagerInterface::class);
    }
    /**
     * @param array<string,mixed> $props
     * @param \PoP\ComponentModel\Component\Component $component
     */
    public function excludeSubcomponent($component, &$props) : bool
    {
        return \false;
    }
    /**
     * @param Component[] $subcomponents
     * @return Component[]
     * @param \PoP\ComponentModel\Component\Component $component
     */
    public function removeExcludedSubcomponents($component, $subcomponents) : array
    {
        return $subcomponents;
    }
    /**
     * @param array<string,mixed> $props
     * @param \PoP\ComponentModel\Component\Component $component
     */
    public function prepareForPropagation($component, &$props) : void
    {
    }
    /**
     * @param array<string,mixed> $props
     * @param \PoP\ComponentModel\Component\Component $component
     */
    public function restoreFromPropagation($component, &$props) : void
    {
    }
}
