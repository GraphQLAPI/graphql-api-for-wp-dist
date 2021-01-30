<?php

namespace PoPSchema\Users;

class ObjectPropertyResolverFactory
{
    protected static $instance;
    public static function setInstance(\PoPSchema\Users\ObjectPropertyResolver $instance)
    {
        self::$instance = $instance;
    }
    public static function getInstance() : \PoPSchema\Users\ObjectPropertyResolver
    {
        return self::$instance;
    }
}
