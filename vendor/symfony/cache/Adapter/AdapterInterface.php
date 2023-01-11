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

use PrefixedByPoP\Psr\Cache\CacheItemPoolInterface;
use PrefixedByPoP\Symfony\Component\Cache\CacheItem;
// Help opcache.preload discover always-needed symbols
\class_exists(CacheItem::class);
/**
 * Interface for adapters managing instances of Symfony's CacheItem.
 *
 * @author Kévin Dunglas <dunglas@gmail.com>
 */
interface AdapterInterface extends CacheItemPoolInterface
{
    /**
     * @param mixed $key
     */
    public function getItem($key) : \PrefixedByPoP\Psr\Cache\CacheItemInterface;
    /**
     * @return iterable<string, CacheItem>
     * @param mixed[] $keys
     */
    public function getItems($keys = []) : iterable;
    /**
     * @param string $prefix
     */
    public function clear($prefix = '') : bool;
}
