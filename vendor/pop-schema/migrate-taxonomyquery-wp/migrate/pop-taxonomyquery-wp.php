<?php
/*
Plugin Name: PoP Taxonomy Query for WordPress
Version: 0.1
Description: Implementation of WordPress functions for PoP CMS
Plugin URI: https://getpop.org/
Author: Leonardo Losoviz
*/
namespace PoPSchema\TaxonomyQuery\WP;
use PoP\Hooks\Facades\HooksAPIFacade;

//-------------------------------------------------------------------------------------
// Constants Definition
//-------------------------------------------------------------------------------------
define('POP_TAXONOMYQUERYWP_VERSION', 0.106);
define('POP_TAXONOMYQUERYWP_DIR', dirname(__FILE__));

class Plugin
{
    public function __construct()
    {
        include_once 'validation.php';
        HooksAPIFacade::getInstance()->addFilter(
            'PoP_TaxonomyQuery_Validation:provider-validation-class',
            array($this, 'getProviderValidationClass')
        );

        // Priority: mid section, after PoP CMS Model WP and PoP TaxonomyQuery WP
        HooksAPIFacade::getInstance()->addAction('plugins_loaded', array($this, 'init'), 888260);
    }
    public function getProviderValidationClass($class)
    {
        return Validation::class;
    }

    public function init()
    {
        if ($this->validate()) {
            $this->initialize();
            define('POP_TAXONOMYQUERYWP_INITIALIZED', true);
        }
    }
    public function validate()
    {
        return true;
        // require_once 'validation.php';
        $validation = new Validation();
        return $validation->validate();
    }
    public function initialize()
    {
        include_once 'initialization.php';
        $initialization = new Initialization();
        return $initialization->initialize();
    }
}

/**
 * Initialization
 */
new Plugin();
