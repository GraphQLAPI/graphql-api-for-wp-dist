<?php

declare (strict_types=1);
namespace PoP\Engine\Hooks\ModuleFilters;

use PoP\Hooks\AbstractHookSet;
use PoP\ComponentModel\State\ApplicationState;
use PoP\ComponentModel\ModulePath\ModulePathUtils;
use PoP\ComponentModel\Facades\ModulePath\ModulePathHelpersFacade;
use PoP\ComponentModel\ModelInstance\ModelInstance;
class ModulePaths extends \PoP\Hooks\AbstractHookSet
{
    protected function init()
    {
        $this->hooksAPI->addFilter(\PoP\ComponentModel\ModelInstance\ModelInstance::HOOK_COMPONENTSFROMVARS_RESULT, [$this, 'maybeAddComponent']);
        $this->hooksAPI->addAction('ApplicationState:addVars', [$this, 'addVars'], 10, 1);
    }
    /**
     * @param array<array> $vars_in_array
     */
    public function addVars(array $vars_in_array) : void
    {
        $vars =& $vars_in_array[0];
        if (isset($vars['modulefilter']) && $vars['modulefilter'] == \PoP\ComponentModel\ModuleFilters\ModulePaths::NAME) {
            $vars['modulepaths'] = \PoP\ComponentModel\ModulePath\ModulePathUtils::getModulePaths();
        }
    }
    public function maybeAddComponent($components)
    {
        $vars = \PoP\ComponentModel\State\ApplicationState::getVars();
        if (isset($vars['modulefilter']) && $vars['modulefilter'] == \PoP\ComponentModel\ModuleFilters\ModulePaths::NAME) {
            if ($modulepaths = $vars['modulepaths']) {
                $modulePathHelpers = \PoP\ComponentModel\Facades\ModulePath\ModulePathHelpersFacade::getInstance();
                $paths = \array_map(function ($modulepath) use($modulePathHelpers) {
                    return $modulePathHelpers->stringifyModulePath($modulepath);
                }, $modulepaths);
                $components[] = $this->translationAPI->__('module paths:', 'engine') . \implode(',', $paths);
            }
        }
        return $components;
    }
}
