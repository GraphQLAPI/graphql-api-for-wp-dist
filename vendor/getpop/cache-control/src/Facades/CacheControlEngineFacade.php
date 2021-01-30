<?php

declare (strict_types=1);
namespace PoP\CacheControl\Facades;

use PoP\CacheControl\Managers\CacheControlEngineInterface;
use PoP\Root\Container\ContainerBuilderFactory;
class CacheControlEngineFacade
{
    public static function getInstance() : \PoP\CacheControl\Managers\CacheControlEngineInterface
    {
        /**
         * @var CacheControlEngineInterface
         */
        $service = \PoP\Root\Container\ContainerBuilderFactory::getInstance()->get(\PoP\CacheControl\Managers\CacheControlEngineInterface::class);
        return $service;
    }
}
