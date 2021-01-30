<?php

declare (strict_types=1);
namespace PoPSchema\Posts\Conditional\Users\Conditional\API\RouteModuleProcessors;

use PoP\ModuleRouting\AbstractEntryRouteModuleProcessor;
use PoPSchema\Users\Routing\RouteNatures;
use PoP\API\Response\Schemes as APISchemes;
class EntryRouteModuleProcessor extends \PoP\ModuleRouting\AbstractEntryRouteModuleProcessor
{
    /**
     * @return array<string, array<string, array<array>>>
     */
    public function getModulesVarsPropertiesByNatureAndRoute() : array
    {
        $ret = array();
        // Author's posts
        $routemodules = array(POP_POSTS_ROUTE_POSTS => [\PrefixedByPoP\PoP_Users_Posts_Module_Processor_FieldDataloads::class, \PrefixedByPoP\PoP_Users_Posts_Module_Processor_FieldDataloads::MODULE_DATALOAD_RELATIONALFIELDS_AUTHORPOSTLIST]);
        foreach ($routemodules as $route => $module) {
            $ret[\PoPSchema\Users\Routing\RouteNatures::USER][$route][] = ['module' => $module, 'conditions' => ['scheme' => \PoP\API\Response\Schemes::API]];
        }
        return $ret;
    }
}
