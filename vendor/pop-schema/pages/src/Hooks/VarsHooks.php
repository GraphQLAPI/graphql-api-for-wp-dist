<?php

declare (strict_types=1);
namespace PoPSchema\Pages\Hooks;

use PoPSchema\Pages\Constants\ModelInstanceComponentTypes;
use PoP\Hooks\AbstractHookSet;
use PoP\ComponentModel\ModelInstance\ModelInstance;
use PoP\Translation\Facades\TranslationAPIFacade;
use PoP\Hooks\Facades\HooksAPIFacade;
use PoP\ComponentModel\State\ApplicationState;
use PoPSchema\Pages\Routing\RouteNatures;
class VarsHooks extends \PoP\Hooks\AbstractHookSet
{
    protected function init()
    {
        $this->hooksAPI->addFilter(\PoP\ComponentModel\ModelInstance\ModelInstance::HOOK_COMPONENTS_RESULT, array($this, 'getModelInstanceComponentsFromVars'));
    }
    public function getModelInstanceComponentsFromVars($components)
    {
        $vars = \PoP\ComponentModel\State\ApplicationState::getVars();
        switch ($vars['nature']) {
            case \PoPSchema\Pages\Routing\RouteNatures::PAGE:
                $component_types = \PoP\Hooks\Facades\HooksAPIFacade::getInstance()->applyFilters('\\PoPSchema\\Pages\\ModelInstanceProcessor_Utils:components_from_vars:type:page', []);
                if (\in_array(\PoPSchema\Pages\Constants\ModelInstanceComponentTypes::SINGLE_PAGE, $component_types)) {
                    $page_id = $vars['routing-state']['queried-object-id'];
                    $components[] = \PoP\Translation\Facades\TranslationAPIFacade::getInstance()->__('page id:', 'pop-engine') . $page_id;
                }
                break;
        }
        return $components;
    }
}
