<?php

namespace PoPSchema\Users;

class FunctionAPIFactory
{
    protected static $instance;
    public static function setInstance(\PoPSchema\Users\FunctionAPI $instance)
    {
        self::$instance = $instance;
    }
    public static function getInstance() : \PoPSchema\Users\FunctionAPI
    {
        return self::$instance;
    }
}
