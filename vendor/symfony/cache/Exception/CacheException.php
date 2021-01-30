<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace PrefixedByPoP\Symfony\Component\Cache\Exception;

use PrefixedByPoP\Psr\Cache\CacheException as Psr6CacheInterface;
use PrefixedByPoP\Psr\SimpleCache\CacheException as SimpleCacheInterface;
if (\interface_exists(\PrefixedByPoP\Psr\SimpleCache\CacheException::class)) {
    class CacheException extends \Exception implements \PrefixedByPoP\Psr\Cache\CacheException, \PrefixedByPoP\Psr\SimpleCache\CacheException
    {
    }
} else {
    class CacheException extends \Exception implements \PrefixedByPoP\Psr\Cache\CacheException
    {
    }
}
