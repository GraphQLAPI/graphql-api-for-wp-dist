<?php

declare (strict_types=1);
namespace PoPSchema\Users\Conditional\API\RouteModuleProcessors;

use PoP\ModuleRouting\AbstractEntryRouteModuleProcessor;
use PoP\Routing\RouteNatures;
use PoPSchema\Users\Routing\RouteNatures as UserRouteNatures;
use PoP\API\Response\Schemes as APISchemes;
class EntryRouteModuleProcessor extends \PoP\ModuleRouting\AbstractEntryRouteModuleProcessor
{
    /**
     * @return array<string, array<array>>
     */
    public function getModulesVarsPropertiesByNature() : array
    {
        $ret = array();
        $ret[\PoPSchema\Users\Routing\RouteNatures::USER][] = ['module' => [\PrefixedByPoP\PoP_Users_Module_Processor_FieldDataloads::class, \PrefixedByPoP\PoP_Users_Module_Processor_FieldDataloads::MODULE_DATALOAD_RELATIONALFIELDS_SINGLEUSER], 'conditions' => ['scheme' => \PoP\API\Response\Schemes::API]];
        return $ret;
    }
    /**
     * @return array<string, array<string, array<array>>>
     */
    public function getModulesVarsPropertiesByNatureAndRoute() : array
    {
        $ret = array();
        $routemodules = array(POP_USERS_ROUTE_USERS => [\PrefixedByPoP\PoP_Users_Module_Processor_FieldDataloads::class, \PrefixedByPoP\PoP_Users_Module_Processor_FieldDataloads::MODULE_DATALOAD_RELATIONALFIELDS_USERLIST]);
        foreach ($routemodules as $route => $module) {
            $ret[\PoP\Routing\RouteNatures::STANDARD][$route][] = ['module' => $module, 'conditions' => ['scheme' => \PoP\API\Response\Schemes::API]];
        }
        return $ret;
    }
}
