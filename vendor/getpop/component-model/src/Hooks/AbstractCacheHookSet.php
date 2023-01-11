<?php

declare (strict_types=1);
namespace PoP\ComponentModel\Hooks;

use PoP\ComponentModel\Cache\PersistentCacheInterface;
use PoP\ComponentModel\Cache\TransientCacheInterface;
use PoP\Root\App;
use PoP\Root\Hooks\AbstractHookSet;
abstract class AbstractCacheHookSet extends AbstractHookSet
{
    /**
     * @var \PoP\ComponentModel\Cache\PersistentCacheInterface|null
     */
    private $persistentCache;
    /**
     * @var \PoP\ComponentModel\Cache\TransientCacheInterface|null
     */
    private $transientCache;
    /**
     * @param \PoP\ComponentModel\Cache\PersistentCacheInterface $persistentCache
     */
    public final function setPersistentCache($persistentCache) : void
    {
        $this->persistentCache = $persistentCache;
    }
    protected final function getPersistentCache() : PersistentCacheInterface
    {
        /** @var PersistentCacheInterface */
        return $this->persistentCache = $this->persistentCache ?? $this->instanceManager->getInstance(PersistentCacheInterface::class);
    }
    /**
     * @param \PoP\ComponentModel\Cache\TransientCacheInterface $transientCache
     */
    public final function setTransientCache($transientCache) : void
    {
        $this->transientCache = $transientCache;
    }
    protected final function getTransientCache() : TransientCacheInterface
    {
        /** @var TransientCacheInterface */
        return $this->transientCache = $this->transientCache ?? $this->instanceManager->getInstance(TransientCacheInterface::class);
    }
    protected function init() : void
    {
        /**
         * When a plugin/module/component/etc is activated/deactivated,
         * delete the cached files from this application.
         *
         * For instance, for WordPress, these hooks must be provided:
         *
         * - 'activate_plugin'
         * - 'deactivate_plugin'
         */
        foreach ($this->getClearHookNames() as $hookName) {
            App::addAction($hookName, \Closure::fromCallable([$this, 'clear']));
        }
        /**
         * Save all deferred cacheItems.
         *
         * For instance, for WordPress, this hook must be provided:
         *
         * - 'shutdown'
         */
        App::addAction($this->getCommitHookName(), \Closure::fromCallable([$this, 'commit']));
    }
    /**
     * @return string[]
     */
    protected abstract function getClearHookNames() : array;
    protected abstract function getCommitHookName() : string;
    public function clear() : void
    {
        $this->getPersistentCache()->clear();
        $this->getTransientCache()->clear();
    }
    public function commit() : void
    {
        $this->getPersistentCache()->commit();
        $this->getTransientCache()->commit();
    }
}
