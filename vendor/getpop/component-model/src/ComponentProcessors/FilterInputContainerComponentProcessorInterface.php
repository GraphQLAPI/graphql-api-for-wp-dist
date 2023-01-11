<?php

declare (strict_types=1);
namespace PoP\ComponentModel\ComponentProcessors;

use PoP\ComponentModel\Component\Component;
use PoP\ComponentModel\TypeResolvers\InputTypeResolverInterface;
interface FilterInputContainerComponentProcessorInterface extends \PoP\ComponentModel\ComponentProcessors\FilterDataComponentProcessorInterface
{
    /**
     * @return array<string,InputTypeResolverInterface>
     * @param \PoP\ComponentModel\Component\Component $component
     */
    public function getFieldFilterInputNameTypeResolvers($component) : array;
    /**
     * @param \PoP\ComponentModel\Component\Component $component
     * @param string $fieldArgName
     */
    public function getFieldFilterInputDescription($component, $fieldArgName) : ?string;
    /**
     * @return mixed
     * @param \PoP\ComponentModel\Component\Component $component
     * @param string $fieldArgName
     */
    public function getFieldFilterInputDefaultValue($component, $fieldArgName);
    /**
     * @param \PoP\ComponentModel\Component\Component $component
     * @param string $fieldArgName
     */
    public function getFieldFilterInputTypeModifiers($component, $fieldArgName) : int;
    /**
     * @return Component[]
     * @param \PoP\ComponentModel\Component\Component $component
     */
    public function getFilterInputComponents($component) : array;
}
