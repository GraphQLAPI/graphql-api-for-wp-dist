<?php

namespace PoPSchema\CustomPostMeta;

abstract class FunctionAPI_Base implements \PoPSchema\CustomPostMeta\FunctionAPI
{
    public function __construct()
    {
        \PoPSchema\CustomPostMeta\FunctionAPIFactory::setInstance($this);
    }
    public function getMetaKey($meta_key)
    {
        return $meta_key;
    }
}
