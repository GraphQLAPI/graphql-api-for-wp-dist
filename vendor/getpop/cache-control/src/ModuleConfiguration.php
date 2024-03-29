<?php

declare (strict_types=1);
namespace PoP\CacheControl;

use PoP\Root\Module\AbstractModuleConfiguration;
use PoP\Root\Module\EnvironmentValueHelpers;
class ModuleConfiguration extends AbstractModuleConfiguration
{
    public function getDefaultCacheControlMaxAge() : int
    {
        $envVariable = \PoP\CacheControl\Environment::DEFAULT_CACHE_CONTROL_MAX_AGE;
        $defaultValue = 3600;
        // 1 hour
        $callback = \Closure::fromCallable([EnvironmentValueHelpers::class, 'toInt']);
        return $this->retrieveConfigurationValueOrUseDefault($envVariable, $defaultValue, $callback);
    }
}
