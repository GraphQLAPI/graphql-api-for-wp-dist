<?php

namespace PoPSchema\UserMeta;

class FunctionAPIFactory
{
    protected static $instance;
    public static function setInstance(\PoPSchema\UserMeta\FunctionAPI $instance)
    {
        self::$instance = $instance;
    }
    public static function getInstance() : \PoPSchema\UserMeta\FunctionAPI
    {
        return self::$instance;
    }
}
