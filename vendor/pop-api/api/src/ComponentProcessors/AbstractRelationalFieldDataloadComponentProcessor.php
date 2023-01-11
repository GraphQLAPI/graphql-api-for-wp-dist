<?php

declare (strict_types=1);
namespace PoPAPI\API\ComponentProcessors;

use PoP\ComponentModel\Component\Component;
use PoPAPI\API\Constants\Formats;
use PoP\ComponentModel\ComponentProcessors\AbstractDataloadComponentProcessor;
abstract class AbstractRelationalFieldDataloadComponentProcessor extends AbstractDataloadComponentProcessor
{
    /**
     * @return Component[]
     * @param \PoP\ComponentModel\Component\Component $component
     */
    protected function getInnerSubcomponents($component) : array
    {
        $ret = parent::getInnerSubcomponents($component);
        $ret[] = $this->getRelationalFieldInnerComponent($component);
        return $ret;
    }
    /**
     * @param \PoP\ComponentModel\Component\Component $component
     */
    protected function getRelationalFieldInnerComponent($component) : Component
    {
        /**
         * The fields to retrieve are passed through component atts,
         * so simply transfer all component atts down the line
         */
        return new Component(\PoPAPI\API\ComponentProcessors\RelationalFieldQueryDataComponentProcessor::class, \PoPAPI\API\ComponentProcessors\RelationalFieldQueryDataComponentProcessor::COMPONENT_LAYOUT_RELATIONALFIELDS, $component->atts);
    }
    /**
     * @param \PoP\ComponentModel\Component\Component $component
     */
    public function getFormat($component) : ?string
    {
        return Formats::FIELDS;
    }
}
