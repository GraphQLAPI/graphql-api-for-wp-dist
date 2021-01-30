<?php

declare (strict_types=1);
namespace PoPSchema\Users\Conditional\RESTAPI\RouteModuleProcessors;

use PoP\ModuleRouting\AbstractEntryRouteModuleProcessor;
use PoP\ComponentModel\State\ApplicationState;
use PoP\Hooks\Facades\HooksAPIFacade;
use PoP\API\Facades\FieldQueryConvertorFacade;
use PoP\Routing\RouteNatures;
use PoPSchema\Users\Routing\RouteNatures as UserRouteNatures;
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
            $restFieldsQuery = 'id|name|url';
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
        $ret[\PoPSchema\Users\Routing\RouteNatures::USER][] = ['module' => [\PrefixedByPoP\PoP_Users_Module_Processor_FieldDataloads::class, \PrefixedByPoP\PoP_Users_Module_Processor_FieldDataloads::MODULE_DATALOAD_RELATIONALFIELDS_SINGLEUSER, ['fields' => isset($vars['query']) ? $vars['query'] : self::getRESTFields()]], 'conditions' => ['scheme' => \PoP\API\Response\Schemes::API, 'datastructure' => \PoP\RESTAPI\DataStructureFormatters\RESTDataStructureFormatter::getName()]];
        return $ret;
    }
    /**
     * @return array<string, array<string, array<array>>>
     */
    public function getModulesVarsPropertiesByNatureAndRoute() : array
    {
        $ret = array();
        $vars = \PoP\ComponentModel\State\ApplicationState::getVars();
        $routemodules = array(POP_USERS_ROUTE_USERS => [\PrefixedByPoP\PoP_Users_Module_Processor_FieldDataloads::class, \PrefixedByPoP\PoP_Users_Module_Processor_FieldDataloads::MODULE_DATALOAD_RELATIONALFIELDS_USERLIST, ['fields' => isset($vars['query']) ? $vars['query'] : self::getRESTFields()]]);
        foreach ($routemodules as $route => $module) {
            $ret[\PoP\Routing\RouteNatures::STANDARD][$route][] = ['module' => $module, 'conditions' => ['scheme' => \PoP\API\Response\Schemes::API, 'datastructure' => \PoP\RESTAPI\DataStructureFormatters\RESTDataStructureFormatter::getName()]];
        }
        return $ret;
    }
}
