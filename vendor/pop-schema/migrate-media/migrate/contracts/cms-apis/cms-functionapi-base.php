<?php

namespace PoPSchema\Media;

abstract class FunctionAPI_Base implements \PoPSchema\Media\FunctionAPI
{
    public function __construct()
    {
        \PoPSchema\Media\FunctionAPIFactory::setInstance($this);
    }
}
