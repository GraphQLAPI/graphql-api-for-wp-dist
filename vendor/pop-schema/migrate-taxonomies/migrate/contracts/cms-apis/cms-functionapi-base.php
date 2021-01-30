<?php

namespace PoPSchema\Taxonomies;

abstract class FunctionAPI_Base implements \PoPSchema\Taxonomies\FunctionAPI
{
    public function __construct()
    {
        \PoPSchema\Taxonomies\FunctionAPIFactory::setInstance($this);
    }
}
