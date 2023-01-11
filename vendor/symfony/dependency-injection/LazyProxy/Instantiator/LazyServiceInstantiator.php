<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace PrefixedByPoP\Symfony\Component\DependencyInjection\LazyProxy\Instantiator;

use PrefixedByPoP\Symfony\Component\DependencyInjection\ContainerInterface;
use PrefixedByPoP\Symfony\Component\DependencyInjection\Definition;
use PrefixedByPoP\Symfony\Component\DependencyInjection\Exception\InvalidArgumentException;
use PrefixedByPoP\Symfony\Component\DependencyInjection\LazyProxy\PhpDumper\LazyServiceDumper;
/**
 * @author Nicolas Grekas <p@tchwork.com>
 */
final class LazyServiceInstantiator implements InstantiatorInterface
{
    /**
     * @return object
     * @param \Symfony\Component\DependencyInjection\ContainerInterface $container
     * @param \Symfony\Component\DependencyInjection\Definition $definition
     * @param string $id
     * @param callable $realInstantiator
     */
    public function instantiateProxy($container, $definition, $id, $realInstantiator)
    {
        $dumper = new LazyServiceDumper();
        if (!$dumper->isProxyCandidate($definition, $asGhostObject, $id)) {
            throw new InvalidArgumentException(\sprintf('Cannot instantiate lazy proxy for service "%s".', $id));
        }
        if (!\class_exists($proxyClass = $dumper->getProxyClass($definition, $asGhostObject, $class), \false)) {
            eval($dumper->getProxyCode($definition, $id));
        }
        return $asGhostObject ? $proxyClass::createLazyGhost($realInstantiator) : $proxyClass::createLazyProxy($realInstantiator);
    }
}
