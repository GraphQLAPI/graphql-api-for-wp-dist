<?php

declare (strict_types=1);
namespace PoPSchema\Posts\Conditional\API\RouteModuleProcessors;

use PoP\ModuleRouting\AbstractEntryRouteModuleProcessor;
use PoP\Routing\RouteNatures;
use PoPSchema\CustomPosts\Routing\RouteNatures as CustomPostRouteNatures;
use PoP\API\Response\Schemes as APISchemes;
class EntryRouteModuleProcessor extends \PoP\ModuleRouting\AbstractEntryRouteModuleProcessor
{
    /**
     * @return array<string, array<array>>
     */
    public function getModulesVarsPropertiesByNature() : array
    {
        $ret = array();
        $ret[\PoPSchema\CustomPosts\Routing\RouteNatures::CUSTOMPOST][] = ['module' => [\PrefixedByPoP\PoP_Posts_Module_Processor_FieldDataloads::class, \PrefixedByPoP\PoP_Posts_Module_Processor_FieldDataloads::MODULE_DATALOAD_RELATIONALFIELDS_SINGLEPOST], 'conditions' => ['scheme' => \PoP\API\Response\Schemes::API]];
        return $ret;
    }
    /**
     * @return array<string, array<string, array<array>>>
     */
    public function getModulesVarsPropertiesByNatureAndRoute() : array
    {
        $ret = array();
        $routemodules = array(POP_POSTS_ROUTE_POSTS => [\PrefixedByPoP\PoP_Posts_Module_Processor_FieldDataloads::class, \PrefixedByPoP\PoP_Posts_Module_Processor_FieldDataloads::MODULE_DATALOAD_RELATIONALFIELDS_POSTLIST]);
        foreach ($routemodules as $route => $module) {
            $ret[\PoP\Routing\RouteNatures::STANDARD][$route][] = ['module' => $module, 'conditions' => ['scheme' => \PoP\API\Response\Schemes::API]];
        }
        return $ret;
    }
}
