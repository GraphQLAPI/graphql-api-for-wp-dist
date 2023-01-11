<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace PrefixedByPoP\Symfony\Component\DependencyInjection\LazyProxy\PhpDumper;

use PrefixedByPoP\Symfony\Component\DependencyInjection\Definition;
/**
 * Null dumper, negates any proxy code generation for any given service definition.
 *
 * @author Marco Pivetta <ocramius@gmail.com>
 *
 * @final
 */
class NullDumper implements DumperInterface
{
    /**
     * @param \Symfony\Component\DependencyInjection\Definition $definition
     * @param bool|null $asGhostObject
     * @param string|null $id
     */
    public function isProxyCandidate($definition, &$asGhostObject = null, $id = null) : bool
    {
        return $asGhostObject = \false;
    }
    /**
     * @param \Symfony\Component\DependencyInjection\Definition $definition
     * @param string $id
     * @param string $factoryCode
     */
    public function getProxyFactoryCode($definition, $id, $factoryCode) : string
    {
        return '';
    }
    /**
     * @param \Symfony\Component\DependencyInjection\Definition $definition
     * @param string|null $id
     */
    public function getProxyCode($definition, $id = null) : string
    {
        return '';
    }
}
