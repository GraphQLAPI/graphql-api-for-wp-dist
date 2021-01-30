<?php

namespace PoPSchema\PostTags;

abstract class FunctionAPI_Base implements \PoPSchema\PostTags\FunctionAPI
{
    public function __construct()
    {
        \PoPSchema\PostTags\FunctionAPIFactory::setInstance($this);
    }
}
