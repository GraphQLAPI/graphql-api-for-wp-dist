<?php

declare (strict_types=1);
namespace PoP\Root\Module;

abstract class AbstractModuleInfo implements \PoP\Root\Module\ModuleInfoInterface
{
    /**
     * @var array<string,mixed>
     */
    protected $values = [];
    /**
     * @var \PoP\Root\Module\ModuleInterface
     */
    protected $module;
    public final function __construct(\PoP\Root\Module\ModuleInterface $module)
    {
        $this->module = $module;
        $this->initialize();
    }
    protected abstract function initialize() : void;
    /**
     * @return mixed
     * @param string $key
     */
    public function get($key)
    {
        return $this->values[$key] ?? null;
    }
}
