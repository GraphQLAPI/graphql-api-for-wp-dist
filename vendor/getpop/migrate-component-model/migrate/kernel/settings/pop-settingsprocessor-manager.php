<?php

namespace PoP\ComponentModel\Settings;

class SettingsProcessorManager extends \PoP\ComponentModel\Settings\AbstractSettingsProcessorManager
{
    public function __construct()
    {
        parent::__construct();
        \PoP\ComponentModel\Settings\SettingsProcessorManagerFactory::setInstance($this);
    }
}
/**
 * Initialization
 */
new \PoP\ComponentModel\Settings\SettingsProcessorManager();
