<?php

declare (strict_types=1);
namespace PoP\ComponentModel\ComponentHelpers;

use PoP\ComponentModel\Component\Component;
interface ComponentHelpersInterface
{
    /**
     * @param \PoP\ComponentModel\Component\Component $component
     */
    public function getComponentFullName($component) : string;
    /**
     * @param string $componentFullName
     */
    public function getComponentFromFullName($componentFullName) : ?Component;
    /**
     * @param \PoP\ComponentModel\Component\Component $component
     */
    public function getComponentOutputName($component) : string;
    /**
     * @param string $componentOutputName
     */
    public function getComponentFromOutputName($componentOutputName) : ?Component;
}
