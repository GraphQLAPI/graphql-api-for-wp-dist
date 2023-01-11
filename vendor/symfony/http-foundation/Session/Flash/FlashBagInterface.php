<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace PrefixedByPoP\Symfony\Component\HttpFoundation\Session\Flash;

use PrefixedByPoP\Symfony\Component\HttpFoundation\Session\SessionBagInterface;
/**
 * FlashBagInterface.
 *
 * @author Drak <drak@zikula.org>
 */
interface FlashBagInterface extends SessionBagInterface
{
    /**
     * Adds a flash message for the given type.
     * @param mixed $message
     * @param string $type
     */
    public function add($type, $message);
    /**
     * Registers one or more messages for a given type.
     * @param string|mixed[] $messages
     * @param string $type
     */
    public function set($type, $messages);
    /**
     * Gets flash messages for a given type.
     *
     * @param string $type    Message category type
     * @param array  $default Default value if $type does not exist
     */
    public function peek($type, $default = []) : array;
    /**
     * Gets all flash messages.
     */
    public function peekAll() : array;
    /**
     * Gets and clears flash from the stack.
     *
     * @param array $default Default value if $type does not exist
     * @param string $type
     */
    public function get($type, $default = []) : array;
    /**
     * Gets and clears flashes from the stack.
     */
    public function all() : array;
    /**
     * Sets all flash messages.
     * @param mixed[] $messages
     */
    public function setAll($messages);
    /**
     * Has flash messages for a given type?
     * @param string $type
     */
    public function has($type) : bool;
    /**
     * Returns a list of all defined types.
     */
    public function keys() : array;
}
