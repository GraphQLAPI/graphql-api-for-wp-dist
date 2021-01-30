<?php

/*
Plugin Name: PoP Users
Version: 0.1
Description: The foundation for a PoP Users
Plugin URI: https://getpop.org/
Author: Leonardo Losoviz
*/
namespace PoPSchema\Users;

use PoP\Hooks\Facades\HooksAPIFacade;
//-------------------------------------------------------------------------------------
// Constants Definition
//-------------------------------------------------------------------------------------
\define('POP_USERS_VERSION', 0.106);
\define('POP_USERS_DIR', \dirname(__FILE__));
class Plugins
{
    public function __construct()
    {
        // Priority: new section, after PoP Posts
        \PoP\Hooks\Facades\HooksAPIFacade::getInstance()->addAction('plugins_loaded', array($this, 'init'), 201);
    }
    public function init()
    {
        if ($this->validate()) {
            $this->initialize();
            \define('POP_USERS_INITIALIZED', \true);
        }
    }
    public function validate()
    {
        return \true;
        include_once 'validation.php';
        $validation = new \PoPSchema\Users\Validation();
        return $validation->validate();
    }
    public function initialize()
    {
        include_once 'initialization.php';
        $initialization = new \PoPSchema\Users\Initialization();
        return $initialization->initialize();
    }
}
/**
 * Initialization
 */
new \PoPSchema\Users\Plugins();
