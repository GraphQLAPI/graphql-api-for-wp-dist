<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace PrefixedByPoP\Symfony\Component\Cache\Messenger;

use PrefixedByPoP\Symfony\Component\Cache\Adapter\AdapterInterface;
use PrefixedByPoP\Symfony\Component\Cache\CacheItem;
use PrefixedByPoP\Symfony\Component\DependencyInjection\ReverseContainer;
/**
 * Conveys a cached value that needs to be computed.
 */
final class EarlyExpirationMessage
{
    private $item;
    private $pool;
    private $callback;
    /**
     * @return $this|null
     */
    public static function create(\PrefixedByPoP\Symfony\Component\DependencyInjection\ReverseContainer $reverseContainer, callable $callback, \PrefixedByPoP\Symfony\Component\Cache\CacheItem $item, \PrefixedByPoP\Symfony\Component\Cache\Adapter\AdapterInterface $pool)
    {
        try {
            $item = clone $item;
            $item->set(null);
        } catch (\Exception $e) {
            return null;
        }
        $pool = $reverseContainer->getId($pool);
        if (\is_object($callback)) {
            if (null === ($id = $reverseContainer->getId($callback))) {
                return null;
            }
            $callback = '@' . $id;
        } elseif (!\is_array($callback)) {
            $callback = (string) $callback;
        } elseif (!\is_object($callback[0])) {
            $callback = [(string) $callback[0], (string) $callback[1]];
        } else {
            if (null === ($id = $reverseContainer->getId($callback[0]))) {
                return null;
            }
            $callback = ['@' . $id, (string) $callback[1]];
        }
        return new self($item, $pool, $callback);
    }
    public function getItem() : \PrefixedByPoP\Symfony\Component\Cache\CacheItem
    {
        return $this->item;
    }
    public function getPool() : string
    {
        return $this->pool;
    }
    public function getCallback()
    {
        return $this->callback;
    }
    public function findPool(\PrefixedByPoP\Symfony\Component\DependencyInjection\ReverseContainer $reverseContainer) : \PrefixedByPoP\Symfony\Component\Cache\Adapter\AdapterInterface
    {
        return $reverseContainer->getService($this->pool);
    }
    public function findCallback(\PrefixedByPoP\Symfony\Component\DependencyInjection\ReverseContainer $reverseContainer) : callable
    {
        if (\is_string($callback = $this->callback)) {
            return '@' === $callback[0] ? $reverseContainer->getService(\substr($callback, 1)) : $callback;
        }
        if ('@' === $callback[0][0]) {
            $callback[0] = $reverseContainer->getService(\substr($callback[0], 1));
        }
        return $callback;
    }
    private function __construct(\PrefixedByPoP\Symfony\Component\Cache\CacheItem $item, string $pool, $callback)
    {
        $this->item = $item;
        $this->pool = $pool;
        $this->callback = $callback;
    }
}
