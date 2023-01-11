<?php

declare (strict_types=1);
namespace PoP\ComponentModel\ComponentProcessors;

use PoP\ComponentModel\Component\Component;
use PoP\ComponentModel\Schema\SchemaTypeModifiers;
use PoP\ComponentModel\TypeResolvers\InputTypeResolverInterface;
use PoP\Root\App;
abstract class AbstractFilterInputContainerComponentProcessor extends \PoP\ComponentModel\ComponentProcessors\AbstractFilterDataComponentProcessor implements \PoP\ComponentModel\ComponentProcessors\FilterInputContainerComponentProcessorInterface
{
    public const HOOK_FILTER_INPUTS = __CLASS__ . ':filter-inputs';
    /**
     * @return Component[]
     * @param \PoP\ComponentModel\Component\Component $component
     */
    public final function getSubcomponents($component) : array
    {
        $filterInputComponents = $this->getFilterInputComponents($component);
        // Enable extensions to add more FilterInputs
        foreach ($this->getFilterInputHookNames() as $filterInputHookName) {
            $filterInputComponents = App::applyFilters($filterInputHookName, $filterInputComponents, $component);
        }
        // Add the filterInputs to whatever came from the parent (if anything)
        return \array_merge(parent::getSubcomponents($component), $filterInputComponents);
    }
    /**
     * @return string[]
     */
    protected function getFilterInputHookNames() : array
    {
        return [self::HOOK_FILTER_INPUTS];
    }
    /**
     * @return array<string,InputTypeResolverInterface>
     * @param \PoP\ComponentModel\Component\Component $component
     */
    public function getFieldFilterInputNameTypeResolvers($component) : array
    {
        $componentProcessorManager = $this->getComponentProcessorManager();
        $filterQueryArgsComponents = $this->getDataloadQueryArgsFilteringComponents($component);
        $schemaFieldArgNameTypeResolvers = [];
        foreach ($filterQueryArgsComponents as $component) {
            /** @var DataloadQueryArgsFilterInputComponentProcessorInterface */
            $dataloadQueryArgsFilterInputComponentProcessor = $componentProcessorManager->getComponentProcessor($component);
            $filterInputName = $dataloadQueryArgsFilterInputComponentProcessor->getName($component);
            $schemaFieldArgNameTypeResolvers[$filterInputName] = $dataloadQueryArgsFilterInputComponentProcessor->getFilterInputTypeResolver($component);
        }
        return $schemaFieldArgNameTypeResolvers;
    }
    /**
     * @param \PoP\ComponentModel\Component\Component $component
     * @param string $fieldArgName
     */
    public function getFieldFilterInputDescription($component, $fieldArgName) : ?string
    {
        $componentProcessorManager = $this->getComponentProcessorManager();
        $filterQueryArgsComponents = $this->getDataloadQueryArgsFilteringComponents($component);
        foreach ($filterQueryArgsComponents as $component) {
            /** @var DataloadQueryArgsFilterInputComponentProcessorInterface */
            $dataloadQueryArgsFilterInputComponentProcessor = $componentProcessorManager->getComponentProcessor($component);
            $filterInputName = $dataloadQueryArgsFilterInputComponentProcessor->getName($component);
            if ($filterInputName === $fieldArgName) {
                return $dataloadQueryArgsFilterInputComponentProcessor->getFilterInputDescription($component);
            }
        }
        return null;
    }
    /**
     * @return mixed
     * @param \PoP\ComponentModel\Component\Component $component
     * @param string $fieldArgName
     */
    public function getFieldFilterInputDefaultValue($component, $fieldArgName)
    {
        $componentProcessorManager = $this->getComponentProcessorManager();
        $filterQueryArgsComponents = $this->getDataloadQueryArgsFilteringComponents($component);
        foreach ($filterQueryArgsComponents as $component) {
            /** @var DataloadQueryArgsFilterInputComponentProcessorInterface */
            $dataloadQueryArgsFilterInputComponentProcessor = $componentProcessorManager->getComponentProcessor($component);
            $filterInputName = $dataloadQueryArgsFilterInputComponentProcessor->getName($component);
            if ($filterInputName === $fieldArgName) {
                return $dataloadQueryArgsFilterInputComponentProcessor->getFilterInputDefaultValue($component);
            }
        }
        return null;
    }
    /**
     * @param \PoP\ComponentModel\Component\Component $component
     * @param string $fieldArgName
     */
    public function getFieldFilterInputTypeModifiers($component, $fieldArgName) : int
    {
        $componentProcessorManager = $this->getComponentProcessorManager();
        $filterQueryArgsComponents = $this->getDataloadQueryArgsFilteringComponents($component);
        foreach ($filterQueryArgsComponents as $filterInputComponent) {
            /** @var DataloadQueryArgsFilterInputComponentProcessorInterface */
            $dataloadQueryArgsFilterInputComponentProcessor = $componentProcessorManager->getComponentProcessor($filterInputComponent);
            $filterInputName = $dataloadQueryArgsFilterInputComponentProcessor->getName($filterInputComponent);
            if ($filterInputName === $fieldArgName) {
                $fieldFilterInputTypeModifiers = $dataloadQueryArgsFilterInputComponentProcessor->getFilterInputTypeModifiers($filterInputComponent);
                if ($this->makeFieldFilterInputMandatoryIfHasDefaultValue($component, $fieldArgName) && null !== $this->getFieldFilterInputDefaultValue($component, $fieldArgName)) {
                    return $fieldFilterInputTypeModifiers | SchemaTypeModifiers::MANDATORY;
                }
                return $fieldFilterInputTypeModifiers;
            }
        }
        return SchemaTypeModifiers::NONE;
    }
    /**
     * Is the input that has a default value also mandatory?
     *
     * This helps avoid errors from expecting type `string` in
     * some PHP function and receiving `null`.
     *
     * Eg: { posts { dateStr(format: null) } }
     * @param \PoP\ComponentModel\Component\Component $component
     * @param string $fieldArgName
     */
    protected function makeFieldFilterInputMandatoryIfHasDefaultValue($component, $fieldArgName) : bool
    {
        return \true;
    }
}
