<?php

declare (strict_types=1);
namespace PoP\ComponentModel\ComponentProcessors;

use PoP\ComponentModel\Component\Component;
interface FormInputComponentProcessorInterface
{
    /**
     * @param array<string,mixed>|null $source
     * @return mixed
     * @param \PoP\ComponentModel\Component\Component $component
     */
    public function getValue($component, $source = null);
    /**
     * @param array<string,mixed> $props
     * @return mixed
     * @param \PoP\ComponentModel\Component\Component $component
     */
    public function getDefaultValue($component, &$props);
    /**
     * @param \PoP\ComponentModel\Component\Component $component
     */
    public function getName($component) : string;
    /**
     * @param \PoP\ComponentModel\Component\Component $component
     */
    public function getInputName($component) : string;
    /**
     * @param \PoP\ComponentModel\Component\Component $component
     */
    public function isMultiple($component) : bool;
    /**
     * @param array<string,mixed>|null $source
     * @return mixed
     * @param \PoP\ComponentModel\Component\Component $component
     */
    public function isInputSetInSource($component, $source = null);
}
