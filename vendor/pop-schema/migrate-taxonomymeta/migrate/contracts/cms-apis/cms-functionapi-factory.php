<?php

namespace PoPSchema\TaxonomyMeta;

class FunctionAPIFactory
{
    protected static $instance;
    public static function setInstance(\PoPSchema\TaxonomyMeta\FunctionAPI $instance)
    {
        self::$instance = $instance;
    }
    public static function getInstance() : \PoPSchema\TaxonomyMeta\FunctionAPI
    {
        return self::$instance;
    }
}
