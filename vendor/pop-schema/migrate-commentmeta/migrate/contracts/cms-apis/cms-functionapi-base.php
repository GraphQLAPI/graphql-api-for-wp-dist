<?php

namespace PoPSchema\CommentMeta;

abstract class FunctionAPI_Base implements \PoPSchema\CommentMeta\FunctionAPI
{
    public function __construct()
    {
        \PoPSchema\CommentMeta\FunctionAPIFactory::setInstance($this);
    }
    public function getMetaKey($meta_key)
    {
        return $meta_key;
    }
}
