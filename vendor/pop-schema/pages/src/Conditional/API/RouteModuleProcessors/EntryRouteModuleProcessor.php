<?php

declare (strict_types=1);
namespace PoPSchema\Pages\Conditional\API\RouteModuleProcessors;

use PoP\ModuleRouting\AbstractEntryRouteModuleProcessor;
use PoPSchema\Pages\Routing\RouteNatures;
use PoP\API\Response\Schemes as APISchemes;
class EntryRouteModuleProcessor extends \PoP\ModuleRouting\AbstractEntryRouteModuleProcessor
{
    /**
     * @return array<string, array<array>>
     */
    public function getModulesVarsPropertiesByNature() : array
    {
        $ret = array();
        $ret[\PoPSchema\Pages\Routing\RouteNatures::PAGE][] = ['module' => [\PrefixedByPoP\PoP_Pages_Module_Processor_FieldDataloads::class, \PrefixedByPoP\PoP_Pages_Module_Processor_FieldDataloads::MODULE_DATALOAD_RELATIONALFIELDS_PAGE], 'conditions' => ['scheme' => \PoP\API\Response\Schemes::API]];
        return $ret;
    }
}
