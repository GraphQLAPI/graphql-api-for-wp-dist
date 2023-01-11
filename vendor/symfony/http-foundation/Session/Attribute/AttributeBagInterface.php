<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace PrefixedByPoP\Symfony\Component\HttpFoundation\Session\Attribute;

use PrefixedByPoP\Symfony\Component\HttpFoundation\Session\SessionBagInterface;
/**
 * Attributes store.
 *
 * @author Drak <drak@zikula.org>
 */
interface AttributeBagInterface extends SessionBagInterface
{
    /**
     * Checks if an attribute is defined.
     * @param string $name
     */
    public function has($name) : bool;
    /**
     * Returns an attribute.
     * @param mixed $default
     * @return mixed
     * @param string $name
     */
    public function get($name, $default = null);
    /**
     * Sets an attribute.
     * @param mixed $value
     * @param string $name
     */
    public function set($name, $value);
    /**
     * Returns attributes.
     *
     * @return array<string, mixed>
     */
    public function all() : array;
    /**
     * @param mixed[] $attributes
     */
    public function replace($attributes);
    /**
     * Removes an attribute.
     *
     * @return mixed The removed value or null when it does not exist
     * @param string $name
     */
    public function remove($name);
}
