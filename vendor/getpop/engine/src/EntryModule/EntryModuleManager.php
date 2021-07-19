<?php

declare (strict_types=1);
namespace PoP\Engine\EntryModule;

use PoP\ComponentModel\EntryModule\EntryModuleManagerInterface;
use PoP\ModuleRouting\ModuleRoutingGroups;
use PoP\ModuleRouting\RouteModuleProcessorManagerInterface;
class EntryModuleManager implements EntryModuleManagerInterface
{
    /**
     * @var \PoP\ModuleRouting\RouteModuleProcessorManagerInterface
     */
    protected $routeModuleProcessorManager;
    public function __construct(RouteModuleProcessorManagerInterface $routeModuleProcessorManager)
    {
        $this->routeModuleProcessorManager = $routeModuleProcessorManager;
    }
    public function getEntryModule() : ?array
    {
        return $this->routeModuleProcessorManager->getRouteModuleByMostAllmatchingVarsProperties(ModuleRoutingGroups::ENTRYMODULE);
    }
}
