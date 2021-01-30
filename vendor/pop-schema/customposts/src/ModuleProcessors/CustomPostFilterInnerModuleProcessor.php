<?php

declare (strict_types=1);
namespace PoPSchema\CustomPosts\ModuleProcessors;

use PoP\Hooks\Facades\HooksAPIFacade;
use PoP\ComponentModel\ModuleProcessors\AbstractModuleProcessor;
class CustomPostFilterInnerModuleProcessor extends \PoP\ComponentModel\ModuleProcessors\AbstractModuleProcessor
{
    public const MODULE_FILTERINNER_UNIONCUSTOMPOSTLIST = 'filterinner-unioncustompostlist';
    public const MODULE_FILTERINNER_UNIONCUSTOMPOSTCOUNT = 'filterinner-unioncustompostcount';
    public const MODULE_FILTERINNER_CUSTOMPOSTLISTLIST = 'filterinner-custompostlist';
    public const MODULE_FILTERINNER_CUSTOMPOSTLISTCOUNT = 'filterinner-custompostcount';
    public function getModulesToProcess() : array
    {
        return array([self::class, self::MODULE_FILTERINNER_UNIONCUSTOMPOSTLIST], [self::class, self::MODULE_FILTERINNER_UNIONCUSTOMPOSTCOUNT], [self::class, self::MODULE_FILTERINNER_CUSTOMPOSTLISTLIST], [self::class, self::MODULE_FILTERINNER_CUSTOMPOSTLISTCOUNT]);
    }
    public function getSubmodules(array $module) : array
    {
        $ret = parent::getSubmodules($module);
        switch ($module[1]) {
            case self::MODULE_FILTERINNER_UNIONCUSTOMPOSTLIST:
            case self::MODULE_FILTERINNER_CUSTOMPOSTLISTLIST:
                $inputmodules = [[\PrefixedByPoP\PoP_Module_Processor_FilterInputs::class, \PrefixedByPoP\PoP_Module_Processor_FilterInputs::MODULE_FILTERINPUT_SEARCH], [\PrefixedByPoP\PoP_Module_Processor_FilterInputs::class, \PrefixedByPoP\PoP_Module_Processor_FilterInputs::MODULE_FILTERINPUT_ORDER], [\PrefixedByPoP\PoP_Module_Processor_FilterInputs::class, \PrefixedByPoP\PoP_Module_Processor_FilterInputs::MODULE_FILTERINPUT_LIMIT], [\PrefixedByPoP\PoP_Module_Processor_FilterInputs::class, \PrefixedByPoP\PoP_Module_Processor_FilterInputs::MODULE_FILTERINPUT_OFFSET], [\PrefixedByPoP\PoP_Module_Processor_FilterInputs::class, \PrefixedByPoP\PoP_Module_Processor_FilterInputs::MODULE_FILTERINPUT_DATES], [\PrefixedByPoP\PoP_Module_Processor_FilterInputs::class, \PrefixedByPoP\PoP_Module_Processor_FilterInputs::MODULE_FILTERINPUT_IDS], [\PrefixedByPoP\PoP_Module_Processor_FilterInputs::class, \PrefixedByPoP\PoP_Module_Processor_FilterInputs::MODULE_FILTERINPUT_ID]];
                break;
            case self::MODULE_FILTERINNER_UNIONCUSTOMPOSTCOUNT:
            case self::MODULE_FILTERINNER_CUSTOMPOSTLISTCOUNT:
                $inputmodules = [[\PrefixedByPoP\PoP_Module_Processor_FilterInputs::class, \PrefixedByPoP\PoP_Module_Processor_FilterInputs::MODULE_FILTERINPUT_SEARCH], [\PrefixedByPoP\PoP_Module_Processor_FilterInputs::class, \PrefixedByPoP\PoP_Module_Processor_FilterInputs::MODULE_FILTERINPUT_DATES], [\PrefixedByPoP\PoP_Module_Processor_FilterInputs::class, \PrefixedByPoP\PoP_Module_Processor_FilterInputs::MODULE_FILTERINPUT_IDS], [\PrefixedByPoP\PoP_Module_Processor_FilterInputs::class, \PrefixedByPoP\PoP_Module_Processor_FilterInputs::MODULE_FILTERINPUT_ID]];
                break;
        }
        // Fields "customPosts" and "customPostCount" also have the "postTypes" filter
        if (\in_array($module[1], [self::MODULE_FILTERINNER_UNIONCUSTOMPOSTLIST, self::MODULE_FILTERINNER_UNIONCUSTOMPOSTCOUNT])) {
            $inputmodules[] = [\PrefixedByPoP\PoP_CustomPosts_Module_Processor_FilterInputs::class, \PrefixedByPoP\PoP_CustomPosts_Module_Processor_FilterInputs::MODULE_FILTERINPUT_UNIONCUSTOMPOSTTYPES];
        }
        if ($modules = \PoP\Hooks\Facades\HooksAPIFacade::getInstance()->applyFilters('CustomPosts:FilterInners:inputmodules', $inputmodules, $module)) {
            $ret = \array_merge($ret, $modules);
        }
        return $ret;
    }
}
