<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace PrefixedByPoP\Symfony\Component\Cache\Adapter;

use PrefixedByPoP\Psr\Cache\CacheItemInterface;
use PrefixedByPoP\Symfony\Component\Cache\CacheItem;
use PrefixedByPoP\Symfony\Contracts\Cache\CacheInterface;
/**
 * @author Titouan Galopin <galopintitouan@gmail.com>
 */
class NullAdapter implements AdapterInterface, CacheInterface
{
    private static $createCacheItem;
    public function __construct()
    {
        self::$createCacheItem = self::$createCacheItem ?? \Closure::bind(static function ($key) {
            $item = new CacheItem();
            $item->key = $key;
            $item->isHit = \false;
            return $item;
        }, null, CacheItem::class);
    }
    /**
     * @return mixed
     * @param string $key
     * @param callable $callback
     * @param float|null $beta
     * @param mixed[]|null $metadata
     */
    public function get($key, $callback, $beta = null, &$metadata = null)
    {
        $save = \true;
        return $callback((self::$createCacheItem)($key), $save);
    }
    /**
     * @param mixed $key
     */
    public function getItem($key) : \PrefixedByPoP\Psr\Cache\CacheItemInterface
    {
        return (self::$createCacheItem)($key);
    }
    /**
     * @param mixed[] $keys
     */
    public function getItems($keys = []) : iterable
    {
        return $this->generateItems($keys);
    }
    /**
     * @param mixed $key
     */
    public function hasItem($key) : bool
    {
        return \false;
    }
    /**
     * @param string $prefix
     */
    public function clear($prefix = '') : bool
    {
        return \true;
    }
    /**
     * @param mixed $key
     */
    public function deleteItem($key) : bool
    {
        return \true;
    }
    /**
     * @param mixed[] $keys
     */
    public function deleteItems($keys) : bool
    {
        return \true;
    }
    /**
     * @param \Psr\Cache\CacheItemInterface $item
     */
    public function save($item) : bool
    {
        return \true;
    }
    /**
     * @param \Psr\Cache\CacheItemInterface $item
     */
    public function saveDeferred($item) : bool
    {
        return \true;
    }
    public function commit() : bool
    {
        return \true;
    }
    /**
     * @param string $key
     */
    public function delete($key) : bool
    {
        return $this->deleteItem($key);
    }
    private function generateItems(array $keys) : \Generator
    {
        $f = self::$createCacheItem;
        foreach ($keys as $key) {
            (yield $key => $f($key));
        }
    }
}
