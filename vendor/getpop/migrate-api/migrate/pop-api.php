<?php

namespace PoP\API;

use PoP\Hooks\Facades\HooksAPIFacade;
class Plugin
{
    public function __construct()
    {
        // Allow the Theme to override definitions.
        // Priority: new section, after PoP CMS section
        \PoP\Hooks\Facades\HooksAPIFacade::getInstance()->addAction('plugins_loaded', array($this, 'init'), 88830);
    }
    public function init()
    {
        require_once 'library/load.php';
    }
}
/**
 * Initialization
 */
new \PoP\API\Plugin();
