<?php

declare (strict_types=1);
namespace PoP\ComponentModel\Resolvers;

use PoP\ComponentModel\Component\Component;
use PoP\ComponentModel\ComponentProcessors\ComponentProcessorManagerInterface;
use PoP\ComponentModel\ComponentProcessors\FilterInputContainerComponentProcessorInterface;
use PoP\ComponentModel\TypeResolvers\InputTypeResolverInterface;
trait QueryableFieldResolverTrait
{
    protected abstract function getComponentProcessorManager() : ComponentProcessorManagerInterface;
    /**
     * @return array<string,InputTypeResolverInterface>
     * @param \PoP\ComponentModel\Component\Component $filterDataloadingComponent
     */
    protected function getFilterFieldArgNameTypeResolvers($filterDataloadingComponent) : array
    {
        /** @var FilterInputContainerComponentProcessorInterface */
        $filterDataComponentProcessor = $this->getComponentProcessorManager()->getComponentProcessor($filterDataloadingComponent);
        return $filterDataComponentProcessor->getFieldFilterInputNameTypeResolvers($filterDataloadingComponent);
    }
    /**
     * @param \PoP\ComponentModel\Component\Component $filterDataloadingComponent
     * @param string $fieldArgName
     */
    protected function getFilterFieldArgDescription($filterDataloadingComponent, $fieldArgName) : ?string
    {
        /** @var FilterInputContainerComponentProcessorInterface */
        $filterDataComponentProcessor = $this->getComponentProcessorManager()->getComponentProcessor($filterDataloadingComponent);
        return $filterDataComponentProcessor->getFieldFilterInputDescription($filterDataloadingComponent, $fieldArgName);
    }
    /**
     * @return mixed
     * @param \PoP\ComponentModel\Component\Component $filterDataloadingComponent
     * @param string $fieldArgName
     */
    protected function getFilterFieldArgDefaultValue($filterDataloadingComponent, $fieldArgName)
    {
        /** @var FilterInputContainerComponentProcessorInterface */
        $filterDataComponentProcessor = $this->getComponentProcessorManager()->getComponentProcessor($filterDataloadingComponent);
        return $filterDataComponentProcessor->getFieldFilterInputDefaultValue($filterDataloadingComponent, $fieldArgName);
    }
    /**
     * @param \PoP\ComponentModel\Component\Component $filterDataloadingComponent
     * @param string $fieldArgName
     */
    protected function getFilterFieldArgTypeModifiers($filterDataloadingComponent, $fieldArgName) : int
    {
        /** @var FilterInputContainerComponentProcessorInterface */
        $filterDataComponentProcessor = $this->getComponentProcessorManager()->getComponentProcessor($filterDataloadingComponent);
        return $filterDataComponentProcessor->getFieldFilterInputTypeModifiers($filterDataloadingComponent, $fieldArgName);
    }
}
