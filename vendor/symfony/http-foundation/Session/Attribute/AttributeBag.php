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

/**
 * This class relates to session attribute storage.
 *
 * @implements \IteratorAggregate<string, mixed>
 */
class AttributeBag implements AttributeBagInterface, \IteratorAggregate, \Countable
{
    /**
     * @var string
     */
    private $name = 'attributes';
    /**
     * @var string
     */
    private $storageKey;
    protected $attributes = [];
    /**
     * @param string $storageKey The key used to store attributes in the session
     */
    public function __construct(string $storageKey = '_sf2_attributes')
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
     * @param mixed[] $attributes
     */
    public function initialize(&$attributes)
    {
        $this->attributes =& $attributes;
    }
    public function getStorageKey() : string
    {
        return $this->storageKey;
    }
    /**
     * @param string $name
     */
    public function has($name) : bool
    {
        return \array_key_exists($name, $this->attributes);
    }
    /**
     * @param mixed $default
     * @return mixed
     * @param string $name
     */
    public function get($name, $default = null)
    {
        return \array_key_exists($name, $this->attributes) ? $this->attributes[$name] : $default;
    }
    /**
     * @param mixed $value
     * @param string $name
     */
    public function set($name, $value)
    {
        $this->attributes[$name] = $value;
    }
    public function all() : array
    {
        return $this->attributes;
    }
    /**
     * @param mixed[] $attributes
     */
    public function replace($attributes)
    {
        $this->attributes = [];
        foreach ($attributes as $key => $value) {
            $this->set($key, $value);
        }
    }
    /**
     * @return mixed
     * @param string $name
     */
    public function remove($name)
    {
        $retval = null;
        if (\array_key_exists($name, $this->attributes)) {
            $retval = $this->attributes[$name];
            unset($this->attributes[$name]);
        }
        return $retval;
    }
    /**
     * @return mixed
     */
    public function clear()
    {
        $return = $this->attributes;
        $this->attributes = [];
        return $return;
    }
    /**
     * Returns an iterator for attributes.
     *
     * @return \ArrayIterator<string, mixed>
     */
    public function getIterator() : \ArrayIterator
    {
        return new \ArrayIterator($this->attributes);
    }
    /**
     * Returns the number of attributes.
     */
    public function count() : int
    {
        return \count($this->attributes);
    }
}
