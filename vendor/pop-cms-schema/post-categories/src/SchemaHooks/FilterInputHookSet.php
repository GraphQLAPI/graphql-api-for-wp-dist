<?php

declare (strict_types=1);
namespace PoPCMSSchema\PostCategories\SchemaHooks;

use PoP\ComponentModel\Component\Component;
use PoP\Root\App;
use PoP\Root\Hooks\AbstractHookSet;
use PoPCMSSchema\Categories\ComponentProcessors\FormInputs\FilterInputComponentProcessor;
use PoPCMSSchema\Posts\ComponentProcessors\AbstractPostFilterInputContainerComponentProcessor;
class FilterInputHookSet extends AbstractHookSet
{
    protected function init() : void
    {
        App::addFilter(AbstractPostFilterInputContainerComponentProcessor::HOOK_FILTER_INPUTS, \Closure::fromCallable([$this, 'getFilterInputComponents']));
    }
    /**
     * @param Component[] $filterInputComponents
     * @return Component[]
     */
    public function getFilterInputComponents($filterInputComponents) : array
    {
        return \array_merge($filterInputComponents, [new Component(FilterInputComponentProcessor::class, FilterInputComponentProcessor::COMPONENT_FILTERINPUT_CATEGORY_IDS)]);
    }
}
