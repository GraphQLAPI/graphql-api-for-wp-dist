<?php

declare (strict_types=1);
namespace PoPCMSSchema\Media\ConditionalOnModule\Users\SchemaHooks;

use PoP\ComponentModel\Component\Component;
use PoP\Root\App;
use PoP\Root\Hooks\AbstractHookSet;
use PoPCMSSchema\Media\ComponentProcessors\MediaFilterInputContainerComponentProcessor;
use PoPCMSSchema\Users\ConditionalOnModule\CustomPosts\ComponentProcessors\FormInputs\FilterInputComponentProcessor;
class FilterInputHookSet extends AbstractHookSet
{
    protected function init() : void
    {
        App::addFilter(MediaFilterInputContainerComponentProcessor::HOOK_FILTER_INPUTS, \Closure::fromCallable([$this, 'getFilterInputComponents']));
    }
    /**
     * @param Component[] $filterInputComponents
     * @return Component[]
     */
    public function getFilterInputComponents($filterInputComponents) : array
    {
        $item1Unpacked = $this->getAuthorFilterInputComponents();
        return \array_merge($filterInputComponents, $item1Unpacked);
    }
    /**
     * @return Component[]
     */
    public function getAuthorFilterInputComponents() : array
    {
        return [new Component(FilterInputComponentProcessor::class, FilterInputComponentProcessor::COMPONENT_FILTERINPUT_AUTHOR_IDS), new Component(FilterInputComponentProcessor::class, FilterInputComponentProcessor::COMPONENT_FILTERINPUT_AUTHOR_SLUG), new Component(FilterInputComponentProcessor::class, FilterInputComponentProcessor::COMPONENT_FILTERINPUT_EXCLUDE_AUTHOR_IDS)];
    }
}
