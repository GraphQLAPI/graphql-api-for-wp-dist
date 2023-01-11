<?php

declare (strict_types=1);
namespace PoP\ComponentModel\Cache;

use DateInterval;
interface CacheInterface
{
    /**
     * @param string $id
     * @param string $type
     */
    public function hasCache($id, $type) : bool;
    /**
     * @return boolean True if the item was successfully removed. False if there was an error.
     * @param string $id
     * @param string $type
     */
    public function deleteCache($id, $type) : bool;
    /**
     * Remove all entries in the cache
     */
    public function clear() : void;
    /**
     * Commit entries in the pool
     */
    public function commit() : void;
    /**
     * @return mixed
     * @param string $id
     * @param string $type
     */
    public function getCache($id, $type);
    /**
     * @return mixed
     * @param string $id
     * @param string $type
     */
    public function getComponentModelCache($id, $type);
    /**
     * Store the cache
     *
     * @param string $id key under which to store the cache
     * @param string $type the type of the cache, used to distinguish groups of caches
     * @param mixed $content the value to cache
     * @param int|DateInterval|null $time time after which the cache expires, in seconds
     */
    public function storeCache($id, $type, $content, $time = null) : void;
    /**
     * @param int|\DateInterval|null $time
     * @param mixed $content
     * @param string $id
     * @param string $type
     */
    public function storeComponentModelCache($id, $type, $content, $time = null) : void;
    /**
     * @return mixed
     * @param string $type
     */
    public function getCacheByModelInstance($type);
    /**
     * @param mixed $content
     * @param string $type
     */
    public function storeCacheByModelInstance($type, $content) : void;
}
