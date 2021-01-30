<?php

namespace PoP\Engine;

class FunctionAPIFactory
{
    protected static $instance;
    public static function setInstance(\PoP\Engine\FunctionAPI $instance)
    {
        self::$instance = $instance;
    }
    public static function getInstance() : \PoP\Engine\FunctionAPI
    {
        return self::$instance;
    }
}
