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
use PrefixedByPoP\Psr\Cache\CacheItemPoolInterface;
use PrefixedByPoP\Symfony\Component\Cache\CacheItem;
use PrefixedByPoP\Symfony\Component\Cache\Exception\InvalidArgumentException;
use PrefixedByPoP\Symfony\Component\Cache\PruneableInterface;
use PrefixedByPoP\Symfony\Component\Cache\ResettableInterface;
use PrefixedByPoP\Symfony\Component\Cache\Traits\ContractsTrait;
use PrefixedByPoP\Symfony\Contracts\Cache\CacheInterface;
use PrefixedByPoP\Symfony\Contracts\Service\ResetInterface;
/**
 * Chains several adapters together.
 *
 * Cached items are fetched from the first adapter having them in its data store.
 * They are saved and deleted in all adapters at once.
 *
 * @author Kévin Dunglas <dunglas@gmail.com>
 */
class ChainAdapter implements AdapterInterface, CacheInterface, PruneableInterface, ResettableInterface
{
    use ContractsTrait;
    /**
     * @var mixed[]
     */
    private $adapters = [];
    /**
     * @var int
     */
    private $adapterCount;
    /**
     * @var int
     */
    private $defaultLifetime;
    /**
     * @var \Closure
     */
    private static $syncItem;
    /**
     * @param CacheItemPoolInterface[] $adapters        The ordered list of adapters used to fetch cached items
     * @param int                      $defaultLifetime The default lifetime of items propagated from lower adapters to upper ones
     */
    public function __construct(array $adapters, int $defaultLifetime = 0)
    {
        if (!$adapters) {
            throw new InvalidArgumentException('At least one adapter must be specified.');
        }
        foreach ($adapters as $adapter) {
            if (!$adapter instanceof CacheItemPoolInterface) {
                throw new InvalidArgumentException(\sprintf('The class "%s" does not implement the "%s" interface.', \get_debug_type($adapter), CacheItemPoolInterface::class));
            }
            if (\in_array(\PHP_SAPI, ['cli', 'phpdbg'], \true) && $adapter instanceof ApcuAdapter && !\filter_var(\ini_get('apc.enable_cli'), \FILTER_VALIDATE_BOOL)) {
                continue;
                // skip putting APCu in the chain when the backend is disabled
            }
            if ($adapter instanceof AdapterInterface) {
                $this->adapters[] = $adapter;
            } else {
                $this->adapters[] = new ProxyAdapter($adapter);
            }
        }
        $this->adapterCount = \count($this->adapters);
        $this->defaultLifetime = $defaultLifetime;
        self::$syncItem = self::$syncItem ?? \Closure::bind(static function ($sourceItem, $item, $defaultLifetime, $sourceMetadata = null) {
            $sourceItem->isTaggable = \false;
            $sourceMetadata = $sourceMetadata ?? $sourceItem->metadata;
            $item->value = $sourceItem->value;
            $item->isHit = $sourceItem->isHit;
            $item->metadata = $item->newMetadata = $sourceItem->metadata = $sourceMetadata;
            if (isset($item->metadata[CacheItem::METADATA_EXPIRY])) {
                $item->expiresAt(\DateTimeImmutable::createFromFormat('U.u', \sprintf('%.6F', $item->metadata[CacheItem::METADATA_EXPIRY])));
            } elseif (0 < $defaultLifetime) {
                $item->expiresAfter($defaultLifetime);
            }
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
        $doSave = \true;
        $callback = static function (CacheItem $item, bool &$save) use($callback, &$doSave) {
            $value = $callback($item, $save);
            $doSave = $save;
            return $value;
        };
        $lastItem = null;
        $i = 0;
        $wrap = function (CacheItem $item = null, bool &$save = \true) use($key, $callback, $beta, &$wrap, &$i, &$doSave, &$lastItem, &$metadata) {
            $adapter = $this->adapters[$i];
            if (isset($this->adapters[++$i])) {
                $callback = $wrap;
                $beta = \INF === $beta ? \INF : 0;
            }
            if ($adapter instanceof CacheInterface) {
                $value = $adapter->get($key, $callback, $beta, $metadata);
            } else {
                $value = $this->doGet($adapter, $key, $callback, $beta, $metadata);
            }
            if (null !== $item) {
                (self::$syncItem)($lastItem = $lastItem ?? $item, $item, $this->defaultLifetime, $metadata);
            }
            $save = $doSave;
            return $value;
        };
        return $wrap();
    }
    /**
     * @param mixed $key
     */
    public function getItem($key) : \PrefixedByPoP\Psr\Cache\CacheItemInterface
    {
        $syncItem = self::$syncItem;
        $misses = [];
        foreach ($this->adapters as $i => $adapter) {
            $item = $adapter->getItem($key);
            if ($item->isHit()) {
                while (0 <= --$i) {
                    $this->adapters[$i]->save($syncItem($item, $misses[$i], $this->defaultLifetime));
                }
                return $item;
            }
            $misses[$i] = $item;
        }
        return $item;
    }
    /**
     * @param mixed[] $keys
     */
    public function getItems($keys = []) : iterable
    {
        return $this->generateItems($this->adapters[0]->getItems($keys), 0);
    }
    private function generateItems(iterable $items, int $adapterIndex) : \Generator
    {
        $missing = [];
        $misses = [];
        $nextAdapterIndex = $adapterIndex + 1;
        $nextAdapter = $this->adapters[$nextAdapterIndex] ?? null;
        foreach ($items as $k => $item) {
            if (!$nextAdapter || $item->isHit()) {
                (yield $k => $item);
            } else {
                $missing[] = $k;
                $misses[$k] = $item;
            }
        }
        if ($missing) {
            $syncItem = self::$syncItem;
            $adapter = $this->adapters[$adapterIndex];
            $items = $this->generateItems($nextAdapter->getItems($missing), $nextAdapterIndex);
            foreach ($items as $k => $item) {
                if ($item->isHit()) {
                    $adapter->save($syncItem($item, $misses[$k], $this->defaultLifetime));
                }
                (yield $k => $item);
            }
        }
    }
    /**
     * @param mixed $key
     */
    public function hasItem($key) : bool
    {
        foreach ($this->adapters as $adapter) {
            if ($adapter->hasItem($key)) {
                return \true;
            }
        }
        return \false;
    }
    /**
     * @param string $prefix
     */
    public function clear($prefix = '') : bool
    {
        $cleared = \true;
        $i = $this->adapterCount;
        while ($i--) {
            if ($this->adapters[$i] instanceof AdapterInterface) {
                $cleared = $this->adapters[$i]->clear($prefix) && $cleared;
            } else {
                $cleared = $this->adapters[$i]->clear() && $cleared;
            }
        }
        return $cleared;
    }
    /**
     * @param mixed $key
     */
    public function deleteItem($key) : bool
    {
        $deleted = \true;
        $i = $this->adapterCount;
        while ($i--) {
            $deleted = $this->adapters[$i]->deleteItem($key) && $deleted;
        }
        return $deleted;
    }
    /**
     * @param mixed[] $keys
     */
    public function deleteItems($keys) : bool
    {
        $deleted = \true;
        $i = $this->adapterCount;
        while ($i--) {
            $deleted = $this->adapters[$i]->deleteItems($keys) && $deleted;
        }
        return $deleted;
    }
    /**
     * @param \Psr\Cache\CacheItemInterface $item
     */
    public function save($item) : bool
    {
        $saved = \true;
        $i = $this->adapterCount;
        while ($i--) {
            $saved = $this->adapters[$i]->save($item) && $saved;
        }
        return $saved;
    }
    /**
     * @param \Psr\Cache\CacheItemInterface $item
     */
    public function saveDeferred($item) : bool
    {
        $saved = \true;
        $i = $this->adapterCount;
        while ($i--) {
            $saved = $this->adapters[$i]->saveDeferred($item) && $saved;
        }
        return $saved;
    }
    public function commit() : bool
    {
        $committed = \true;
        $i = $this->adapterCount;
        while ($i--) {
            $committed = $this->adapters[$i]->commit() && $committed;
        }
        return $committed;
    }
    public function prune() : bool
    {
        $pruned = \true;
        foreach ($this->adapters as $adapter) {
            if ($adapter instanceof PruneableInterface) {
                $pruned = $adapter->prune() && $pruned;
            }
        }
        return $pruned;
    }
    public function reset()
    {
        foreach ($this->adapters as $adapter) {
            if ($adapter instanceof ResetInterface) {
                $adapter->reset();
            }
        }
    }
}
