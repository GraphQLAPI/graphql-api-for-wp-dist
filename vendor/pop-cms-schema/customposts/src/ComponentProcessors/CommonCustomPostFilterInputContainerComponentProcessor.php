<?php

declare (strict_types=1);
namespace PoPCMSSchema\CustomPosts\ComponentProcessors;

use PoP\ComponentModel\Component\Component;
use PoP\ComponentModel\FilterInput\FilterInputHelper;
use PoP\ComponentModel\Schema\SchemaTypeModifiers;
use PoPCMSSchema\CustomPosts\ComponentProcessors\FormInputs\FilterInputComponentProcessor;
use PoPCMSSchema\SchemaCommons\ComponentProcessors\AbstractFilterInputContainerComponentProcessor;
use PoPCMSSchema\SchemaCommons\ComponentProcessors\FormInputs\CommonFilterInputComponentProcessor;
class CommonCustomPostFilterInputContainerComponentProcessor extends AbstractFilterInputContainerComponentProcessor
{
    public const HOOK_FILTER_INPUTS = __CLASS__ . ':filter-inputs';
    public const COMPONENT_FILTERINPUTCONTAINER_CUSTOMPOSTSTATUS = 'filterinputcontainer-custompoststatus';
    public const COMPONENT_FILTERINPUTCONTAINER_CUSTOMPOST_BY_UNIONTYPE = 'filterinputcontainer-custompost-by-uniontype';
    public const COMPONENT_FILTERINPUTCONTAINER_CUSTOMPOST_BY_STATUS_UNIONTYPE = 'filterinputcontainer-custompost-by-status-uniontype';
    public const COMPONENT_FILTERINPUTCONTAINER_CUSTOMPOST_BY_ID_STATUS = 'filterinputcontainer-custompost-by-id-status';
    public const COMPONENT_FILTERINPUTCONTAINER_CUSTOMPOST_BY_ID_UNIONTYPE = 'filterinputcontainer-custompost-by-id-uniontype';
    public const COMPONENT_FILTERINPUTCONTAINER_CUSTOMPOST_BY_ID_STATUS_UNIONTYPE = 'filterinputcontainer-custompost-by-id-status-uniontype';
    public const COMPONENT_FILTERINPUTCONTAINER_CUSTOMPOST_BY_SLUG_STATUS = 'filterinputcontainer-custompost-by-slug-status';
    public const COMPONENT_FILTERINPUTCONTAINER_CUSTOMPOST_BY_SLUG_UNIONTYPE = 'filterinputcontainer-custompost-by-slug-uniontype';
    public const COMPONENT_FILTERINPUTCONTAINER_CUSTOMPOST_BY_SLUG_STATUS_UNIONTYPE = 'filterinputcontainer-custompost-by-slug-status-uniontype';
    /**
     * @return string[]
     */
    public function getComponentNamesToProcess() : array
    {
        return array(self::COMPONENT_FILTERINPUTCONTAINER_CUSTOMPOSTSTATUS, self::COMPONENT_FILTERINPUTCONTAINER_CUSTOMPOST_BY_UNIONTYPE, self::COMPONENT_FILTERINPUTCONTAINER_CUSTOMPOST_BY_STATUS_UNIONTYPE, self::COMPONENT_FILTERINPUTCONTAINER_CUSTOMPOST_BY_ID_STATUS, self::COMPONENT_FILTERINPUTCONTAINER_CUSTOMPOST_BY_ID_UNIONTYPE, self::COMPONENT_FILTERINPUTCONTAINER_CUSTOMPOST_BY_ID_STATUS_UNIONTYPE, self::COMPONENT_FILTERINPUTCONTAINER_CUSTOMPOST_BY_SLUG_STATUS, self::COMPONENT_FILTERINPUTCONTAINER_CUSTOMPOST_BY_SLUG_UNIONTYPE, self::COMPONENT_FILTERINPUTCONTAINER_CUSTOMPOST_BY_SLUG_STATUS_UNIONTYPE);
    }
    /**
     * @return Component[]
     * @param \PoP\ComponentModel\Component\Component $component
     */
    public function getFilterInputComponents($component) : array
    {
        switch ($component->name) {
            case self::COMPONENT_FILTERINPUTCONTAINER_CUSTOMPOSTSTATUS:
                return [new Component(FilterInputComponentProcessor::class, FilterInputComponentProcessor::COMPONENT_FILTERINPUT_CUSTOMPOSTSTATUS)];
            case self::COMPONENT_FILTERINPUTCONTAINER_CUSTOMPOST_BY_UNIONTYPE:
                return [new Component(FilterInputComponentProcessor::class, FilterInputComponentProcessor::COMPONENT_FILTERINPUT_UNIONCUSTOMPOSTTYPES)];
            case self::COMPONENT_FILTERINPUTCONTAINER_CUSTOMPOST_BY_STATUS_UNIONTYPE:
                return [new Component(FilterInputComponentProcessor::class, FilterInputComponentProcessor::COMPONENT_FILTERINPUT_CUSTOMPOSTSTATUS), new Component(FilterInputComponentProcessor::class, FilterInputComponentProcessor::COMPONENT_FILTERINPUT_UNIONCUSTOMPOSTTYPES)];
            case self::COMPONENT_FILTERINPUTCONTAINER_CUSTOMPOST_BY_ID_STATUS:
                return [new Component(CommonFilterInputComponentProcessor::class, CommonFilterInputComponentProcessor::COMPONENT_FILTERINPUT_ID), new Component(FilterInputComponentProcessor::class, FilterInputComponentProcessor::COMPONENT_FILTERINPUT_CUSTOMPOSTSTATUS)];
            case self::COMPONENT_FILTERINPUTCONTAINER_CUSTOMPOST_BY_ID_UNIONTYPE:
                return [new Component(CommonFilterInputComponentProcessor::class, CommonFilterInputComponentProcessor::COMPONENT_FILTERINPUT_ID), new Component(FilterInputComponentProcessor::class, FilterInputComponentProcessor::COMPONENT_FILTERINPUT_UNIONCUSTOMPOSTTYPES)];
            case self::COMPONENT_FILTERINPUTCONTAINER_CUSTOMPOST_BY_ID_STATUS_UNIONTYPE:
                return [new Component(CommonFilterInputComponentProcessor::class, CommonFilterInputComponentProcessor::COMPONENT_FILTERINPUT_ID), new Component(FilterInputComponentProcessor::class, FilterInputComponentProcessor::COMPONENT_FILTERINPUT_CUSTOMPOSTSTATUS), new Component(FilterInputComponentProcessor::class, FilterInputComponentProcessor::COMPONENT_FILTERINPUT_UNIONCUSTOMPOSTTYPES)];
            case self::COMPONENT_FILTERINPUTCONTAINER_CUSTOMPOST_BY_SLUG_STATUS:
                return [new Component(CommonFilterInputComponentProcessor::class, CommonFilterInputComponentProcessor::COMPONENT_FILTERINPUT_SLUG), new Component(FilterInputComponentProcessor::class, FilterInputComponentProcessor::COMPONENT_FILTERINPUT_CUSTOMPOSTSTATUS)];
            case self::COMPONENT_FILTERINPUTCONTAINER_CUSTOMPOST_BY_SLUG_UNIONTYPE:
                return [new Component(CommonFilterInputComponentProcessor::class, CommonFilterInputComponentProcessor::COMPONENT_FILTERINPUT_SLUG), new Component(FilterInputComponentProcessor::class, FilterInputComponentProcessor::COMPONENT_FILTERINPUT_UNIONCUSTOMPOSTTYPES)];
            case self::COMPONENT_FILTERINPUTCONTAINER_CUSTOMPOST_BY_SLUG_STATUS_UNIONTYPE:
                return [new Component(CommonFilterInputComponentProcessor::class, CommonFilterInputComponentProcessor::COMPONENT_FILTERINPUT_SLUG), new Component(FilterInputComponentProcessor::class, FilterInputComponentProcessor::COMPONENT_FILTERINPUT_CUSTOMPOSTSTATUS), new Component(FilterInputComponentProcessor::class, FilterInputComponentProcessor::COMPONENT_FILTERINPUT_UNIONCUSTOMPOSTTYPES)];
            default:
                return [];
        }
    }
    /**
     * @param \PoP\ComponentModel\Component\Component $component
     * @param string $fieldArgName
     */
    public function getFieldFilterInputTypeModifiers($component, $fieldArgName) : int
    {
        $fieldFilterInputTypeModifiers = parent::getFieldFilterInputTypeModifiers($component, $fieldArgName);
        switch ($component->name) {
            case self::COMPONENT_FILTERINPUTCONTAINER_CUSTOMPOST_BY_ID_STATUS:
            case self::COMPONENT_FILTERINPUTCONTAINER_CUSTOMPOST_BY_ID_UNIONTYPE:
            case self::COMPONENT_FILTERINPUTCONTAINER_CUSTOMPOST_BY_ID_STATUS_UNIONTYPE:
                $idFilterInputName = FilterInputHelper::getFilterInputName(new Component(CommonFilterInputComponentProcessor::class, CommonFilterInputComponentProcessor::COMPONENT_FILTERINPUT_ID));
                if ($fieldArgName === $idFilterInputName) {
                    return $fieldFilterInputTypeModifiers | SchemaTypeModifiers::MANDATORY;
                }
                break;
            case self::COMPONENT_FILTERINPUTCONTAINER_CUSTOMPOST_BY_SLUG_STATUS:
            case self::COMPONENT_FILTERINPUTCONTAINER_CUSTOMPOST_BY_SLUG_UNIONTYPE:
            case self::COMPONENT_FILTERINPUTCONTAINER_CUSTOMPOST_BY_SLUG_STATUS_UNIONTYPE:
                $slugFilterInputName = FilterInputHelper::getFilterInputName(new Component(CommonFilterInputComponentProcessor::class, CommonFilterInputComponentProcessor::COMPONENT_FILTERINPUT_SLUG));
                if ($fieldArgName === $slugFilterInputName) {
                    return $fieldFilterInputTypeModifiers | SchemaTypeModifiers::MANDATORY;
                }
                break;
        }
        return $fieldFilterInputTypeModifiers;
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
