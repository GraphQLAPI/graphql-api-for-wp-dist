<?php

namespace PoPSchema\Comments;

abstract class FunctionAPI_Base implements \PoPSchema\Comments\FunctionAPI
{
    public function __construct()
    {
        \PoPSchema\Comments\FunctionAPIFactory::setInstance($this);
    }
}
