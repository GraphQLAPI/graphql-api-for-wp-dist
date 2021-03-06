<?php

declare (strict_types=1);
namespace PoPSchema\Posts\Conditional\RESTAPI\RouteModuleProcessorHelpers;

use PoP\Hooks\Facades\HooksAPIFacade;
use PoP\API\Facades\FieldQueryConvertorFacade;
class EntryRouteModuleProcessorHelpers
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
            self::$restFieldsQuery = (string) \PoP\Hooks\Facades\HooksAPIFacade::getInstance()->applyFilters(self::HOOK_REST_FIELDS, \PoPSchema\CustomPosts\Conditional\RESTAPI\RouteModuleProcessorHelpers\EntryRouteModuleProcessorHelpers::getRESTFieldsQuery());
        }
        return self::$restFieldsQuery;
    }
}
