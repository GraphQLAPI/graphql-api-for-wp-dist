<?php

declare (strict_types=1);
namespace PoP\API\RouteModuleProcessors;

use PoP\Routing\RouteNatures;
use PoP\ModuleRouting\AbstractEntryRouteModuleProcessor;
use PoP\API\ModuleProcessors\RootRelationalFieldDataloadModuleProcessor;
use PoP\API\Response\Schemes as APISchemes;
class EntryRouteModuleProcessor extends \PoP\ModuleRouting\AbstractEntryRouteModuleProcessor
{
    /**
     * @return array<string, array<array>>
     */
    public function getModulesVarsPropertiesByNature() : array
    {
        $ret = array();
        $ret[\PoP\Routing\RouteNatures::HOME][] = ['module' => [\PoP\API\ModuleProcessors\RootRelationalFieldDataloadModuleProcessor::class, \PoP\API\ModuleProcessors\RootRelationalFieldDataloadModuleProcessor::MODULE_DATALOAD_RELATIONALFIELDS_ROOT], 'conditions' => ['scheme' => \PoP\API\Response\Schemes::API]];
        return $ret;
    }
}
