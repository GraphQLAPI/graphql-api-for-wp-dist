<?php

namespace PoPSchema\Media;

class ObjectPropertyResolverFactory
{
    protected static $instance;
    public static function setInstance(\PoPSchema\Media\ObjectPropertyResolver $instance)
    {
        self::$instance = $instance;
    }
    public static function getInstance() : \PoPSchema\Media\ObjectPropertyResolver
    {
        return self::$instance;
    }
}
