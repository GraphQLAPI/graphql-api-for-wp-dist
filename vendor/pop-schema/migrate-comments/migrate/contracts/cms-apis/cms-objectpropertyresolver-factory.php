<?php

namespace PoPSchema\Comments;

class ObjectPropertyResolverFactory
{
    protected static $instance;
    public static function setInstance(\PoPSchema\Comments\ObjectPropertyResolver $instance)
    {
        self::$instance = $instance;
    }
    public static function getInstance() : \PoPSchema\Comments\ObjectPropertyResolver
    {
        return self::$instance;
    }
}
