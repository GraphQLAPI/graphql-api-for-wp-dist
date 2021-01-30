<?php

namespace PoPSchema\Media;

abstract class PostsFunctionAPI_Base implements \PoPSchema\Media\PostsFunctionAPI
{
    public function __construct()
    {
        \PoPSchema\Media\PostsFunctionAPIFactory::setInstance($this);
    }
}
