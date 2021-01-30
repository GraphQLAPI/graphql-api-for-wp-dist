<?php

namespace PoPSchema\CustomPosts;

use PoP\Hooks\Facades\HooksAPIFacade;
use PoPSchema\CustomPosts\Routing\RouteNatures;
use PoPSchema\CustomPosts\Facades\CustomPostTypeAPIFacade;
class Engine_Hooks
{
    public function __construct()
    {
        \PoP\Hooks\Facades\HooksAPIFacade::getInstance()->addAction('augmentVarsProperties', [$this, 'augmentVarsProperties'], 10, 1);
    }
    /**
     * @param array<array> $vars_in_array
     */
    public function augmentVarsProperties(array $vars_in_array) : void
    {
        // Set additional properties based on the nature
        $vars =& $vars_in_array[0];
        $nature = $vars['nature'];
        $vars['routing-state']['is-custompost'] = $nature == \PoPSchema\CustomPosts\Routing\RouteNatures::CUSTOMPOST;
        // Attributes needed to match the RouteModuleProcessor vars conditions
        if ($nature == \PoPSchema\CustomPosts\Routing\RouteNatures::CUSTOMPOST) {
            $customPostTypeAPI = \PoPSchema\CustomPosts\Facades\CustomPostTypeAPIFacade::getInstance();
            $post_id = $vars['routing-state']['queried-object-id'];
            $vars['routing-state']['queried-object-post-type'] = $customPostTypeAPI->getCustomPostType($post_id);
        }
    }
}
/**
 * Initialization
 */
new \PoPSchema\CustomPosts\Engine_Hooks();
