<?php

declare (strict_types=1);
namespace PoP\ComponentModel\Facades\Cache;

use PrefixedByPoP\Psr\Cache\CacheItemPoolInterface;
use PoP\Root\Container\ContainerBuilderFactory;
class MemoryManagerItemPoolFacade
{
    public static function getInstance() : \PrefixedByPoP\Psr\Cache\CacheItemPoolInterface
    {
        /**
         * @var CacheItemPoolInterface
         */
        $service = \PoP\Root\Container\ContainerBuilderFactory::getInstance()->get('memory_cache_item_pool');
        return $service;
    }
}
