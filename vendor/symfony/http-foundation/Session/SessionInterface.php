<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace PrefixedByPoP\Symfony\Component\HttpFoundation\Session;

use PrefixedByPoP\Symfony\Component\HttpFoundation\Session\Storage\MetadataBag;
/**
 * Interface for the session.
 *
 * @author Drak <drak@zikula.org>
 */
interface SessionInterface
{
    /**
     * Starts the session storage.
     *
     * @throws \RuntimeException if session fails to start
     */
    public function start() : bool;
    /**
     * Returns the session ID.
     */
    public function getId() : string;
    /**
     * Sets the session ID.
     * @param string $id
     */
    public function setId($id);
    /**
     * Returns the session name.
     */
    public function getName() : string;
    /**
     * Sets the session name.
     * @param string $name
     */
    public function setName($name);
    /**
     * Invalidates the current session.
     *
     * Clears all session attributes and flashes and regenerates the
     * session and deletes the old session from persistence.
     *
     * @param int $lifetime Sets the cookie lifetime for the session cookie. A null value
     *                      will leave the system settings unchanged, 0 sets the cookie
     *                      to expire with browser session. Time is in seconds, and is
     *                      not a Unix timestamp.
     */
    public function invalidate($lifetime = null) : bool;
    /**
     * Migrates the current session to a new session id while maintaining all
     * session attributes.
     *
     * @param bool $destroy  Whether to delete the old session or leave it to garbage collection
     * @param int  $lifetime Sets the cookie lifetime for the session cookie. A null value
     *                       will leave the system settings unchanged, 0 sets the cookie
     *                       to expire with browser session. Time is in seconds, and is
     *                       not a Unix timestamp.
     */
    public function migrate($destroy = \false, $lifetime = null) : bool;
    /**
     * Force the session to be saved and closed.
     *
     * This method is generally not required for real sessions as
     * the session will be automatically saved at the end of
     * code execution.
     */
    public function save();
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
     */
    public function all() : array;
    /**
     * Sets attributes.
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
    /**
     * Clears all attributes.
     */
    public function clear();
    /**
     * Checks if the session was started.
     */
    public function isStarted() : bool;
    /**
     * Registers a SessionBagInterface with the session.
     * @param \Symfony\Component\HttpFoundation\Session\SessionBagInterface $bag
     */
    public function registerBag($bag);
    /**
     * Gets a bag instance by name.
     * @param string $name
     */
    public function getBag($name) : SessionBagInterface;
    /**
     * Gets session meta.
     */
    public function getMetadataBag() : MetadataBag;
}
