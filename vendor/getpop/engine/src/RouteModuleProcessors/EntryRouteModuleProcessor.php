<?php

declare (strict_types=1);
namespace PoP\Engine\RouteModuleProcessors;

use PoP\ModuleRouting\AbstractEntryRouteModuleProcessor;
class EntryRouteModuleProcessor extends \PoP\ModuleRouting\AbstractEntryRouteModuleProcessor
{
    /**
     * @return array<string, string[]>
     */
    public function getModulesVarsProperties() : array
    {
        $ret = array();
        $ret[] = ['module' => [\PrefixedByPoP\PoP_Engine_Module_Processor_Elements::class, \PrefixedByPoP\PoP_Engine_Module_Processor_Elements::MODULE_EMPTY]];
        return $ret;
    }
}
