<?php

declare (strict_types=1);
namespace PoP\Root\Instances;

use PoP\Root\App;
class SystemInstanceManager implements \PoP\Root\Instances\InstanceManagerInterface
{
    /**
     * @return object
     * @param string $class
     */
    public function getInstance($class)
    {
        $containerBuilder = App::getSystemContainer();
        /** @var object */
        return $containerBuilder->get($class);
    }
    /**
     * @param string $class
     */
    public function hasInstance($class) : bool
    {
        $containerBuilder = App::getSystemContainer();
        return $containerBuilder->has($class);
    }
}
