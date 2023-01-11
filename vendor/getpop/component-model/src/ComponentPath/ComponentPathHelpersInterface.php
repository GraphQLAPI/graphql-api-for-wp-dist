<?php

declare (strict_types=1);
namespace PoP\ComponentModel\ComponentPath;

use PoP\ComponentModel\Component\Component;
interface ComponentPathHelpersInterface
{
    /**
     * @param \PoP\ComponentModel\Component\Component $component
     */
    public function getStringifiedModulePropagationCurrentPath($component) : string;
    /**
     * @param Component[] $componentPath
     */
    public function stringifyComponentPath($componentPath) : string;
    /**
     * @return array<Component|null>
     * @param string $componentPath_as_string
     */
    public function recastComponentPath($componentPath_as_string) : array;
    /**
     * @return array<array<Component|null>>
     */
    public function getComponentPaths() : array;
}
