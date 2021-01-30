<?php

declare (strict_types=1);
namespace PoPSchema\CustomPosts\Hooks;

use PoPSchema\CustomPosts\Constants\ModelInstanceComponentTypes;
use PoP\Hooks\AbstractHookSet;
use PoP\ComponentModel\ModelInstance\ModelInstance;
use PoP\Translation\Facades\TranslationAPIFacade;
use PoP\Hooks\Facades\HooksAPIFacade;
use PoPSchema\CustomPosts\Routing\RouteNatures;
use PoP\ComponentModel\State\ApplicationState;
class VarsHooks extends \PoP\Hooks\AbstractHookSet
{
    protected function init()
    {
        $this->hooksAPI->addFilter(\PoP\ComponentModel\ModelInstance\ModelInstance::HOOK_COMPONENTS_RESULT, array($this, 'getModelInstanceComponentsFromVars'));
    }
    public function getModelInstanceComponentsFromVars($components)
    {
        $vars = \PoP\ComponentModel\State\ApplicationState::getVars();
        $nature = $vars['nature'];
        // Properties specific to each nature
        switch ($nature) {
            case \PoPSchema\CustomPosts\Routing\RouteNatures::CUSTOMPOST:
                // Single may depend on its post_type and category
                // Post and Event may be different
                // Announcements and Articles (Posts), or Past Event and (Upcoming) Event may be different
                // By default, we check for post type but not for categories
                $component_types = (array) \PoP\Hooks\Facades\HooksAPIFacade::getInstance()->applyFilters('\\PoP\\ComponentModel\\ModelInstanceProcessor_Utils:components_from_vars:type:single', array(\PoPSchema\CustomPosts\Constants\ModelInstanceComponentTypes::SINGLE_CUSTOMPOST));
                if (\in_array(\PoPSchema\CustomPosts\Constants\ModelInstanceComponentTypes::SINGLE_CUSTOMPOST, $component_types)) {
                    $customPostType = $vars['routing-state']['queried-object-post-type'];
                    $components[] = \PoP\Translation\Facades\TranslationAPIFacade::getInstance()->__('post type:', 'pop-engine') . $customPostType;
                }
                break;
        }
        return $components;
    }
}
