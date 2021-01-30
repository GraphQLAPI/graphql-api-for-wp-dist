<?php

namespace PoPSchema\Users;

abstract class FunctionAPI_Base implements \PoPSchema\Users\FunctionAPI
{
    public function __construct()
    {
        \PoPSchema\Users\FunctionAPIFactory::setInstance($this);
    }
}
