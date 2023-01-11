<?php

declare (strict_types=1);
namespace PoPCMSSchema\CustomPosts\ComponentProcessors;

use PoP\ComponentModel\Component\Component;
use PoPCMSSchema\CustomPosts\ComponentProcessors\FormInputs\FilterInputComponentProcessor;
use PoPCMSSchema\SchemaCommons\ComponentProcessors\FormInputs\CommonFilterInputComponentProcessor;
class CustomPostFilterInputContainerComponentProcessor extends \PoPCMSSchema\CustomPosts\ComponentProcessors\AbstractCustomPostFilterInputContainerComponentProcessor
{
    public const HOOK_FILTER_INPUTS = __CLASS__ . ':filter-inputs';
    public const COMPONENT_FILTERINPUTCONTAINER_UNIONCUSTOMPOSTLIST = 'filterinputcontainer-unioncustompostlist';
    public const COMPONENT_FILTERINPUTCONTAINER_UNIONCUSTOMPOSTCOUNT = 'filterinputcontainer-unioncustompostcount';
    public const COMPONENT_FILTERINPUTCONTAINER_ADMINUNIONCUSTOMPOSTLIST = 'filterinputcontainer-adminunioncustompostlist';
    public const COMPONENT_FILTERINPUTCONTAINER_ADMINUNIONCUSTOMPOSTCOUNT = 'filterinputcontainer-adminunioncustompostcount';
    public const COMPONENT_FILTERINPUTCONTAINER_CUSTOMPOSTLISTLIST = 'filterinputcontainer-custompostlist';
    public const COMPONENT_FILTERINPUTCONTAINER_CUSTOMPOSTLISTCOUNT = 'filterinputcontainer-custompostcount';
    public const COMPONENT_FILTERINPUTCONTAINER_ADMINCUSTOMPOSTLISTLIST = 'filterinputcontainer-admincustompostlist';
    public const COMPONENT_FILTERINPUTCONTAINER_ADMINCUSTOMPOSTLISTCOUNT = 'filterinputcontainer-admincustompostcount';
    /**
     * @return string[]
     */
    public function getComponentNamesToProcess() : array
    {
        return array(self::COMPONENT_FILTERINPUTCONTAINER_UNIONCUSTOMPOSTLIST, self::COMPONENT_FILTERINPUTCONTAINER_UNIONCUSTOMPOSTCOUNT, self::COMPONENT_FILTERINPUTCONTAINER_ADMINUNIONCUSTOMPOSTLIST, self::COMPONENT_FILTERINPUTCONTAINER_ADMINUNIONCUSTOMPOSTCOUNT, self::COMPONENT_FILTERINPUTCONTAINER_CUSTOMPOSTLISTLIST, self::COMPONENT_FILTERINPUTCONTAINER_CUSTOMPOSTLISTCOUNT, self::COMPONENT_FILTERINPUTCONTAINER_ADMINCUSTOMPOSTLISTLIST, self::COMPONENT_FILTERINPUTCONTAINER_ADMINCUSTOMPOSTLISTCOUNT);
    }
    /**
     * @return Component[]
     * @param \PoP\ComponentModel\Component\Component $component
     */
    public function getFilterInputComponents($component) : array
    {
        $item0Unpacked = $this->getIDFilterInputComponents();
        $customPostFilterInputComponents = \array_merge($item0Unpacked, [new Component(CommonFilterInputComponentProcessor::class, CommonFilterInputComponentProcessor::COMPONENT_FILTERINPUT_SEARCH)]);
        $unionCustomPostFilterInputComponents = [new Component(FilterInputComponentProcessor::class, FilterInputComponentProcessor::COMPONENT_FILTERINPUT_UNIONCUSTOMPOSTTYPES)];
        $adminCustomPostFilterInputComponents = [new Component(FilterInputComponentProcessor::class, FilterInputComponentProcessor::COMPONENT_FILTERINPUT_CUSTOMPOSTSTATUS)];
        $paginationFilterInputComponents = $this->getPaginationFilterInputComponents();
        switch ($component->name) {
            case self::COMPONENT_FILTERINPUTCONTAINER_UNIONCUSTOMPOSTLIST:
                return \array_merge(\is_array($customPostFilterInputComponents) ? $customPostFilterInputComponents : \iterator_to_array($customPostFilterInputComponents), $unionCustomPostFilterInputComponents, $paginationFilterInputComponents);
            case self::COMPONENT_FILTERINPUTCONTAINER_ADMINUNIONCUSTOMPOSTLIST:
                return \array_merge(\is_array($customPostFilterInputComponents) ? $customPostFilterInputComponents : \iterator_to_array($customPostFilterInputComponents), $unionCustomPostFilterInputComponents, $adminCustomPostFilterInputComponents, $paginationFilterInputComponents);
            case self::COMPONENT_FILTERINPUTCONTAINER_CUSTOMPOSTLISTLIST:
                return \array_merge(\is_array($customPostFilterInputComponents) ? $customPostFilterInputComponents : \iterator_to_array($customPostFilterInputComponents), $paginationFilterInputComponents);
            case self::COMPONENT_FILTERINPUTCONTAINER_ADMINCUSTOMPOSTLISTLIST:
                return \array_merge(\is_array($customPostFilterInputComponents) ? $customPostFilterInputComponents : \iterator_to_array($customPostFilterInputComponents), $adminCustomPostFilterInputComponents, $paginationFilterInputComponents);
            case self::COMPONENT_FILTERINPUTCONTAINER_UNIONCUSTOMPOSTCOUNT:
                return \array_merge(\is_array($customPostFilterInputComponents) ? $customPostFilterInputComponents : \iterator_to_array($customPostFilterInputComponents), $unionCustomPostFilterInputComponents);
            case self::COMPONENT_FILTERINPUTCONTAINER_ADMINUNIONCUSTOMPOSTCOUNT:
                return \array_merge(\is_array($customPostFilterInputComponents) ? $customPostFilterInputComponents : \iterator_to_array($customPostFilterInputComponents), $adminCustomPostFilterInputComponents, $unionCustomPostFilterInputComponents);
            case self::COMPONENT_FILTERINPUTCONTAINER_CUSTOMPOSTLISTCOUNT:
                return $customPostFilterInputComponents;
            case self::COMPONENT_FILTERINPUTCONTAINER_ADMINCUSTOMPOSTLISTCOUNT:
                return \array_merge(\is_array($customPostFilterInputComponents) ? $customPostFilterInputComponents : \iterator_to_array($customPostFilterInputComponents), $adminCustomPostFilterInputComponents);
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
