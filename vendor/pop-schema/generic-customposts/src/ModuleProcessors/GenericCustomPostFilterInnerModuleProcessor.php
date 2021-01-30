<?php

declare (strict_types=1);
namespace PoPSchema\GenericCustomPosts\ModuleProcessors;

use PoP\Hooks\Facades\HooksAPIFacade;
use PoP\ComponentModel\ModuleProcessors\AbstractModuleProcessor;
class GenericCustomPostFilterInnerModuleProcessor extends \PoP\ComponentModel\ModuleProcessors\AbstractModuleProcessor
{
    public const MODULE_FILTERINNER_GENERICCUSTOMPOSTLIST = 'filterinner-genericcustompostlist';
    public const MODULE_FILTERINNER_GENERICCUSTOMPOSTCOUNT = 'filterinner-genericcustompostcount';
    public function getModulesToProcess() : array
    {
        return array([self::class, self::MODULE_FILTERINNER_GENERICCUSTOMPOSTLIST], [self::class, self::MODULE_FILTERINNER_GENERICCUSTOMPOSTCOUNT]);
    }
    public function getSubmodules(array $module) : array
    {
        $ret = parent::getSubmodules($module);
        switch ($module[1]) {
            case self::MODULE_FILTERINNER_GENERICCUSTOMPOSTLIST:
                $inputmodules = [[\PrefixedByPoP\PoP_Module_Processor_FilterInputs::class, \PrefixedByPoP\PoP_Module_Processor_FilterInputs::MODULE_FILTERINPUT_SEARCH], [\PrefixedByPoP\PoP_Module_Processor_FilterInputs::class, \PrefixedByPoP\PoP_Module_Processor_FilterInputs::MODULE_FILTERINPUT_ORDER], [\PrefixedByPoP\PoP_Module_Processor_FilterInputs::class, \PrefixedByPoP\PoP_Module_Processor_FilterInputs::MODULE_FILTERINPUT_LIMIT], [\PrefixedByPoP\PoP_Module_Processor_FilterInputs::class, \PrefixedByPoP\PoP_Module_Processor_FilterInputs::MODULE_FILTERINPUT_OFFSET], [\PrefixedByPoP\PoP_Module_Processor_FilterInputs::class, \PrefixedByPoP\PoP_Module_Processor_FilterInputs::MODULE_FILTERINPUT_DATES], [\PrefixedByPoP\PoP_Module_Processor_FilterInputs::class, \PrefixedByPoP\PoP_Module_Processor_FilterInputs::MODULE_FILTERINPUT_IDS], [\PrefixedByPoP\PoP_Module_Processor_FilterInputs::class, \PrefixedByPoP\PoP_Module_Processor_FilterInputs::MODULE_FILTERINPUT_ID], [\PrefixedByPoP\PoP_CustomPosts_Module_Processor_FilterInputs::class, \PrefixedByPoP\PoP_CustomPosts_Module_Processor_FilterInputs::MODULE_FILTERINPUT_GENERICPOSTTYPES]];
                break;
            case self::MODULE_FILTERINNER_GENERICCUSTOMPOSTCOUNT:
                $inputmodules = [[\PrefixedByPoP\PoP_Module_Processor_FilterInputs::class, \PrefixedByPoP\PoP_Module_Processor_FilterInputs::MODULE_FILTERINPUT_SEARCH], [\PrefixedByPoP\PoP_Module_Processor_FilterInputs::class, \PrefixedByPoP\PoP_Module_Processor_FilterInputs::MODULE_FILTERINPUT_DATES], [\PrefixedByPoP\PoP_Module_Processor_FilterInputs::class, \PrefixedByPoP\PoP_Module_Processor_FilterInputs::MODULE_FILTERINPUT_IDS], [\PrefixedByPoP\PoP_Module_Processor_FilterInputs::class, \PrefixedByPoP\PoP_Module_Processor_FilterInputs::MODULE_FILTERINPUT_ID], [\PrefixedByPoP\PoP_CustomPosts_Module_Processor_FilterInputs::class, \PrefixedByPoP\PoP_CustomPosts_Module_Processor_FilterInputs::MODULE_FILTERINPUT_GENERICPOSTTYPES]];
                break;
        }
        if ($modules = \PoP\Hooks\Facades\HooksAPIFacade::getInstance()->applyFilters('GenericCustomPosts:FilterInners:inputmodules', $inputmodules, $module)) {
            $ret = \array_merge($ret, $modules);
        }
        return $ret;
    }
}
