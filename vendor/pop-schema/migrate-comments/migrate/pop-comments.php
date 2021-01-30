<?php

/*
Plugin Name: PoP Comments
Version: 0.1
Description: The foundation for a PoP Comments
Plugin URI: https://getpop.org/
Author: Leonardo Losoviz
*/
namespace PoPSchema\Comments;

use PoP\Hooks\Facades\HooksAPIFacade;
//-------------------------------------------------------------------------------------
// Constants Definition
//-------------------------------------------------------------------------------------
\define('POP_COMMENTS_VERSION', 0.106);
\define('POP_COMMENTS_DIR', \dirname(__FILE__));
class Plugins
{
    public function __construct()
    {
        // Priority: new section, after PoP Posts
        \PoP\Hooks\Facades\HooksAPIFacade::getInstance()->addAction('plugins_loaded', array($this, 'init'), 210);
    }
    public function init()
    {
        if ($this->validate()) {
            $this->initialize();
            \define('POP_COMMENTS_INITIALIZED', \true);
        }
    }
    public function validate()
    {
        return \true;
        include_once 'validation.php';
        $validation = new \PoPSchema\Comments\Validation();
        return $validation->validate();
    }
    public function initialize()
    {
        include_once 'initialization.php';
        $initialization = new \PoPSchema\Comments\Initialization();
        return $initialization->initialize();
    }
}
/**
 * Initialization
 */
new \PoPSchema\Comments\Plugins();
