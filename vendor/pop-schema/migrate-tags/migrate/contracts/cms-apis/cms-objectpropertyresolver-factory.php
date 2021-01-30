<?php

namespace PoPSchema\Tags;

class ObjectPropertyResolverFactory
{
    protected static $instance;
    public static function setInstance(\PoPSchema\Tags\ObjectPropertyResolver $instance)
    {
        self::$instance = $instance;
    }
    public static function getInstance() : \PoPSchema\Tags\ObjectPropertyResolver
    {
        return self::$instance;
    }
}
