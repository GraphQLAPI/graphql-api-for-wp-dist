<?php

namespace PoP\ComponentModel\Settings;

class SiteConfigurationProcessorManager
{
    public $processor;
    public function __construct()
    {
        \PoP\ComponentModel\Settings\SiteConfigurationProcessorManagerFactory::setInstance($this);
    }
    public function getProcessor()
    {
        return $this->processor;
    }
    public function set($processor)
    {
        $this->processor = $processor;
    }
}
/**
 * Initialization
 */
new \PoP\ComponentModel\Settings\SiteConfigurationProcessorManager();
