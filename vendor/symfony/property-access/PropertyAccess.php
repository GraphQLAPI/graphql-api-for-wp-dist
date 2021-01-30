<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace PrefixedByPoP\Symfony\Component\PropertyAccess;

/**
 * Entry point of the PropertyAccess component.
 *
 * @author Bernhard Schussek <bschussek@gmail.com>
 */
final class PropertyAccess
{
    /**
     * Creates a property accessor with the default configuration.
     */
    public static function createPropertyAccessor() : \PrefixedByPoP\Symfony\Component\PropertyAccess\PropertyAccessor
    {
        return self::createPropertyAccessorBuilder()->getPropertyAccessor();
    }
    public static function createPropertyAccessorBuilder() : \PrefixedByPoP\Symfony\Component\PropertyAccess\PropertyAccessorBuilder
    {
        return new \PrefixedByPoP\Symfony\Component\PropertyAccess\PropertyAccessorBuilder();
    }
    /**
     * This class cannot be instantiated.
     */
    private function __construct()
    {
    }
}
