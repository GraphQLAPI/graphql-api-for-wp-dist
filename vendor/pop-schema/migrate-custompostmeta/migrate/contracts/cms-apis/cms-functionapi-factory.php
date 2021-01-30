<?php

namespace PoPSchema\CustomPostMeta;

class FunctionAPIFactory
{
    protected static $instance;
    public static function setInstance(\PoPSchema\CustomPostMeta\FunctionAPI $instance)
    {
        self::$instance = $instance;
    }
    public static function getInstance() : \PoPSchema\CustomPostMeta\FunctionAPI
    {
        return self::$instance;
    }
}
