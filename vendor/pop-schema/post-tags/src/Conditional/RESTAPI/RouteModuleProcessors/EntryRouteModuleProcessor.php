<?php

declare (strict_types=1);
namespace PoPSchema\PostTags\Conditional\RESTAPI\RouteModuleProcessors;

use PoP\ModuleRouting\AbstractEntryRouteModuleProcessor;
use PoP\ComponentModel\State\ApplicationState;
use PoP\Hooks\Facades\HooksAPIFacade;
use PoP\API\Facades\FieldQueryConvertorFacade;
use PoPSchema\PostTags\Facades\PostTagTypeAPIFacade;
use PoP\Routing\RouteNatures;
use PoPSchema\Tags\Routing\RouteNatures as TagRouteNatures;
use PoP\RESTAPI\DataStructureFormatters\RESTDataStructureFormatter;
use PoP\API\Response\Schemes as APISchemes;
class EntryRouteModuleProcessor extends \PoP\ModuleRouting\AbstractEntryRouteModuleProcessor
{
    public const HOOK_REST_FIELDS = __CLASS__ . ':RESTFields';
    private static $restFieldsQuery;
    private static $restFields;
    public static function getRESTFields() : array
    {
        if (\is_null(self::$restFields)) {
            self::$restFields = self::getRESTFieldsQuery();
            if (\is_string(self::$restFields)) {
                $fieldQueryConvertor = \PoP\API\Facades\FieldQueryConvertorFacade::getInstance();
                $fieldQuerySet = $fieldQueryConvertor->convertAPIQuery(self::$restFields);
                self::$restFields = $fieldQuerySet->getRequestedFieldQuery();
            }
        }
        return self::$restFields;
    }
    public static function getRESTFieldsQuery() : string
    {
        if (\is_null(self::$restFieldsQuery)) {
            $restFieldsQuery = 'id|name|count|url';
            self::$restFieldsQuery = (string) \PoP\Hooks\Facades\HooksAPIFacade::getInstance()->applyFilters(self::HOOK_REST_FIELDS, $restFieldsQuery);
        }
        return self::$restFieldsQuery;
    }
    /**
     * @return array<string, array<array>>
     */
    public function getModulesVarsPropertiesByNature() : array
    {
        $ret = array();
        $vars = \PoP\ComponentModel\State\ApplicationState::getVars();
        $postTagTypeAPI = \PoPSchema\PostTags\Facades\PostTagTypeAPIFacade::getInstance();
        $ret[\PoPSchema\Tags\Routing\RouteNatures::TAG][] = ['module' => [\PrefixedByPoP\PoP_PostTags_Module_Processor_FieldDataloads::class, \PrefixedByPoP\PoP_PostTags_Module_Processor_FieldDataloads::MODULE_DATALOAD_RELATIONALFIELDS_TAG, ['fields' => isset($vars['query']) ? $vars['query'] : self::getRESTFields()]], 'conditions' => ['scheme' => \PoP\API\Response\Schemes::API, 'datastructure' => \PoP\RESTAPI\DataStructureFormatters\RESTDataStructureFormatter::getName(), 'routing-state' => ['taxonomy-name' => $postTagTypeAPI->getPostTagTaxonomyName()]]];
        return $ret;
    }
    /**
     * @return array<string, array<string, array<array>>>
     */
    public function getModulesVarsPropertiesByNatureAndRoute() : array
    {
        $ret = array();
        $vars = \PoP\ComponentModel\State\ApplicationState::getVars();
        $postTagTypeAPI = \PoPSchema\PostTags\Facades\PostTagTypeAPIFacade::getInstance();
        $routemodules = array(POP_POSTTAGS_ROUTE_POSTTAGS => [\PrefixedByPoP\PoP_PostTags_Module_Processor_FieldDataloads::class, \PrefixedByPoP\PoP_PostTags_Module_Processor_FieldDataloads::MODULE_DATALOAD_RELATIONALFIELDS_TAGLIST, ['fields' => isset($vars['query']) ? $vars['query'] : self::getRESTFields()]]);
        foreach ($routemodules as $route => $module) {
            $ret[\PoP\Routing\RouteNatures::STANDARD][$route][] = ['module' => $module, 'conditions' => ['scheme' => \PoP\API\Response\Schemes::API, 'datastructure' => \PoP\RESTAPI\DataStructureFormatters\RESTDataStructureFormatter::getName()]];
        }
        $routemodules = array(POP_POSTS_ROUTE_POSTS => [\PrefixedByPoP\PoP_Taxonomies_Posts_Module_Processor_FieldDataloads::class, \PrefixedByPoP\PoP_Taxonomies_Posts_Module_Processor_FieldDataloads::MODULE_DATALOAD_RELATIONALFIELDS_TAGPOSTLIST, ['fields' => isset($vars['query']) ? $vars['query'] : self::getRESTFields()]]);
        foreach ($routemodules as $route => $module) {
            $ret[\PoPSchema\Tags\Routing\RouteNatures::TAG][$route][] = ['module' => $module, 'conditions' => ['scheme' => \PoP\API\Response\Schemes::API, 'datastructure' => \PoP\RESTAPI\DataStructureFormatters\RESTDataStructureFormatter::getName(), 'routing-state' => ['taxonomy-name' => $postTagTypeAPI->getPostTagTaxonomyName()]]];
        }
        return $ret;
    }
}
