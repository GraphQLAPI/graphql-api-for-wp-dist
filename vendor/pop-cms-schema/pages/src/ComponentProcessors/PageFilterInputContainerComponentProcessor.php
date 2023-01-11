<?php

declare (strict_types=1);
namespace PoPCMSSchema\Pages\ComponentProcessors;

use PoP\ComponentModel\Component\Component;
use PoPCMSSchema\CustomPosts\ComponentProcessors\CustomPostFilterInputContainerComponentProcessor;
use PoPCMSSchema\SchemaCommons\ComponentProcessors\FormInputs\CommonFilterInputComponentProcessor;
class PageFilterInputContainerComponentProcessor extends CustomPostFilterInputContainerComponentProcessor
{
    public const HOOK_FILTER_INPUTS = __CLASS__ . ':filter-inputs';
    public const COMPONENT_FILTERINPUTCONTAINER_PAGELISTLIST = 'filterinputcontainer-pagelist';
    public const COMPONENT_FILTERINPUTCONTAINER_PAGELISTCOUNT = 'filterinputcontainer-pagecount';
    public const COMPONENT_FILTERINPUTCONTAINER_ADMINPAGELISTLIST = 'filterinputcontainer-adminpagelist';
    public const COMPONENT_FILTERINPUTCONTAINER_ADMINPAGELISTCOUNT = 'filterinputcontainer-adminpagecount';
    /**
     * @return string[]
     */
    public function getComponentNamesToProcess() : array
    {
        return array(self::COMPONENT_FILTERINPUTCONTAINER_PAGELISTLIST, self::COMPONENT_FILTERINPUTCONTAINER_PAGELISTCOUNT, self::COMPONENT_FILTERINPUTCONTAINER_ADMINPAGELISTLIST, self::COMPONENT_FILTERINPUTCONTAINER_ADMINPAGELISTCOUNT);
    }
    /**
     * @return Component[]
     * @param \PoP\ComponentModel\Component\Component $component
     */
    public function getFilterInputComponents($component) : array
    {
        switch ($component->name) {
            case self::COMPONENT_FILTERINPUTCONTAINER_PAGELISTLIST:
                $targetComponent = new Component(parent::class, parent::COMPONENT_FILTERINPUTCONTAINER_CUSTOMPOSTLISTLIST);
                break;
            case self::COMPONENT_FILTERINPUTCONTAINER_PAGELISTCOUNT:
                $targetComponent = new Component(parent::class, parent::COMPONENT_FILTERINPUTCONTAINER_CUSTOMPOSTLISTCOUNT);
                break;
            case self::COMPONENT_FILTERINPUTCONTAINER_ADMINPAGELISTLIST:
                $targetComponent = new Component(parent::class, parent::COMPONENT_FILTERINPUTCONTAINER_ADMINCUSTOMPOSTLISTLIST);
                break;
            case self::COMPONENT_FILTERINPUTCONTAINER_ADMINPAGELISTCOUNT:
                $targetComponent = new Component(parent::class, parent::COMPONENT_FILTERINPUTCONTAINER_ADMINCUSTOMPOSTLISTCOUNT);
                break;
            default:
                $targetComponent = null;
                break;
        }
        if ($targetComponent === null) {
            return [];
        }
        $filterInputComponents = parent::getFilterInputComponents($targetComponent);
        // Add the parentIDs and parentID filterInputs
        $filterInputComponents[] = new Component(CommonFilterInputComponentProcessor::class, CommonFilterInputComponentProcessor::COMPONENT_FILTERINPUT_PARENT_IDS);
        $filterInputComponents[] = new Component(CommonFilterInputComponentProcessor::class, CommonFilterInputComponentProcessor::COMPONENT_FILTERINPUT_PARENT_ID);
        $filterInputComponents[] = new Component(CommonFilterInputComponentProcessor::class, CommonFilterInputComponentProcessor::COMPONENT_FILTERINPUT_EXCLUDE_PARENT_IDS);
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
