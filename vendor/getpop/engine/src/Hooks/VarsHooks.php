<?php

declare (strict_types=1);
namespace PoP\Engine\Hooks;

use PoP\Hooks\AbstractHookSet;
use PoP\Translation\Facades\TranslationAPIFacade;
use PoP\ComponentModel\ModelInstance\ModelInstance;
use PoP\Engine\ComponentConfiguration;
class VarsHooks extends \PoP\Hooks\AbstractHookSet
{
    protected function init()
    {
        $this->hooksAPI->addFilter(\PoP\ComponentModel\ModelInstance\ModelInstance::HOOK_COMPONENTS_RESULT, array($this, 'getModelInstanceComponentsFromVars'));
    }
    public function getModelInstanceComponentsFromVars($components)
    {
        // Removing fields changes the configuration
        $components[] = \PoP\Translation\Facades\TranslationAPIFacade::getInstance()->__('disable redundant root fields:', 'pop-engine') . \PoP\Engine\ComponentConfiguration::disableRedundantRootTypeMutationFields();
        return $components;
    }
}
