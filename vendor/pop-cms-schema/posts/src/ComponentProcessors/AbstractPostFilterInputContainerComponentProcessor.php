<?php

declare (strict_types=1);
namespace PoPCMSSchema\Posts\ComponentProcessors;

use PoP\ComponentModel\Component\Component;
use PoPCMSSchema\CustomPosts\ComponentProcessors\AbstractCustomPostFilterInputContainerComponentProcessor;
use PoPCMSSchema\CustomPosts\ComponentProcessors\FormInputs\FilterInputComponentProcessor;
use PoPCMSSchema\SchemaCommons\ComponentProcessors\FormInputs\CommonFilterInputComponentProcessor;
abstract class AbstractPostFilterInputContainerComponentProcessor extends AbstractCustomPostFilterInputContainerComponentProcessor
{
    public const HOOK_FILTER_INPUTS = __CLASS__ . ':filter-inputs';
    public const COMPONENT_FILTERINPUTCONTAINER_POSTS = 'filterinputcontainer-posts';
    public const COMPONENT_FILTERINPUTCONTAINER_POSTCOUNT = 'filterinputcontainer-postcount';
    public const COMPONENT_FILTERINPUTCONTAINER_ADMINPOSTS = 'filterinputcontainer-adminposts';
    public const COMPONENT_FILTERINPUTCONTAINER_ADMINPOSTCOUNT = 'filterinputcontainer-adminpostcount';
    /**
     * @return string[]
     */
    public function getComponentNamesToProcess() : array
    {
        return array(self::COMPONENT_FILTERINPUTCONTAINER_POSTS, self::COMPONENT_FILTERINPUTCONTAINER_POSTCOUNT, self::COMPONENT_FILTERINPUTCONTAINER_ADMINPOSTS, self::COMPONENT_FILTERINPUTCONTAINER_ADMINPOSTCOUNT);
    }
    /**
     * @return Component[]
     * @param \PoP\ComponentModel\Component\Component $component
     */
    public function getFilterInputComponents($component) : array
    {
        $item0Unpacked = $this->getIDFilterInputComponents();
        $postFilterInputComponents = \array_merge($item0Unpacked, [new Component(CommonFilterInputComponentProcessor::class, CommonFilterInputComponentProcessor::COMPONENT_FILTERINPUT_SEARCH)]);
        $statusFilterInputComponents = [new Component(FilterInputComponentProcessor::class, FilterInputComponentProcessor::COMPONENT_FILTERINPUT_CUSTOMPOSTSTATUS)];
        $paginationFilterInputComponents = $this->getPaginationFilterInputComponents();
        switch ($component->name) {
            case self::COMPONENT_FILTERINPUTCONTAINER_POSTS:
                return \array_merge(\is_array($postFilterInputComponents) ? $postFilterInputComponents : \iterator_to_array($postFilterInputComponents), $paginationFilterInputComponents);
            case self::COMPONENT_FILTERINPUTCONTAINER_POSTCOUNT:
                return $postFilterInputComponents;
            case self::COMPONENT_FILTERINPUTCONTAINER_ADMINPOSTS:
                return \array_merge(\is_array($postFilterInputComponents) ? $postFilterInputComponents : \iterator_to_array($postFilterInputComponents), $paginationFilterInputComponents, $statusFilterInputComponents);
            case self::COMPONENT_FILTERINPUTCONTAINER_ADMINPOSTCOUNT:
                return \array_merge(\is_array($postFilterInputComponents) ? $postFilterInputComponents : \iterator_to_array($postFilterInputComponents), $statusFilterInputComponents);
            default:
                return [];
        }
    }
    /**
     * @return string[]
     */
    protected function getFilterInputHookNames() : array
    {
        $item1Unpacked = parent::getFilterInputHookNames();
        return \array_merge($item1Unpacked, [self::HOOK_FILTER_INPUTS]);
    }
}
