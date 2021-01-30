<?php

declare (strict_types=1);
namespace PoP\Engine\Hooks\Misc;

use PoP\Hooks\AbstractHookSet;
use PoP\Engine\ModuleFilters\HeadModule;
class URLHookSet extends \PoP\Hooks\AbstractHookSet
{
    protected function init()
    {
        $this->hooksAPI->addFilter('RequestUtils:current_url:remove_params', [$this, 'getParamsToRemoveFromURL']);
    }
    public function getParamsToRemoveFromURL($params)
    {
        $params[] = \PoP\Engine\ModuleFilters\HeadModule::URLPARAM_HEADMODULE;
        return $params;
    }
}
