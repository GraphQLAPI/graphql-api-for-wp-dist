<?php

declare (strict_types=1);
namespace PoP\ComponentModel\Facades\Cache;

use PoP\ComponentModel\Cache\CacheInterface;
use PoP\Root\Container\ContainerBuilderFactory;
class MemoryManagerFacade
{
    public static function getInstance() : \PoP\ComponentModel\Cache\CacheInterface
    {
        /**
         * @var CacheInterface
         */
        $service = \PoP\Root\Container\ContainerBuilderFactory::getInstance()->get('memory_cache');
        return $service;
    }
}
