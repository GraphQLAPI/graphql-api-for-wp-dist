<?php

namespace PoPSchema\Comments;

class FunctionAPIFactory
{
    protected static $instance;
    public static function setInstance(\PoPSchema\Comments\FunctionAPI $instance)
    {
        self::$instance = $instance;
    }
    public static function getInstance() : \PoPSchema\Comments\FunctionAPI
    {
        return self::$instance;
    }
}
