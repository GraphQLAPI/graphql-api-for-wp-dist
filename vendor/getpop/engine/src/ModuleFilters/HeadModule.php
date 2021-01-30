<?php

declare (strict_types=1);
namespace PoP\Engine\ModuleFilters;

use PoP\ComponentModel\ModuleFilters\AbstractModuleFilter;
use PoP\ComponentModel\State\ApplicationState;
class HeadModule extends \PoP\ComponentModel\ModuleFilters\AbstractModuleFilter
{
    public const NAME = 'headmodule';
    public const URLPARAM_HEADMODULE = 'headmodule';
    public function getName()
    {
        return self::NAME;
    }
    public function excludeModule(array $module, array &$props)
    {
        $vars = \PoP\ComponentModel\State\ApplicationState::getVars();
        return $vars['headmodule'] != $module;
    }
}
