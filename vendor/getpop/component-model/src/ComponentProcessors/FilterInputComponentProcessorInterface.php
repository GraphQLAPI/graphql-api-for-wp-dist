<?php

declare (strict_types=1);
namespace PoP\ComponentModel\ComponentProcessors;

use PoP\ComponentModel\Component\Component;
use PoP\ComponentModel\TypeResolvers\InputTypeResolverInterface;
interface FilterInputComponentProcessorInterface extends \PoP\ComponentModel\ComponentProcessors\FormInputComponentProcessorInterface
{
    /**
     * @param \PoP\ComponentModel\Component\Component $component
     */
    public function getFilterInputTypeResolver($component) : InputTypeResolverInterface;
    /**
     * @param \PoP\ComponentModel\Component\Component $component
     */
    public function getFilterInputDescription($component) : ?string;
    /**
     * @return mixed
     * @param \PoP\ComponentModel\Component\Component $component
     */
    public function getFilterInputDefaultValue($component);
    /**
     * @param \PoP\ComponentModel\Component\Component $component
     */
    public function getFilterInputTypeModifiers($component) : int;
}
