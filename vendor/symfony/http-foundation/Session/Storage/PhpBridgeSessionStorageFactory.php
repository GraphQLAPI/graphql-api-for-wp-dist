<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace PrefixedByPoP\Symfony\Component\HttpFoundation\Session\Storage;

use PrefixedByPoP\Symfony\Component\HttpFoundation\Request;
use PrefixedByPoP\Symfony\Component\HttpFoundation\Session\Storage\Proxy\AbstractProxy;
// Help opcache.preload discover always-needed symbols
\class_exists(PhpBridgeSessionStorage::class);
/**
 * @author Jérémy Derussé <jeremy@derusse.com>
 */
class PhpBridgeSessionStorageFactory implements SessionStorageFactoryInterface
{
    /**
     * @var \Symfony\Component\HttpFoundation\Session\Storage\Proxy\AbstractProxy|\SessionHandlerInterface|null
     */
    private $handler;
    /**
     * @var \Symfony\Component\HttpFoundation\Session\Storage\MetadataBag|null
     */
    private $metaBag;
    /**
     * @var bool
     */
    private $secure;
    /**
     * @param \Symfony\Component\HttpFoundation\Session\Storage\Proxy\AbstractProxy|\SessionHandlerInterface $handler
     */
    public function __construct($handler = null, MetadataBag $metaBag = null, bool $secure = \false)
    {
        $this->handler = $handler;
        $this->metaBag = $metaBag;
        $this->secure = $secure;
    }
    /**
     * @param \Symfony\Component\HttpFoundation\Request|null $request
     */
    public function createStorage($request) : SessionStorageInterface
    {
        $storage = new PhpBridgeSessionStorage($this->handler, $this->metaBag);
        if ($this->secure && (($request2 = $request) ? $request2->isSecure() : null)) {
            $storage->setOptions(['cookie_secure' => \true]);
        }
        return $storage;
    }
}
