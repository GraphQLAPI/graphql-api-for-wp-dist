<?php

declare (strict_types=1);
namespace PoPCMSSchema\Categories\ComponentProcessors;

use PoPCMSSchema\Categories\ComponentProcessors\FormInputs\FilterInputComponentProcessor;
use PoPCMSSchema\SchemaCommons\ComponentProcessors\AbstractFilterInputContainerComponentProcessor;
use PoPCMSSchema\SchemaCommons\ComponentProcessors\FormInputs\CommonFilterInputComponentProcessor;
use PoP\ComponentModel\Component\Component;
class CategoryFilterInputContainerComponentProcessor extends AbstractFilterInputContainerComponentProcessor
{
    public const HOOK_FILTER_INPUTS = __CLASS__ . ':filter-inputs';
    public const COMPONENT_FILTERINPUTCONTAINER_CATEGORIES = 'filterinputcontainer-categories';
    public const COMPONENT_FILTERINPUTCONTAINER_CATEGORYCOUNT = 'filterinputcontainer-categorycount';
    public const COMPONENT_FILTERINPUTCONTAINER_CHILDCATEGORIES = 'filterinputcontainer-childcategories';
    public const COMPONENT_FILTERINPUTCONTAINER_CHILDCATEGORYCOUNT = 'filterinputcontainer-childcategorycount';
    public const COMPONENT_FILTERINPUTCONTAINER_GENERICCATEGORIES = 'filterinputcontainer-genericcategories';
    /**
     * @return string[]
     */
    public function getComponentNamesToProcess() : array
    {
        return array(self::COMPONENT_FILTERINPUTCONTAINER_CATEGORIES, self::COMPONENT_FILTERINPUTCONTAINER_CATEGORYCOUNT, self::COMPONENT_FILTERINPUTCONTAINER_CHILDCATEGORIES, self::COMPONENT_FILTERINPUTCONTAINER_CHILDCATEGORYCOUNT, self::COMPONENT_FILTERINPUTCONTAINER_GENERICCATEGORIES);
    }
    /**
     * @return Component[]
     * @param \PoP\ComponentModel\Component\Component $component
     */
    public function getFilterInputComponents($component) : array
    {
        $item0Unpacked = $this->getIDFilterInputComponents();
        $categoryFilterInputComponents = \array_merge($item0Unpacked, [new Component(CommonFilterInputComponentProcessor::class, CommonFilterInputComponentProcessor::COMPONENT_FILTERINPUT_SEARCH), new Component(CommonFilterInputComponentProcessor::class, CommonFilterInputComponentProcessor::COMPONENT_FILTERINPUT_SLUGS)]);
        $topLevelCategoryFilterInputComponents = [new Component(CommonFilterInputComponentProcessor::class, CommonFilterInputComponentProcessor::COMPONENT_FILTERINPUT_PARENT_ID)];
        $paginationFilterInputComponents = $this->getPaginationFilterInputComponents();
        switch ($component->name) {
            case self::COMPONENT_FILTERINPUTCONTAINER_CATEGORIES:
                return \array_merge(\is_array($categoryFilterInputComponents) ? $categoryFilterInputComponents : \iterator_to_array($categoryFilterInputComponents), $topLevelCategoryFilterInputComponents, $paginationFilterInputComponents);
            case self::COMPONENT_FILTERINPUTCONTAINER_CHILDCATEGORIES:
                return \array_merge(\is_array($categoryFilterInputComponents) ? $categoryFilterInputComponents : \iterator_to_array($categoryFilterInputComponents), $paginationFilterInputComponents);
            case self::COMPONENT_FILTERINPUTCONTAINER_CATEGORYCOUNT:
                return \array_merge(\is_array($categoryFilterInputComponents) ? $categoryFilterInputComponents : \iterator_to_array($categoryFilterInputComponents), $topLevelCategoryFilterInputComponents);
            case self::COMPONENT_FILTERINPUTCONTAINER_CHILDCATEGORYCOUNT:
                return $categoryFilterInputComponents;
            case self::COMPONENT_FILTERINPUTCONTAINER_GENERICCATEGORIES:
                return [new Component(FilterInputComponentProcessor::class, FilterInputComponentProcessor::COMPONENT_FILTERINPUT_GENERIC_CATEGORY_TAXONOMY)];
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
