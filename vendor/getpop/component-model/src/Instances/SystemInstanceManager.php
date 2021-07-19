<?php

declare (strict_types=1);
namespace PoP\ComponentModel\Instances;

use PoP\Root\Container\SystemContainerBuilderFactory;
class SystemInstanceManager implements \PoP\ComponentModel\Instances\InstanceManagerInterface
{
    use InstanceManagerTrait;
    /**
     * @return object
     */
    public function getInstance(string $class)
    {
        $containerBuilder = SystemContainerBuilderFactory::getInstance();
        return $containerBuilder->get($class);
    }
}
