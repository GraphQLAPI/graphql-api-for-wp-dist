<?php

namespace PoPSchema\PostTags;

class FunctionAPIFactory
{
    protected static $instance;
    public static function setInstance(\PoPSchema\PostTags\FunctionAPI $instance)
    {
        self::$instance = $instance;
    }
    public static function getInstance() : \PoPSchema\PostTags\FunctionAPI
    {
        return self::$instance;
    }
}
