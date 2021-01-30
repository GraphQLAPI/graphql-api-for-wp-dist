<?php

declare (strict_types=1);
namespace PoP\Engine\Cache;

use PoP\Hooks\HooksAPIInterface;
use PrefixedByPoP\Psr\Cache\CacheItemPoolInterface;
use PrefixedByPoP\Psr\Cache\CacheItemInterface;
use PoP\ComponentModel\ModelInstance\ModelInstanceInterface;
class Cache extends \PoP\ComponentModel\Cache\Cache
{
    /**
     * @var \PoP\Hooks\HooksAPIInterface
     */
    protected $hooksAPI;
    public function __construct(\PrefixedByPoP\Psr\Cache\CacheItemPoolInterface $cacheItemPool, \PoP\Hooks\HooksAPIInterface $hooksAPI, \PoP\ComponentModel\ModelInstance\ModelInstanceInterface $modelInstance)
    {
        parent::__construct($cacheItemPool, $modelInstance);
        $this->hooksAPI = $hooksAPI;
        // When a plugin is activated/deactivated, ANY plugin, delete the corresponding cached files
        // This is particularly important for the MEMORY, since we can't set by constants to not use it
        $this->hooksAPI->addAction('popcms:componentInstalledOrUninstalled', function () {
            $this->cacheItemPool->clear();
        });
        // Save all deferred cacheItems
        $this->hooksAPI->addAction('popcms:shutdown', function () {
            $this->cacheItemPool->commit();
        });
    }
    /**
     * Override to save as deferred, on hook "popcms:shutdown"
     *
     * @param CacheItemInterface $cacheItem
     * @return void
     */
    protected function saveCache(\PrefixedByPoP\Psr\Cache\CacheItemInterface $cacheItem)
    {
        $this->cacheItemPool->saveDeferred($cacheItem);
    }
}
