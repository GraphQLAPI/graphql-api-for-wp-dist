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

/**
 * FlashBag flash message container.
 *
 * @author Drak <drak@zikula.org>
 */
class FlashBag implements FlashBagInterface
{
    /**
     * @var string
     */
    private $name = 'flashes';
    /**
     * @var mixed[]
     */
    private $flashes = [];
    /**
     * @var string
     */
    private $storageKey;
    /**
     * @param string $storageKey The key used to store flashes in the session
     */
    public function __construct(string $storageKey = '_symfony_flashes')
    {
        $this->storageKey = $storageKey;
    }
    public function getName() : string
    {
        return $this->name;
    }
    /**
     * @param string $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }
    /**
     * @param mixed[] $flashes
     */
    public function initialize(&$flashes)
    {
        $this->flashes =& $flashes;
    }
    /**
     * @param mixed $message
     * @param string $type
     */
    public function add($type, $message)
    {
        $this->flashes[$type][] = $message;
    }
    /**
     * @param string $type
     * @param mixed[] $default
     */
    public function peek($type, $default = []) : array
    {
        return $this->has($type) ? $this->flashes[$type] : $default;
    }
    public function peekAll() : array
    {
        return $this->flashes;
    }
    /**
     * @param string $type
     * @param mixed[] $default
     */
    public function get($type, $default = []) : array
    {
        if (!$this->has($type)) {
            return $default;
        }
        $return = $this->flashes[$type];
        unset($this->flashes[$type]);
        return $return;
    }
    public function all() : array
    {
        $return = $this->peekAll();
        $this->flashes = [];
        return $return;
    }
    /**
     * @param string|mixed[] $messages
     * @param string $type
     */
    public function set($type, $messages)
    {
        $this->flashes[$type] = (array) $messages;
    }
    /**
     * @param mixed[] $messages
     */
    public function setAll($messages)
    {
        $this->flashes = $messages;
    }
    /**
     * @param string $type
     */
    public function has($type) : bool
    {
        return \array_key_exists($type, $this->flashes) && $this->flashes[$type];
    }
    public function keys() : array
    {
        return \array_keys($this->flashes);
    }
    public function getStorageKey() : string
    {
        return $this->storageKey;
    }
    /**
     * @return mixed
     */
    public function clear()
    {
        return $this->all();
    }
}
