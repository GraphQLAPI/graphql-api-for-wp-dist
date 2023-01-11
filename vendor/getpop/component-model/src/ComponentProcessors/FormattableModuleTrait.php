<?php

declare (strict_types=1);
namespace PoP\ComponentModel\ComponentProcessors;

use PoP\ComponentModel\Component\Component;
trait FormattableModuleTrait
{
    /**
     * @param \PoP\ComponentModel\Component\Component $component
     */
    public function getFormat($component) : ?string
    {
        return null;
    }
}
