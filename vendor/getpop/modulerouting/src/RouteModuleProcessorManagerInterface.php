<?php

declare (strict_types=1);
namespace PoP\ModuleRouting;

use PoP\ModuleRouting\AbstractRouteModuleProcessor;
interface RouteModuleProcessorManagerInterface
{
    public function addRouteModuleProcessor(AbstractRouteModuleProcessor $processor) : void;
    /**
     * @return AbstractRouteModuleProcessor[]
     * @param string $group
     */
    public function getProcessors($group = null) : array;
    public function getDefaultGroup() : string;
    /**
     * @return array<string, mixed>
     */
    public function getVars() : array;
    /**
     * @return string[]|null
     * @param string $group
     */
    public function getRouteModuleByMostAllmatchingVarsProperties($group = null) : ?array;
}
