<?php

namespace PoP\Engine;

abstract class FunctionAPI_Base implements \PoP\Engine\FunctionAPI
{
    public function __construct()
    {
        \PoP\Engine\FunctionAPIFactory::setInstance($this);
    }
    public function getVersion()
    {
        return '';
    }
    public function getHost() : string
    {
        return removeScheme($this->getHomeURL());
    }
    public function getDate($format, $date)
    {
        return \date($format, \strtotime($date));
    }
}
