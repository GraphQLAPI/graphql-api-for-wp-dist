<?php

declare (strict_types=1);
namespace PoP\ComponentModel\Instances;

use PoP\Root\Container\ContainerBuilderFactory;
trait InstanceManagerTrait
{
    /**
     * @var array<string, object>
     */
    private $instances = [];
    /**
     * @var array<string, string>
     */
    private $overridingClasses = [];
    public function overrideClass(string $overrideClass, string $withClass) : void
    {
        $this->overridingClasses[$overrideClass] = $withClass;
    }
    protected function hasClassBeenLoaded(string $class) : bool
    {
        return !\is_null($this->instances[$class] ?? null);
    }
    public function getImplementationClass(string $class) : string
    {
        // Allow a class to take the place of another one
        return $this->overridingClasses[$class] ?? $class;
    }
    /**
     * @return object
     */
    public function getClassInstance(string $class)
    {
        if (!$this->hasClassBeenLoaded($class)) {
            // Allow a class to take the place of another one
            $class = $this->getImplementationClass($class);
            // First ask the ContainerBuilder to handle the class as a Service
            $containerBuilder = \PoP\Root\Container\ContainerBuilderFactory::getInstance();
            if ($containerBuilder->has($class)) {
                $instance = $containerBuilder->get($class);
            } else {
                // Otherwise, assume the class needs no parameters
                $instance = new $class();
            }
            $this->instances[$class] = $instance;
        }
        return $this->instances[$class];
    }
    /**
     * @return object
     */
    public function getInstance(string $class)
    {
        return $this->getClassInstance($class);
    }
}
