<?php

declare (strict_types=1);
namespace PoP\ComponentModel\Facades\Engine;

use PoP\ComponentModel\Engine\DataloadingEngineInterface;
use PoP\Root\Container\ContainerBuilderFactory;
class DataloadingEngineFacade
{
    public static function getInstance() : \PoP\ComponentModel\Engine\DataloadingEngineInterface
    {
        /**
         * @var DataloadingEngineInterface
         */
        $service = \PoP\Root\Container\ContainerBuilderFactory::getInstance()->get(\PoP\ComponentModel\Engine\DataloadingEngineInterface::class);
        return $service;
    }
}
