<?php

/*
Plugin Name: PoP Post Media
Version: 0.1
Description: The foundation for a PoP Post Media
Plugin URI: https://getpop.org/
Author: Leonardo Losoviz
*/
namespace PoPSchema\CustomPosts;

use PoP\Hooks\Facades\HooksAPIFacade;
//-------------------------------------------------------------------------------------
// Constants Definition
//-------------------------------------------------------------------------------------
\define('POP_CUSTOMPOSTS_VERSION', 0.106);
\define('POP_CUSTOMPOSTS_DIR', \dirname(__FILE__));
class Plugins
{
    public function __construct()
    {
        // Priority: new section, after PoP CMS Model
        \PoP\Hooks\Facades\HooksAPIFacade::getInstance()->addAction('plugins_loaded', array($this, 'init'), 205);
    }
    public function init()
    {
        if ($this->validate()) {
            $this->initialize();
            \define('POP_CUSTOMPOSTS_INITIALIZED', \true);
        }
    }
    public function validate()
    {
        return \true;
        include_once 'validation.php';
        $validation = new \PoPSchema\CustomPosts\Validation();
        return $validation->validate();
    }
    public function initialize()
    {
        include_once 'initialization.php';
        $initialization = new \PoPSchema\CustomPosts\Initialization();
        return $initialization->initialize();
    }
}
/**
 * Initialization
 */
new \PoPSchema\CustomPosts\Plugins();
