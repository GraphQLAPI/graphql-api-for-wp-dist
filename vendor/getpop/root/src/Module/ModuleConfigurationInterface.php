<?php

declare (strict_types=1);
namespace PoP\Root\Module;

interface ModuleConfigurationInterface
{
    /**
     * @param string $envVariable
     */
    public function hasConfigurationValue($envVariable) : bool;
    /**
     * @return mixed
     * @param string $envVariable
     */
    public function getConfigurationValue($envVariable);
}
