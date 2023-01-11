<?php

declare (strict_types=1);
namespace PoP\ComponentModel\ComponentFilters;

use PoP\ComponentModel\Component\Component;
interface ComponentFilterInterface
{
    public function getName() : string;
    /**
     * @param array<string,mixed> $props
     * @param \PoP\ComponentModel\Component\Component $component
     */
    public function excludeSubcomponent($component, &$props) : bool;
    /**
     * @param Component[] $subcomponents
     * @return Component[]
     * @param \PoP\ComponentModel\Component\Component $component
     */
    public function removeExcludedSubcomponents($component, $subcomponents) : array;
    /**
     * @param array<string,mixed> $props
     * @param \PoP\ComponentModel\Component\Component $component
     */
    public function prepareForPropagation($component, &$props) : void;
    /**
     * @param array<string,mixed> $props
     * @param \PoP\ComponentModel\Component\Component $component
     */
    public function restoreFromPropagation($component, &$props) : void;
}
