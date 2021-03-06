<?php

declare (strict_types=1);
namespace PoPSchema\Pages\Conditional\RESTAPI\RouteModuleProcessors;

use PoP\API\Facades\FieldQueryConvertorFacade;
use PoP\API\Response\Schemes as APISchemes;
use PoP\ComponentModel\State\ApplicationState;
use PoP\Hooks\Facades\HooksAPIFacade;
use PoP\RESTAPI\RouteModuleProcessors\AbstractRESTEntryRouteModuleProcessor;
use PoPSchema\Pages\Routing\RouteNatures;
class EntryRouteModuleProcessor extends \PoP\RESTAPI\RouteModuleProcessors\AbstractRESTEntryRouteModuleProcessor
{
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
            $restFieldsQuery = 'id|title|url|content';
            self::$restFieldsQuery = (string) \PoP\Hooks\Facades\HooksAPIFacade::getInstance()->applyFilters('Pages:RESTFields', $restFieldsQuery);
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
        $ret[\PoPSchema\Pages\Routing\RouteNatures::PAGE][] = ['module' => [\PrefixedByPoP\PoP_Pages_Module_Processor_FieldDataloads::class, \PrefixedByPoP\PoP_Pages_Module_Processor_FieldDataloads::MODULE_DATALOAD_RELATIONALFIELDS_PAGE, ['fields' => isset($vars['query']) ? $vars['query'] : self::getRESTFields()]], 'conditions' => ['scheme' => \PoP\API\Response\Schemes::API, 'datastructure' => $this->restDataStructureFormatter->getName()]];
        return $ret;
    }
}
