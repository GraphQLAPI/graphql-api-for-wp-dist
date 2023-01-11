<?php

declare (strict_types=1);
namespace PoP\ComponentModel\ComponentProcessors;

use PoP\ComponentModel\Component\Component;
trait FilterDataComponentProcessorTrait
{
    protected abstract function getComponentProcessorManager() : \PoP\ComponentModel\ComponentProcessors\ComponentProcessorManagerInterface;
    /**
     * @var array<string,array<string,Component[]>>
     */
    protected $activeDataloadQueryArgsFilteringComponents = [];
    /**
     * @param array<string,mixed> $query
     * @param array<string,mixed>|null $source
     * @param \PoP\ComponentModel\Component\Component $component
     */
    public function filterHeadcomponentDataloadQueryArgs($component, &$query, $source = null) : void
    {
        if ($activeDataloadQueryArgsFilteringComponents = $this->getActiveDataloadQueryArgsFilteringComponents($component, $source)) {
            $componentProcessorManager = $this->getComponentProcessorManager();
            foreach ($activeDataloadQueryArgsFilteringComponents as $subcomponent) {
                /** @var DataloadQueryArgsFilterInputComponentProcessorInterface */
                $dataloadQueryArgsFilterInputComponentProcessor = $componentProcessorManager->getComponentProcessor($subcomponent);
                $value = $dataloadQueryArgsFilterInputComponentProcessor->getValue($subcomponent, $source);
                if ($filterInput = $dataloadQueryArgsFilterInputComponentProcessor->getFilterInput($subcomponent)) {
                    $filterInput->filterDataloadQueryArgs($query, $value);
                }
            }
        }
    }
    /**
     * @return Component[]
     * @param array<string,mixed>|null $source
     * @param \PoP\ComponentModel\Component\Component $component
     */
    public function getActiveDataloadQueryArgsFilteringComponents($component, $source = null) : array
    {
        // Search for cached result
        $cacheKey = (string) \json_encode($source ?? []);
        $this->activeDataloadQueryArgsFilteringComponents[$cacheKey] = $this->activeDataloadQueryArgsFilteringComponents[$cacheKey] ?? [];
        if (isset($this->activeDataloadQueryArgsFilteringComponents[$cacheKey][$component->name])) {
            return $this->activeDataloadQueryArgsFilteringComponents[$cacheKey][$component->name];
        }
        $components = [];
        // Check if the component has any filtercomponent
        if ($dataloadQueryArgsFilteringComponents = $this->getDataloadQueryArgsFilteringComponents($component)) {
            $componentProcessorManager = $this->getComponentProcessorManager();
            // Check if if we're currently filtering by any filtercomponent
            $components = \array_filter($dataloadQueryArgsFilteringComponents, function (Component $component) use($source, $componentProcessorManager) {
                /** @var DataloadQueryArgsFilterInputComponentProcessorInterface */
                $dataloadQueryArgsFilterInputComponentProcessor = $componentProcessorManager->getComponentProcessor($component);
                return $dataloadQueryArgsFilterInputComponentProcessor->isInputSetInSource($component, $source);
            });
        }
        $this->activeDataloadQueryArgsFilteringComponents[$cacheKey][$component->name] = $components;
        return $components;
    }
    /**
     * @return Component[]
     * @param \PoP\ComponentModel\Component\Component $component
     */
    public function getDataloadQueryArgsFilteringComponents($component) : array
    {
        $componentProcessorManager = $this->getComponentProcessorManager();
        return \array_values(\array_filter($this->getDatasetcomponentTreeSectionFlattenedComponents($component), function (Component $component) use($componentProcessorManager) {
            return $componentProcessorManager->getComponentProcessor($component) instanceof \PoP\ComponentModel\ComponentProcessors\DataloadQueryArgsFilterInputComponentProcessorInterface;
        }));
    }
}
