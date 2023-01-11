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
use PrefixedByPoP\Symfony\Component\Cache\PruneableInterface;
use PrefixedByPoP\Symfony\Component\Cache\ResettableInterface;
use PrefixedByPoP\Symfony\Contracts\Cache\CacheInterface;
use PrefixedByPoP\Symfony\Contracts\Service\ResetInterface;
/**
 * An adapter that collects data about all cache calls.
 *
 * @author Aaron Scherer <aequasi@gmail.com>
 * @author Tobias Nyholm <tobias.nyholm@gmail.com>
 * @author Nicolas Grekas <p@tchwork.com>
 */
class TraceableAdapter implements AdapterInterface, CacheInterface, PruneableInterface, ResettableInterface
{
    protected $pool;
    /**
     * @var mixed[]
     */
    private $calls = [];
    public function __construct(AdapterInterface $pool)
    {
        $this->pool = $pool;
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
        if (!$this->pool instanceof CacheInterface) {
            throw new \BadMethodCallException(\sprintf('Cannot call "%s::get()": this class doesn\'t implement "%s".', \get_debug_type($this->pool), CacheInterface::class));
        }
        $isHit = \true;
        $callback = function (CacheItem $item, bool &$save) use($callback, &$isHit) {
            $isHit = $item->isHit();
            return $callback($item, $save);
        };
        $event = $this->start(__FUNCTION__);
        try {
            $value = $this->pool->get($key, $callback, $beta, $metadata);
            $event->result[$key] = \get_debug_type($value);
        } finally {
            $event->end = \microtime(\true);
        }
        if ($isHit) {
            ++$event->hits;
        } else {
            ++$event->misses;
        }
        return $value;
    }
    /**
     * @param mixed $key
     */
    public function getItem($key) : \PrefixedByPoP\Psr\Cache\CacheItemInterface
    {
        $event = $this->start(__FUNCTION__);
        try {
            $item = $this->pool->getItem($key);
        } finally {
            $event->end = \microtime(\true);
        }
        if ($event->result[$key] = $item->isHit()) {
            ++$event->hits;
        } else {
            ++$event->misses;
        }
        return $item;
    }
    /**
     * @param mixed $key
     */
    public function hasItem($key) : bool
    {
        $event = $this->start(__FUNCTION__);
        try {
            return $event->result[$key] = $this->pool->hasItem($key);
        } finally {
            $event->end = \microtime(\true);
        }
    }
    /**
     * @param mixed $key
     */
    public function deleteItem($key) : bool
    {
        $event = $this->start(__FUNCTION__);
        try {
            return $event->result[$key] = $this->pool->deleteItem($key);
        } finally {
            $event->end = \microtime(\true);
        }
    }
    /**
     * @param \Psr\Cache\CacheItemInterface $item
     */
    public function save($item) : bool
    {
        $event = $this->start(__FUNCTION__);
        try {
            return $event->result[$item->getKey()] = $this->pool->save($item);
        } finally {
            $event->end = \microtime(\true);
        }
    }
    /**
     * @param \Psr\Cache\CacheItemInterface $item
     */
    public function saveDeferred($item) : bool
    {
        $event = $this->start(__FUNCTION__);
        try {
            return $event->result[$item->getKey()] = $this->pool->saveDeferred($item);
        } finally {
            $event->end = \microtime(\true);
        }
    }
    /**
     * @param mixed[] $keys
     */
    public function getItems($keys = []) : iterable
    {
        $event = $this->start(__FUNCTION__);
        try {
            $result = $this->pool->getItems($keys);
        } finally {
            $event->end = \microtime(\true);
        }
        $f = function () use($result, $event) {
            $event->result = [];
            foreach ($result as $key => $item) {
                if ($event->result[$key] = $item->isHit()) {
                    ++$event->hits;
                } else {
                    ++$event->misses;
                }
                (yield $key => $item);
            }
        };
        return $f();
    }
    /**
     * @param string $prefix
     */
    public function clear($prefix = '') : bool
    {
        $event = $this->start(__FUNCTION__);
        try {
            if ($this->pool instanceof AdapterInterface) {
                return $event->result = $this->pool->clear($prefix);
            }
            return $event->result = $this->pool->clear();
        } finally {
            $event->end = \microtime(\true);
        }
    }
    /**
     * @param mixed[] $keys
     */
    public function deleteItems($keys) : bool
    {
        $event = $this->start(__FUNCTION__);
        $event->result['keys'] = $keys;
        try {
            return $event->result['result'] = $this->pool->deleteItems($keys);
        } finally {
            $event->end = \microtime(\true);
        }
    }
    public function commit() : bool
    {
        $event = $this->start(__FUNCTION__);
        try {
            return $event->result = $this->pool->commit();
        } finally {
            $event->end = \microtime(\true);
        }
    }
    public function prune() : bool
    {
        if (!$this->pool instanceof PruneableInterface) {
            return \false;
        }
        $event = $this->start(__FUNCTION__);
        try {
            return $event->result = $this->pool->prune();
        } finally {
            $event->end = \microtime(\true);
        }
    }
    public function reset()
    {
        if ($this->pool instanceof ResetInterface) {
            $this->pool->reset();
        }
        $this->clearCalls();
    }
    /**
     * @param string $key
     */
    public function delete($key) : bool
    {
        $event = $this->start(__FUNCTION__);
        try {
            return $event->result[$key] = $this->pool->deleteItem($key);
        } finally {
            $event->end = \microtime(\true);
        }
    }
    public function getCalls()
    {
        return $this->calls;
    }
    public function clearCalls()
    {
        $this->calls = [];
    }
    public function getPool() : AdapterInterface
    {
        return $this->pool;
    }
    /**
     * @param string $name
     */
    protected function start($name)
    {
        $this->calls[] = $event = new TraceableAdapterEvent();
        $event->name = $name;
        $event->start = \microtime(\true);
        return $event;
    }
}
/**
 * @internal
 */
class TraceableAdapterEvent
{
    /**
     * @var string
     */
    public $name;
    /**
     * @var float
     */
    public $start;
    /**
     * @var float
     */
    public $end;
    /**
     * @var mixed[]|bool
     */
    public $result;
    /**
     * @var int
     */
    public $hits = 0;
    /**
     * @var int
     */
    public $misses = 0;
}
