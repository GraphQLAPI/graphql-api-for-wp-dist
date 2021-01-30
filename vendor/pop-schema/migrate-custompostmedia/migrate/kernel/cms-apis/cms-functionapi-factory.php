<?php

namespace PoPSchema\Media;

class PostsFunctionAPIFactory
{
    protected static $instance;
    public static function setInstance(\PoPSchema\Media\PostsFunctionAPI $instance)
    {
        self::$instance = $instance;
    }
    public static function getInstance() : \PoPSchema\Media\PostsFunctionAPI
    {
        return self::$instance;
    }
}
