<?php

namespace PoP\ComponentModel;

class StratumManagerFactory
{
    protected static $instance;
    public static function setInstance(\PoP\ComponentModel\StratumManager $instance)
    {
        self::$instance = $instance;
    }
    public static function getInstance() : \PoP\ComponentModel\StratumManager
    {
        return self::$instance;
    }
}
