<?php

declare (strict_types=1);
namespace PoP\ModuleRouting;

abstract class AbstractEntryRouteModuleProcessor extends \PoP\ModuleRouting\AbstractRouteModuleProcessor
{
    /**
     * @return string[]
     */
    public function getGroups() : array
    {
        return [\PoP\ModuleRouting\ModuleRoutingGroups::ENTRYMODULE];
    }
}
