<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace PrefixedByPoP\Symfony\Component\Config\Resource;

use PrefixedByPoP\Symfony\Component\Config\ResourceCheckerInterface;
/**
 * Resource checker for instances of SelfCheckingResourceInterface.
 *
 * As these resources perform the actual check themselves, we can provide
 * this class as a standard way of validating them.
 *
 * @author Matthias Pigulla <mp@webfactory.de>
 */
class SelfCheckingResourceChecker implements ResourceCheckerInterface
{
    // Common shared cache, because this checker can be used in different
    // situations. For example, when using the full stack framework, the router
    // and the container have their own cache. But they may check the very same
    // resources
    /**
     * @var mixed[]
     */
    private static $cache = [];
    /**
     * @param \Symfony\Component\Config\Resource\ResourceInterface $metadata
     */
    public function supports($metadata) : bool
    {
        return $metadata instanceof SelfCheckingResourceInterface;
    }
    /**
     * @param SelfCheckingResourceInterface $resource
     * @param int $timestamp
     */
    public function isFresh($resource, $timestamp) : bool
    {
        $key = "{$resource}:{$timestamp}";
        return self::$cache[$key] = self::$cache[$key] ?? $resource->isFresh($timestamp);
    }
}
