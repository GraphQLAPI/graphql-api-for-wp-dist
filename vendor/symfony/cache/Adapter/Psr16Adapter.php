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

use PrefixedByPoP\Psr\SimpleCache\CacheInterface;
use PrefixedByPoP\Symfony\Component\Cache\PruneableInterface;
use PrefixedByPoP\Symfony\Component\Cache\ResettableInterface;
use PrefixedByPoP\Symfony\Component\Cache\Traits\ProxyTrait;
/**
 * Turns a PSR-16 cache into a PSR-6 one.
 *
 * @author Nicolas Grekas <p@tchwork.com>
 */
class Psr16Adapter extends AbstractAdapter implements PruneableInterface, ResettableInterface
{
    use ProxyTrait;
    /**
     * @internal
     */
    protected const NS_SEPARATOR = '_';
    /**
     * @var object
     */
    private $miss;
    public function __construct(CacheInterface $pool, string $namespace = '', int $defaultLifetime = 0)
    {
        parent::__construct($namespace, $defaultLifetime);
        $this->pool = $pool;
        $this->miss = new \stdClass();
    }
    /**
     * @param mixed[] $ids
     */
    protected function doFetch($ids) : iterable
    {
        foreach ($this->pool->getMultiple($ids, $this->miss) as $key => $value) {
            if ($this->miss !== $value) {
                (yield $key => $value);
            }
        }
    }
    /**
     * @param string $id
     */
    protected function doHave($id) : bool
    {
        return $this->pool->has($id);
    }
    /**
     * @param string $namespace
     */
    protected function doClear($namespace) : bool
    {
        return $this->pool->clear();
    }
    /**
     * @param mixed[] $ids
     */
    protected function doDelete($ids) : bool
    {
        return $this->pool->deleteMultiple($ids);
    }
    /**
     * @return mixed[]|bool
     * @param mixed[] $values
     * @param int $lifetime
     */
    protected function doSave($values, $lifetime)
    {
        return $this->pool->setMultiple($values, 0 === $lifetime ? null : $lifetime);
    }
}
