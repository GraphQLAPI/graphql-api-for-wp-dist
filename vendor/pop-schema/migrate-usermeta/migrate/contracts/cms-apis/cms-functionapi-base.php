<?php

namespace PoPSchema\UserMeta;

abstract class FunctionAPI_Base implements \PoPSchema\UserMeta\FunctionAPI
{
    public function __construct()
    {
        \PoPSchema\UserMeta\FunctionAPIFactory::setInstance($this);
    }
    public function getMetaKey($meta_key)
    {
        return $meta_key;
    }
}
