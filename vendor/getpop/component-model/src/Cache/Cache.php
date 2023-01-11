<?php

declare (strict_types=1);
namespace PoP\ComponentModel\Cache;

use DateInterval;
use PoP\ComponentModel\ModelInstance\ModelInstanceInterface;
use PoP\Root\Services\BasicServiceTrait;
use PrefixedByPoP\Psr\Cache\CacheItemInterface;
use PrefixedByPoP\Psr\Cache\CacheItemPoolInterface;
class Cache implements \PoP\ComponentModel\Cache\PersistentCacheInterface, \PoP\ComponentModel\Cache\TransientCacheInterface
{
    use BasicServiceTrait;
    use \PoP\ComponentModel\Cache\ReplaceCurrentExecutionDataWithPlaceholdersTrait;
    /**
     * @var \PoP\ComponentModel\ModelInstance\ModelInstanceInterface|null
     */
    private $modelInstance;
    /**
     * @var \Psr\Cache\CacheItemPoolInterface
     */
    protected $cacheItemPool;
    public function __construct(CacheItemPoolInterface $cacheItemPool)
    {
        $this->cacheItemPool = $cacheItemPool;
    }
    /**
     * @param \PoP\ComponentModel\ModelInstance\ModelInstanceInterface $modelInstance
     */
    public final function setModelInstance($modelInstance) : void
    {
        $this->modelInstance = $modelInstance;
    }
    protected final function getModelInstance() : ModelInstanceInterface
    {
        /** @var ModelInstanceInterface */
        return $this->modelInstance = $this->modelInstance ?? $this->instanceManager->getInstance(ModelInstanceInterface::class);
    }
    /**
     * @param string $id
     * @param string $type
     */
    protected function getKey($id, $type) : string
    {
        return $type . '.' . $id;
    }
    /**
     * @param string $id
     * @param string $type
     */
    protected function getCacheItem($id, $type) : CacheItemInterface
    {
        return $this->cacheItemPool->getItem($this->getKey($id, $type));
    }
    /**
     * @param string $id
     * @param string $type
     */
    public function hasCache($id, $type) : bool
    {
        $cacheItem = $this->getCacheItem($id, $type);
        return $cacheItem->isHit();
    }
    /**
     * @return boolean True if the item was successfully removed. False if there was an error.
     * @param string $id
     * @param string $type
     */
    public function deleteCache($id, $type) : bool
    {
        return $this->cacheItemPool->deleteItem($this->getKey($id, $type));
    }
    public function clear() : void
    {
        $this->cacheItemPool->clear();
    }
    public function commit() : void
    {
        $this->cacheItemPool->commit();
    }
    /**
     * If the item is not cached, it will return `null`
     * @see https://www.php-fig.org/psr/psr-6/
     * @return mixed
     * @param string $id
     * @param string $type
     */
    public function getCache($id, $type)
    {
        $cacheItem = $this->getCacheItem($id, $type);
        return $cacheItem->get();
    }
    /**
     * @return mixed
     * @param string $id
     * @param string $type
     */
    public function getComponentModelCache($id, $type)
    {
        $content = $this->getCache($id, $type);
        // Inject the current request data in place of the placeholders (pun not intended!)
        return $this->replacePlaceholdersWithCurrentExecutionData($content);
    }
    /**
     * Store the cache
     *
     * @param string $id key under which to store the cache
     * @param string $type the type of the cache, used to distinguish groups of caches
     * @param mixed $content the value to cache
     * @param int|DateInterval|null $time time after which the cache expires, in seconds
     */
    public function storeCache($id, $type, $content, $time = null) : void
    {
        $cacheItem = $this->getCacheItem($id, $type);
        $cacheItem->set($content);
        $cacheItem->expiresAfter($time);
        $this->saveCache($cacheItem);
    }
    /**
     * Store the cache by component model
     * @param int|\DateInterval|null $time
     * @param mixed $content
     * @param string $id
     * @param string $type
     */
    public function storeComponentModelCache($id, $type, $content, $time = null) : void
    {
        // Before saving the cache, replace the data specific to this execution with generic placeholders
        $content = $this->replaceCurrentExecutionDataWithPlaceholders($content);
        $this->storeCache($id, $type, $content, $time);
    }
    /**
     * Save immediately. Can override to save as deferred
     * @param \Psr\Cache\CacheItemInterface $cacheItem
     */
    protected function saveCache($cacheItem) : void
    {
        $this->cacheItemPool->save($cacheItem);
    }
    /**
     * @return mixed
     * @param string $type
     */
    public function getCacheByModelInstance($type)
    {
        return $this->getComponentModelCache($this->getModelInstance()->getModelInstanceID(), $type);
    }
    /**
     * @param mixed $content
     * @param string $type
     */
    public function storeCacheByModelInstance($type, $content) : void
    {
        $this->storeCache($this->getModelInstance()->getModelInstanceID(), $type, $content);
    }
}
