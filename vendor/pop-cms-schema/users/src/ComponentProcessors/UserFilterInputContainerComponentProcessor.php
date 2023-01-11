<?php

declare (strict_types=1);
namespace PoPCMSSchema\Users\ComponentProcessors;

use PoP\ComponentModel\Component\Component;
use PoPCMSSchema\SchemaCommons\ComponentProcessors\AbstractFilterInputContainerComponentProcessor;
use PoPCMSSchema\Users\ComponentProcessors\FormInputs\FilterInputComponentProcessor;
class UserFilterInputContainerComponentProcessor extends AbstractFilterInputContainerComponentProcessor
{
    public const HOOK_FILTER_INPUTS = __CLASS__ . ':filter-inputs';
    public const COMPONENT_FILTERINPUTCONTAINER_USERS = 'filterinputcontainer-users';
    public const COMPONENT_FILTERINPUTCONTAINER_USERCOUNT = 'filterinputcontainer-usercount';
    public const COMPONENT_FILTERINPUTCONTAINER_ADMINUSERS = 'filterinputcontainer-adminusers';
    public const COMPONENT_FILTERINPUTCONTAINER_ADMINUSERCOUNT = 'filterinputcontainer-adminusercount';
    /**
     * @return string[]
     */
    public function getComponentNamesToProcess() : array
    {
        return array(self::COMPONENT_FILTERINPUTCONTAINER_USERS, self::COMPONENT_FILTERINPUTCONTAINER_USERCOUNT, self::COMPONENT_FILTERINPUTCONTAINER_ADMINUSERS, self::COMPONENT_FILTERINPUTCONTAINER_ADMINUSERCOUNT);
    }
    /**
     * @return Component[]
     * @param \PoP\ComponentModel\Component\Component $component
     */
    public function getFilterInputComponents($component) : array
    {
        $item0Unpacked = $this->getIDFilterInputComponents();
        $userFilterInputComponents = \array_merge($item0Unpacked, [new Component(FilterInputComponentProcessor::class, FilterInputComponentProcessor::COMPONENT_FILTERINPUT_NAME)]);
        $adminUserFilterInputComponents = [new Component(FilterInputComponentProcessor::class, FilterInputComponentProcessor::COMPONENT_FILTERINPUT_EMAILS)];
        $paginationFilterInputComponents = $this->getPaginationFilterInputComponents();
        switch ($component->name) {
            case self::COMPONENT_FILTERINPUTCONTAINER_USERS:
                return \array_merge(\is_array($userFilterInputComponents) ? $userFilterInputComponents : \iterator_to_array($userFilterInputComponents), $paginationFilterInputComponents);
            case self::COMPONENT_FILTERINPUTCONTAINER_ADMINUSERS:
                return \array_merge(\is_array($userFilterInputComponents) ? $userFilterInputComponents : \iterator_to_array($userFilterInputComponents), $adminUserFilterInputComponents, $paginationFilterInputComponents);
            case self::COMPONENT_FILTERINPUTCONTAINER_USERCOUNT:
                return $userFilterInputComponents;
            case self::COMPONENT_FILTERINPUTCONTAINER_ADMINUSERCOUNT:
                return \array_merge(\is_array($userFilterInputComponents) ? $userFilterInputComponents : \iterator_to_array($userFilterInputComponents), $adminUserFilterInputComponents);
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
