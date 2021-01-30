<?php

namespace PoPSchema\Media;

class FunctionAPIFactory
{
    protected static $instance;
    public static function setInstance(\PoPSchema\Media\FunctionAPI $instance)
    {
        self::$instance = $instance;
    }
    public static function getInstance() : \PoPSchema\Media\FunctionAPI
    {
        return self::$instance;
    }
}
