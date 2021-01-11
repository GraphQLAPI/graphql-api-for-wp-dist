<?php

declare(strict_types=1);

namespace PoPSchema\Pages\Conditional\RESTAPI\RouteModuleProcessors;

use PoP\ModuleRouting\AbstractEntryRouteModuleProcessor;
use PoP\ComponentModel\State\ApplicationState;
use PoP\Hooks\Facades\HooksAPIFacade;
use PoP\API\Facades\FieldQueryConvertorFacade;
use PoPSchema\Pages\Routing\RouteNatures;
use PoP\RESTAPI\DataStructureFormatters\RESTDataStructureFormatter;
use PoP\API\Response\Schemes as APISchemes;

class EntryRouteModuleProcessor extends AbstractEntryRouteModuleProcessor
{
    private static $restFieldsQuery;
    private static $restFields;
    public static function getRESTFields(): array
    {
        if (is_null(self::$restFields)) {
            self::$restFields = self::getRESTFieldsQuery();
            if (is_string(self::$restFields)) {
                $fieldQueryConvertor = FieldQueryConvertorFacade::getInstance();
                $fieldQuerySet = $fieldQueryConvertor->convertAPIQuery(self::$restFields);
                self::$restFields = $fieldQuerySet->getRequestedFieldQuery();
            }
        }
        return self::$restFields;
    }
    public static function getRESTFieldsQuery(): string
    {
        if (is_null(self::$restFieldsQuery)) {
            $restFieldsQuery = 'id|title|url|content';
            self::$restFieldsQuery = (string) HooksAPIFacade::getInstance()->applyFilters('Pages:RESTFields', $restFieldsQuery);
        }
        return self::$restFieldsQuery;
    }

    /**
     * @return array<string, array<array>>
     */
    public function getModulesVarsPropertiesByNature(): array
    {
        $ret = array();

        $vars = ApplicationState::getVars();
        $ret[RouteNatures::PAGE][] = [
            'module' => [\PoP_Pages_Module_Processor_FieldDataloads::class, \PoP_Pages_Module_Processor_FieldDataloads::MODULE_DATALOAD_RELATIONALFIELDS_PAGE, ['fields' => isset($vars['query']) ? $vars['query'] : self::getRESTFields()]],
            'conditions' => [
                'scheme' => APISchemes::API,
                'datastructure' => RESTDataStructureFormatter::getName(),
            ],
        ];

        return $ret;
    }
}