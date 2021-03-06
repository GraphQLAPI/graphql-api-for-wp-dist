<?php

/*
Plugin Name: PoP Taxonomies
Version: 0.1
Description: The foundation for a PoP Taxonomies
Plugin URI: https://getpop.org/
Author: Leonardo Losoviz
*/
namespace PoPSchema\Taxonomies;

use PoP\Hooks\Facades\HooksAPIFacade;
//-------------------------------------------------------------------------------------
// Constants Definition
//-------------------------------------------------------------------------------------
\define('POP_TAXONOMIES_VERSION', 0.106);
\define('POP_TAXONOMIES_DIR', \dirname(__FILE__));
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
            \define('POP_TAXONOMIES_INITIALIZED', \true);
        }
    }
    public function validate()
    {
        return \true;
        include_once 'validation.php';
        $validation = new \PoPSchema\Taxonomies\Validation();
        return $validation->validate();
    }
    public function initialize()
    {
        include_once 'initialization.php';
        $initialization = new \PoPSchema\Taxonomies\Initialization();
        return $initialization->initialize();
    }
}
/**
 * Initialization
 */
new \PoPSchema\Taxonomies\Plugins();
