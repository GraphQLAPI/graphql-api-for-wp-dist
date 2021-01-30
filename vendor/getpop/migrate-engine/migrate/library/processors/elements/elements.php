<?php

namespace PrefixedByPoP;

use PoP\ComponentModel\ModuleProcessors\AbstractModuleProcessor;
class PoP_Engine_Module_Processor_Elements extends \PoP\ComponentModel\ModuleProcessors\AbstractModuleProcessor
{
    public const MODULE_EMPTY = 'empty';
    public function getModulesToProcess() : array
    {
        return array([self::class, self::MODULE_EMPTY]);
    }
}
\class_alias('PrefixedByPoP\\PoP_Engine_Module_Processor_Elements', 'PoP_Engine_Module_Processor_Elements', \false);
