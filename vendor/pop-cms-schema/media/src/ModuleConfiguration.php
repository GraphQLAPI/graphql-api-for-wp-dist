<?php

declare (strict_types=1);
namespace PoPCMSSchema\Media;

use PoP\Root\Module\AbstractModuleConfiguration;
use PoP\Root\Module\EnvironmentValueHelpers;
class ModuleConfiguration extends AbstractModuleConfiguration
{
    public function getMediaListDefaultLimit() : ?int
    {
        $envVariable = \PoPCMSSchema\Media\Environment::MEDIA_LIST_DEFAULT_LIMIT;
        $defaultValue = 10;
        $callback = \Closure::fromCallable([EnvironmentValueHelpers::class, 'toInt']);
        return $this->retrieveConfigurationValueOrUseDefault($envVariable, $defaultValue, $callback);
    }
    public function getMediaListMaxLimit() : ?int
    {
        $envVariable = \PoPCMSSchema\Media\Environment::MEDIA_LIST_MAX_LIMIT;
        $defaultValue = -1;
        // Unlimited
        $callback = \Closure::fromCallable([EnvironmentValueHelpers::class, 'toInt']);
        return $this->retrieveConfigurationValueOrUseDefault($envVariable, $defaultValue, $callback);
    }
}
