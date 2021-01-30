<?php

namespace PoPSchema\TaxonomyMeta;

abstract class FunctionAPI_Base implements \PoPSchema\TaxonomyMeta\FunctionAPI
{
    public function __construct()
    {
        \PoPSchema\TaxonomyMeta\FunctionAPIFactory::setInstance($this);
    }
    public function getMetaKey($meta_key)
    {
        return $meta_key;
    }
}
