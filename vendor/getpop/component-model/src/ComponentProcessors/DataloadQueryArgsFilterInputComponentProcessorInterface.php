<?php

declare (strict_types=1);
namespace PoP\ComponentModel\ComponentProcessors;

use PoP\ComponentModel\Component\Component;
use PoP\ComponentModel\FilterInputs\FilterInputInterface;
interface DataloadQueryArgsFilterInputComponentProcessorInterface extends \PoP\ComponentModel\ComponentProcessors\FilterInputComponentProcessorInterface
{
    /**
     * @param \PoP\ComponentModel\Component\Component $component
     */
    public function getFilterInput($component) : ?FilterInputInterface;
}
