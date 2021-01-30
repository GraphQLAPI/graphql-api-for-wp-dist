<?php

declare (strict_types=1);
namespace PoPSchema\PostTags\Conditional\API\RouteModuleProcessors;

use PoP\Routing\RouteNatures;
use PoPSchema\PostTags\Facades\PostTagTypeAPIFacade;
use PoPSchema\Tags\Routing\RouteNatures as TagRouteNatures;
use PoP\ModuleRouting\AbstractEntryRouteModuleProcessor;
use PoP\API\Response\Schemes as APISchemes;
class EntryRouteModuleProcessor extends \PoP\ModuleRouting\AbstractEntryRouteModuleProcessor
{
    /**
     * @return array<string, array<array>>
     */
    public function getModulesVarsPropertiesByNature() : array
    {
        $ret = array();
        $postTagTypeAPI = \PoPSchema\PostTags\Facades\PostTagTypeAPIFacade::getInstance();
        $ret[\PoPSchema\Tags\Routing\RouteNatures::TAG][] = ['module' => [\PrefixedByPoP\PoP_PostTags_Module_Processor_FieldDataloads::class, \PrefixedByPoP\PoP_PostTags_Module_Processor_FieldDataloads::MODULE_DATALOAD_RELATIONALFIELDS_TAG], 'conditions' => ['scheme' => \PoP\API\Response\Schemes::API, 'routing-state' => ['taxonomy-name' => $postTagTypeAPI->getPostTagTaxonomyName()]]];
        return $ret;
    }
    /**
     * @return array<string, array<string, array<array>>>
     */
    public function getModulesVarsPropertiesByNatureAndRoute() : array
    {
        $ret = array();
        $postTagTypeAPI = \PoPSchema\PostTags\Facades\PostTagTypeAPIFacade::getInstance();
        $routemodules = array(POP_POSTTAGS_ROUTE_POSTTAGS => [\PrefixedByPoP\PoP_PostTags_Module_Processor_FieldDataloads::class, \PrefixedByPoP\PoP_PostTags_Module_Processor_FieldDataloads::MODULE_DATALOAD_RELATIONALFIELDS_TAGLIST]);
        foreach ($routemodules as $route => $module) {
            $ret[\PoP\Routing\RouteNatures::STANDARD][$route][] = ['module' => $module, 'conditions' => ['scheme' => \PoP\API\Response\Schemes::API]];
        }
        $routemodules = array(POP_POSTS_ROUTE_POSTS => [\PrefixedByPoP\PoP_Taxonomies_Posts_Module_Processor_FieldDataloads::class, \PrefixedByPoP\PoP_Taxonomies_Posts_Module_Processor_FieldDataloads::MODULE_DATALOAD_RELATIONALFIELDS_TAGPOSTLIST]);
        foreach ($routemodules as $route => $module) {
            $ret[\PoPSchema\Tags\Routing\RouteNatures::TAG][$route][] = ['module' => $module, 'conditions' => ['scheme' => \PoP\API\Response\Schemes::API, 'routing-state' => ['taxonomy-name' => $postTagTypeAPI->getPostTagTaxonomyName()]]];
        }
        return $ret;
    }
}
