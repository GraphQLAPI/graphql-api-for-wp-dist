<?php

declare (strict_types=1);
namespace PoP\ComponentModel\Cache;

use DateInterval;
interface CacheInterface
{
    public function hasCache(string $id, string $type) : bool;
    /**
     * @return boolean True if the item was successfully removed. False if there was an error.
     */
    public function deleteCache(string $id, string $type) : bool;
    /**
     * Remove all entries in the cache
     */
    public function clear() : void;
    /**
     * @return mixed
     */
    public function getCache(string $id, string $type);
    /**
     * @return mixed
     */
    public function getComponentModelCache(string $id, string $type);
    /**
     * Store the cache
     *
     * @param string $id key under which to store the cache
     * @param string $type the type of the cache, used to distinguish groups of caches
     * @param mixed $content the value to cache
     * @param int|DateInterval|null $time time after which the cache expires, in seconds
     */
    public function storeCache(string $id, string $type, $content, $time = null) : void;
    /**
     * @param int|\DateInterval|null $time
     * @param mixed $content
     */
    public function storeComponentModelCache(string $id, string $type, $content, $time = null) : void;
    /**
     * @return mixed
     */
    public function getCacheByModelInstance(string $type);
    /**
     * @param mixed $content
     */
    public function storeCacheByModelInstance(string $type, $content) : void;
}
