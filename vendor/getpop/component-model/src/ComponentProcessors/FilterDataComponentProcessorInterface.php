<?php

declare (strict_types=1);
namespace PoP\ComponentModel\ComponentProcessors;

use PoP\ComponentModel\Component\Component;
interface FilterDataComponentProcessorInterface
{
    /**
     * @return Component[]
     * @param \PoP\ComponentModel\Component\Component $component
     */
    public function getDataloadQueryArgsFilteringComponents($component) : array;
    /**
     * @return Component[]
     * @param array<string,mixed>|null $source
     * @param \PoP\ComponentModel\Component\Component $component
     */
    public function getActiveDataloadQueryArgsFilteringComponents($component, $source = null) : array;
    /**
     * @param array<string,mixed> $query
     * @param array<string,mixed>|null $source
     * @param \PoP\ComponentModel\Component\Component $component
     */
    public function filterHeadcomponentDataloadQueryArgs($component, &$query, $source = null) : void;
}
