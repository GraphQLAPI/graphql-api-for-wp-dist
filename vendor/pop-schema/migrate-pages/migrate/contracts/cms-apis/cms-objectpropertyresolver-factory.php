<?php

namespace PoPSchema\Pages;

class ObjectPropertyResolverFactory
{
    protected static $instance;
    public static function setInstance(\PoPSchema\Pages\ObjectPropertyResolver $instance)
    {
        self::$instance = $instance;
    }
    public static function getInstance() : \PoPSchema\Pages\ObjectPropertyResolver
    {
        return self::$instance;
    }
}
