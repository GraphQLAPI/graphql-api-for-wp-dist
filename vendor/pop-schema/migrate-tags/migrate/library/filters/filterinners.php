<?php

namespace PrefixedByPoP;

use PoP\Hooks\Facades\HooksAPIFacade;
use PoP\ComponentModel\ModuleProcessors\AbstractModuleProcessor;
class PoP_Tags_Module_Processor_CustomFilterInners extends \PoP\ComponentModel\ModuleProcessors\AbstractModuleProcessor
{
    public const MODULE_FILTERINNER_TAGS = 'filterinner-tags';
    public const MODULE_FILTERINNER_TAGCOUNT = 'filterinner-tagcount';
    public function getModulesToProcess() : array
    {
        return array([self::class, self::MODULE_FILTERINNER_TAGS], [self::class, self::MODULE_FILTERINNER_TAGCOUNT]);
    }
    public function getSubmodules(array $module) : array
    {
        $ret = parent::getSubmodules($module);
        $inputmodules = [self::MODULE_FILTERINNER_TAGS => [[\PrefixedByPoP\PoP_Module_Processor_FilterInputs::class, \PrefixedByPoP\PoP_Module_Processor_FilterInputs::MODULE_FILTERINPUT_SEARCH], [\PrefixedByPoP\PoP_Module_Processor_FilterInputs::class, \PrefixedByPoP\PoP_Module_Processor_FilterInputs::MODULE_FILTERINPUT_ORDER], [\PrefixedByPoP\PoP_Module_Processor_FilterInputs::class, \PrefixedByPoP\PoP_Module_Processor_FilterInputs::MODULE_FILTERINPUT_LIMIT], [\PrefixedByPoP\PoP_Module_Processor_FilterInputs::class, \PrefixedByPoP\PoP_Module_Processor_FilterInputs::MODULE_FILTERINPUT_OFFSET], [\PrefixedByPoP\PoP_Module_Processor_FilterInputs::class, \PrefixedByPoP\PoP_Module_Processor_FilterInputs::MODULE_FILTERINPUT_IDS], [\PrefixedByPoP\PoP_Module_Processor_FilterInputs::class, \PrefixedByPoP\PoP_Module_Processor_FilterInputs::MODULE_FILTERINPUT_ID]], self::MODULE_FILTERINNER_TAGCOUNT => [[\PrefixedByPoP\PoP_Module_Processor_FilterInputs::class, \PrefixedByPoP\PoP_Module_Processor_FilterInputs::MODULE_FILTERINPUT_SEARCH], [\PrefixedByPoP\PoP_Module_Processor_FilterInputs::class, \PrefixedByPoP\PoP_Module_Processor_FilterInputs::MODULE_FILTERINPUT_IDS], [\PrefixedByPoP\PoP_Module_Processor_FilterInputs::class, \PrefixedByPoP\PoP_Module_Processor_FilterInputs::MODULE_FILTERINPUT_ID]]];
        if ($modules = \PoP\Hooks\Facades\HooksAPIFacade::getInstance()->applyFilters('Tags:FilterInners:inputmodules', $inputmodules[$module[1]], $module)) {
            $ret = \array_merge($ret, $modules);
        }
        return $ret;
    }
}
\class_alias('PrefixedByPoP\\PoP_Tags_Module_Processor_CustomFilterInners', 'PoP_Tags_Module_Processor_CustomFilterInners', \false);
