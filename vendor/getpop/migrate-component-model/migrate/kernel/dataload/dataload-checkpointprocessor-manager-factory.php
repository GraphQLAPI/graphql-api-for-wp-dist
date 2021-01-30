<?php

namespace PoP\ComponentModel;

class CheckpointProcessorManagerFactory
{
    protected static $instance;
    public static function setInstance(\PoP\ComponentModel\CheckpointProcessorManager $instance)
    {
        self::$instance = $instance;
    }
    public static function getInstance() : \PoP\ComponentModel\CheckpointProcessorManager
    {
        return self::$instance;
    }
}
