<?php

declare (strict_types=1);
namespace PoP\Root\Instances;

interface InstanceManagerInterface
{
    /**
     * @return object
     * @param string $class
     */
    public function getInstance($class);
    /**
     * @param string $class
     */
    public function hasInstance($class) : bool;
}
