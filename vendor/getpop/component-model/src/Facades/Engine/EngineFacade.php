<?php

declare (strict_types=1);
namespace PoP\ComponentModel\Facades\Engine;

use PoP\ComponentModel\Engine\EngineInterface;
use PoP\Root\Container\ContainerBuilderFactory;
class EngineFacade
{
    public static function getInstance() : \PoP\ComponentModel\Engine\EngineInterface
    {
        /**
         * @var EngineInterface
         */
        $service = \PoP\Root\Container\ContainerBuilderFactory::getInstance()->get(\PoP\ComponentModel\Engine\EngineInterface::class);
        return $service;
    }
}
