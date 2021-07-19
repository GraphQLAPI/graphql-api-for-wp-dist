<?php

declare (strict_types=1);
namespace PoP\ComponentModel\Instances;

use PoP\Root\Container\ContainerBuilderFactory;
class InstanceManager implements \PoP\ComponentModel\Instances\InstanceManagerInterface
{
    use InstanceManagerTrait;
    /**
     * @return object
     */
    public function getInstance(string $class)
    {
        $containerBuilder = ContainerBuilderFactory::getInstance();
        return $containerBuilder->get($class);
    }
}
