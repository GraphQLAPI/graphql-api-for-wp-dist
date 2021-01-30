<?php

namespace PrefixedByPoP;

use PoP\Hooks\Facades\HooksAPIFacade;
use PoP\ComponentModel\ModuleProcessors\AbstractModuleProcessor;
class PoP_Posts_Module_Processor_CustomFilterInners extends \PoP\ComponentModel\ModuleProcessors\AbstractModuleProcessor
{
    public const MODULE_FILTERINNER_POSTS = 'filterinner-posts';
    public const MODULE_FILTERINNER_POSTCOUNT = 'filterinner-postcount';
    public function getModulesToProcess() : array
    {
        return array([self::class, self::MODULE_FILTERINNER_POSTS], [self::class, self::MODULE_FILTERINNER_POSTCOUNT]);
    }
    public function getSubmodules(array $module) : array
    {
        $ret = parent::getSubmodules($module);
        $inputmodules = [self::MODULE_FILTERINNER_POSTS => [[\PrefixedByPoP\PoP_Module_Processor_FilterInputs::class, \PrefixedByPoP\PoP_Module_Processor_FilterInputs::MODULE_FILTERINPUT_SEARCH], [\PrefixedByPoP\PoP_Module_Processor_FilterInputs::class, \PrefixedByPoP\PoP_Module_Processor_FilterInputs::MODULE_FILTERINPUT_ORDER], [\PrefixedByPoP\PoP_Module_Processor_FilterInputs::class, \PrefixedByPoP\PoP_Module_Processor_FilterInputs::MODULE_FILTERINPUT_LIMIT], [\PrefixedByPoP\PoP_Module_Processor_FilterInputs::class, \PrefixedByPoP\PoP_Module_Processor_FilterInputs::MODULE_FILTERINPUT_OFFSET], [\PrefixedByPoP\PoP_Module_Processor_FilterInputs::class, \PrefixedByPoP\PoP_Module_Processor_FilterInputs::MODULE_FILTERINPUT_DATES], [\PrefixedByPoP\PoP_Module_Processor_FilterInputs::class, \PrefixedByPoP\PoP_Module_Processor_FilterInputs::MODULE_FILTERINPUT_IDS], [\PrefixedByPoP\PoP_Module_Processor_FilterInputs::class, \PrefixedByPoP\PoP_Module_Processor_FilterInputs::MODULE_FILTERINPUT_ID]], self::MODULE_FILTERINNER_POSTCOUNT => [[\PrefixedByPoP\PoP_Module_Processor_FilterInputs::class, \PrefixedByPoP\PoP_Module_Processor_FilterInputs::MODULE_FILTERINPUT_SEARCH], [\PrefixedByPoP\PoP_Module_Processor_FilterInputs::class, \PrefixedByPoP\PoP_Module_Processor_FilterInputs::MODULE_FILTERINPUT_DATES], [\PrefixedByPoP\PoP_Module_Processor_FilterInputs::class, \PrefixedByPoP\PoP_Module_Processor_FilterInputs::MODULE_FILTERINPUT_IDS], [\PrefixedByPoP\PoP_Module_Processor_FilterInputs::class, \PrefixedByPoP\PoP_Module_Processor_FilterInputs::MODULE_FILTERINPUT_ID]]];
        if ($modules = \PoP\Hooks\Facades\HooksAPIFacade::getInstance()->applyFilters('Posts:FilterInners:inputmodules', $inputmodules[$module[1]], $module)) {
            $ret = \array_merge($ret, $modules);
        }
        return $ret;
    }
}
\class_alias('PrefixedByPoP\\PoP_Posts_Module_Processor_CustomFilterInners', 'PoP_Posts_Module_Processor_CustomFilterInners', \false);
