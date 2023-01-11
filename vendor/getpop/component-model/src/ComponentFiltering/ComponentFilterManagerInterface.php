<?php

declare (strict_types=1);
namespace PoP\ComponentModel\ComponentFiltering;

use PoP\ComponentModel\Component\Component;
use PoP\ComponentModel\ComponentFilters\ComponentFilterInterface;
interface ComponentFilterManagerInterface
{
    /**
     * @param \PoP\ComponentModel\ComponentFilters\ComponentFilterInterface $componentFilter
     */
    public function addComponentFilter($componentFilter) : void;
    public function getSelectedComponentFilterName() : ?string;
    /**
     * @param string $selectedComponentFilterName
     */
    public function setSelectedComponentFilterName($selectedComponentFilterName) : void;
    /**
     * @return array<mixed[]>|null
     */
    public function getNotExcludedComponentSets() : ?array;
    /**
     * @param bool $neverExclude
     */
    public function setNeverExclude($neverExclude) : void;
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
     * The `prepare` function advances the componentPath one level down, when interating into the subcomponents, and then calling `restore` the value goes one level up again
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
