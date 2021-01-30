<?php

namespace PoP\ComponentModel\Settings;

class SiteConfigurationProcessorBase
{
    public function __construct()
    {
        \PoP\ComponentModel\Settings\SiteConfigurationProcessorManagerFactory::getInstance()->set($this);
    }
    public function getEntryModule() : ?array
    {
        return null;
    }
}
