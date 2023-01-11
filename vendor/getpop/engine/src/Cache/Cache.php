<?php

declare (strict_types=1);
namespace PoP\Engine\Cache;

use PoP\ComponentModel\Cache\Cache as UpstreamCache;
use PrefixedByPoP\Psr\Cache\CacheItemInterface;
class Cache extends UpstreamCache
{
    /**
     * Override to save as deferred, on hook "popcms:shutdown"
     * @param \Psr\Cache\CacheItemInterface $cacheItem
     */
    protected function saveCache($cacheItem) : void
    {
        $this->cacheItemPool->saveDeferred($cacheItem);
    }
}
