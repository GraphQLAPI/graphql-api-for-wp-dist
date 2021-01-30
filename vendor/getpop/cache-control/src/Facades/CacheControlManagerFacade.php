<?php

declare (strict_types=1);
namespace PoP\CacheControl\Facades;

use PoP\CacheControl\Managers\CacheControlManagerInterface;
use PoP\Root\Container\ContainerBuilderFactory;
class CacheControlManagerFacade
{
    public static function getInstance() : \PoP\CacheControl\Managers\CacheControlManagerInterface
    {
        /**
         * @var CacheControlManagerInterface
         */
        $service = \PoP\Root\Container\ContainerBuilderFactory::getInstance()->get(\PoP\CacheControl\Managers\CacheControlManagerInterface::class);
        return $service;
    }
}
