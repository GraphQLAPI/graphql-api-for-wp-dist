<?php

declare (strict_types=1);
namespace PoP\ComponentModel\Instances;

interface InstanceManagerInterface
{
    /**
     * @return object
     */
    public function getInstance(string $class);
    public function getInstanceClass(string $class) : string;
}
