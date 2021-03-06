<?php

/*
Plugin Name: PoP Tags
Version: 0.1
Description: The foundation for a PoP Tags
Plugin URI: https://getpop.org/
Author: Leonardo Losoviz
*/
namespace PoPSchema\Tags;

use PoP\Hooks\Facades\HooksAPIFacade;
//-------------------------------------------------------------------------------------
// Constants Definition
//-------------------------------------------------------------------------------------
\define('POP_TAGS_VERSION', 0.106);
\define('POP_TAGS_DIR', \dirname(__FILE__));
class Plugins
{
    public function __construct()
    {
        // Priority: new section, after PoP Posts
        \PoP\Hooks\Facades\HooksAPIFacade::getInstance()->addAction('plugins_loaded', array($this, 'init'), 888205);
    }
    public function init()
    {
        if ($this->validate()) {
            $this->initialize();
            \define('POP_TAGS_INITIALIZED', \true);
        }
    }
    public function validate()
    {
        return \true;
        include_once 'validation.php';
        $validation = new \PoPSchema\Tags\Validation();
        return $validation->validate();
    }
    public function initialize()
    {
        include_once 'initialization.php';
        $initialization = new \PoPSchema\Tags\Initialization();
        return $initialization->initialize();
    }
}
/**
 * Initialization
 */
new \PoPSchema\Tags\Plugins();
