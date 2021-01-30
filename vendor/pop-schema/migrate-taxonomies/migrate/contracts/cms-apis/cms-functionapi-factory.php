<?php

namespace PoPSchema\Taxonomies;

class FunctionAPIFactory
{
    protected static $instance;
    public static function setInstance(\PoPSchema\Taxonomies\FunctionAPI $instance)
    {
        self::$instance = $instance;
    }
    public static function getInstance() : \PoPSchema\Taxonomies\FunctionAPI
    {
        return self::$instance;
    }
}
