<?php

namespace PoP\ComponentModel\Settings;

abstract class DefaultSettingsProcessorBase extends \PoP\ComponentModel\Settings\SettingsProcessorBase
{
    public function init()
    {
        parent::init();
        \PoP\ComponentModel\Settings\SettingsProcessorManagerFactory::getInstance()->setDefault($this);
    }
}
