<?php

declare (strict_types=1);
namespace PoPCMSSchema\PostMutations\ComponentProcessors;

use PoP\ComponentModel\Component\Component;
use PoPCMSSchema\CustomPosts\ComponentProcessors\FormInputs\FilterInputComponentProcessor as CustomPostFilterInputComponentProcessor;
use PoPCMSSchema\Posts\ComponentProcessors\AbstractPostFilterInputContainerComponentProcessor;
use PoPCMSSchema\Posts\ComponentProcessors\PostFilterInputContainerComponentProcessor;
class PostMutationFilterInputContainerComponentProcessor extends AbstractPostFilterInputContainerComponentProcessor
{
    public const HOOK_FILTER_INPUTS = __CLASS__ . ':filter-inputs';
    public const COMPONENT_FILTERINPUTCONTAINER_MYPOSTS = 'filterinputcontainer-myposts';
    public const COMPONENT_FILTERINPUTCONTAINER_MYPOSTCOUNT = 'filterinputcontainer-mypostcount';
    /**
     * @return string[]
     */
    public function getComponentNamesToProcess() : array
    {
        return array(self::COMPONENT_FILTERINPUTCONTAINER_MYPOSTS, self::COMPONENT_FILTERINPUTCONTAINER_MYPOSTCOUNT);
    }
    /**
     * Retrieve the same elements as for Posts, and add the "status" filter
     *
     * @return Component[]
     * @param \PoP\ComponentModel\Component\Component $component
     */
    public function getFilterInputComponents($component) : array
    {
        switch ($component->name) {
            case self::COMPONENT_FILTERINPUTCONTAINER_MYPOSTS:
                $targetComponent = self::COMPONENT_FILTERINPUTCONTAINER_POSTS;
                break;
            case self::COMPONENT_FILTERINPUTCONTAINER_MYPOSTCOUNT:
                $targetComponent = self::COMPONENT_FILTERINPUTCONTAINER_POSTCOUNT;
                break;
            default:
                $targetComponent = null;
                break;
        }
        if ($targetComponent === null) {
            return [];
        }
        $filterInputComponents = parent::getFilterInputComponents(new Component(PostFilterInputContainerComponentProcessor::class, $targetComponent));
        $filterInputComponents[] = new Component(CustomPostFilterInputComponentProcessor::class, CustomPostFilterInputComponentProcessor::COMPONENT_FILTERINPUT_CUSTOMPOSTSTATUS);
        return $filterInputComponents;
    }
    /**
     * @return string[]
     */
    protected function getFilterInputHookNames() : array
    {
        $item0Unpacked = parent::getFilterInputHookNames();
        return \array_merge($item0Unpacked, [self::HOOK_FILTER_INPUTS]);
    }
}
