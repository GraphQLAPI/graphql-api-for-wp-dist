<?php

declare (strict_types=1);
namespace PoP\ComponentModel\Dictionaries;

interface ObjectDictionaryInterface
{
    /**
     * @param string|int $id
     * @return mixed
     * @param string $class
     */
    public function get($class, $id);
    /**
     * @param string|int $id
     * @param string $class
     */
    public function has($class, $id) : bool;
    /**
     * @param string|int $id
     * @param mixed $instance
     * @param string $class
     */
    public function set($class, $id, $instance) : void;
}
