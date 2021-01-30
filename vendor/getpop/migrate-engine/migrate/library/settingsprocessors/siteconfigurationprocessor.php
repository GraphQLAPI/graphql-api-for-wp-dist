<?php

namespace PoP\Engine\Settings\Impl;

use PoP\ModuleRouting\Facades\RouteModuleProcessorManagerFacade;
use PoP\ModuleRouting\ModuleRoutingGroups;
class PageModuleSiteConfigurationProcessor extends \PoP\ComponentModel\Settings\SiteConfigurationProcessorBase
{
    public function getEntryModule() : ?array
    {
        $pop_module_routemoduleprocessor_manager = \PoP\ModuleRouting\Facades\RouteModuleProcessorManagerFacade::getInstance();
        return $pop_module_routemoduleprocessor_manager->getRouteModuleByMostAllmatchingVarsProperties(\PoP\ModuleRouting\ModuleRoutingGroups::ENTRYMODULE);
    }
}
/**
 * Initialization
 */
new \PoP\Engine\Settings\Impl\PageModuleSiteConfigurationProcessor();
