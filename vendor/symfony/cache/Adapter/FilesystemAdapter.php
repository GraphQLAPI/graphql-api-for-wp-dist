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

use PrefixedByPoP\Symfony\Component\Cache\Marshaller\DefaultMarshaller;
use PrefixedByPoP\Symfony\Component\Cache\Marshaller\MarshallerInterface;
use PrefixedByPoP\Symfony\Component\Cache\PruneableInterface;
use PrefixedByPoP\Symfony\Component\Cache\Traits\FilesystemTrait;
class FilesystemAdapter extends \PrefixedByPoP\Symfony\Component\Cache\Adapter\AbstractAdapter implements \PrefixedByPoP\Symfony\Component\Cache\PruneableInterface
{
    use FilesystemTrait;
    public function __construct(string $namespace = '', int $defaultLifetime = 0, string $directory = null, \PrefixedByPoP\Symfony\Component\Cache\Marshaller\MarshallerInterface $marshaller = null)
    {
        $this->marshaller = $marshaller ?? new \PrefixedByPoP\Symfony\Component\Cache\Marshaller\DefaultMarshaller();
        parent::__construct('', $defaultLifetime);
        $this->init($namespace, $directory);
    }
}
