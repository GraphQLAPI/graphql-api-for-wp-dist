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

use PrefixedByPoP\Psr\Cache\InvalidArgumentException as Psr6CacheInterface;
use PrefixedByPoP\Psr\SimpleCache\InvalidArgumentException as SimpleCacheInterface;
if (\interface_exists(\PrefixedByPoP\Psr\SimpleCache\InvalidArgumentException::class)) {
    class InvalidArgumentException extends \InvalidArgumentException implements \PrefixedByPoP\Psr\Cache\InvalidArgumentException, \PrefixedByPoP\Psr\SimpleCache\InvalidArgumentException
    {
    }
} else {
    class InvalidArgumentException extends \InvalidArgumentException implements \PrefixedByPoP\Psr\Cache\InvalidArgumentException
    {
    }
}
