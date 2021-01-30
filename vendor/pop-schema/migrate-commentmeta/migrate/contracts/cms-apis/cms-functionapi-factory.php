<?php

namespace PoPSchema\CommentMeta;

class FunctionAPIFactory
{
    protected static $instance;
    public static function setInstance(\PoPSchema\CommentMeta\FunctionAPI $instance)
    {
        self::$instance = $instance;
    }
    public static function getInstance() : \PoPSchema\CommentMeta\FunctionAPI
    {
        return self::$instance;
    }
}
