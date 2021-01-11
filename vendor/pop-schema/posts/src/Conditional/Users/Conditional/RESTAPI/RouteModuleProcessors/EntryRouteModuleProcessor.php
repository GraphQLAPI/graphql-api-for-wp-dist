<?php

declare(strict_types=1);

namespace PoPSchema\Posts\Conditional\Users\Conditional\RESTAPI\RouteModuleProcessors;

use PoP\ModuleRouting\AbstractEntryRouteModuleProcessor;
use PoP\ComponentModel\State\ApplicationState;
use PoP\Hooks\Facades\HooksAPIFacade;
use PoP\API\Facades\FieldQueryConvertorFacade;
use PoPSchema\Users\Routing\RouteNatures;
use PoP\RESTAPI\DataStructureFormatters\RESTDataStructureFormatter;
use PoPSchema\CustomPosts\Conditional\RESTAPI\RouteModuleProcessorHelpers\EntryRouteModuleProcessorHelpers;
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
            // Same as for posts, but removing the user data
            self::$restFieldsQuery = (string) HooksAPIFacade::getInstance()->applyFilters('Users:Posts:RESTFields', str_replace(',' . \PoPSchema\Users\Conditional\CustomPosts\Conditional\RESTAPI\Hooks\CustomPostHooks::AUTHOR_RESTFIELDS, '', EntryRouteModuleProcessorHelpers::getRESTFieldsQuery()));
        }
        return self::$restFieldsQuery;
    }

    /**
     * @return array<string, array<string, array<array>>>
     */
    public function getModulesVarsPropertiesByNatureAndRoute(): array
    {
        $ret = array();
        $vars = ApplicationState::getVars();
        // Author's posts
        $routemodules = array(
            POP_POSTS_ROUTE_POSTS => [
                \PoP_Users_Posts_Module_Processor_FieldDataloads::class,
                \PoP_Users_Posts_Module_Processor_FieldDataloads::MODULE_DATALOAD_RELATIONALFIELDS_AUTHORPOSTLIST,
                [
                    'fields' => isset($vars['query']) ?
                        $vars['query'] :
                        self::getRESTFields()
                    ]
                ],
        );
        foreach ($routemodules as $route => $module) {
            $ret[RouteNatures::USER][$route][] = [
                'module' => $module,
                'conditions' => [
                    'scheme' => APISchemes::API,
                    'datastructure' => RESTDataStructureFormatter::getName(),
                ],
            ];
        }
        return $ret;
    }
}