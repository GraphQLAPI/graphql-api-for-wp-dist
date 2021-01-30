<?php

namespace PoPSchema\QueriedObject;

class CMSRoutingStateFactory
{
    protected static $instance;
    public static function setInstance(\PoPSchema\QueriedObject\CMSRoutingStateInterface $instance)
    {
        self::$instance = $instance;
    }
    public static function getInstance() : \PoPSchema\QueriedObject\CMSRoutingStateInterface
    {
        return self::$instance;
    }
}
