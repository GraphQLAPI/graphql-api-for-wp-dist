<?php

declare(strict_types=1);

namespace PoP\ComponentModel\Instances;

interface InstanceManagerInterface
{
    /**
     * @return object
     */
    public function getInstance(string $class);
    public function getImplementationClass(string $class): string;
}
