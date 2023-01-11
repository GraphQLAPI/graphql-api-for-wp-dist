<?php

declare (strict_types=1);
namespace PoPCMSSchema\UserRoles;

use PoP\Root\Module\AbstractModuleConfiguration;
use PoP\Root\Module\EnvironmentValueHelpers;
class ModuleConfiguration extends AbstractModuleConfiguration
{
    public function treatUserRoleAsSensitiveData() : bool
    {
        $envVariable = \PoPCMSSchema\UserRoles\Environment::TREAT_USER_ROLE_AS_SENSITIVE_DATA;
        $defaultValue = \true;
        $callback = \Closure::fromCallable([EnvironmentValueHelpers::class, 'toBool']);
        return $this->retrieveConfigurationValueOrUseDefault($envVariable, $defaultValue, $callback);
    }
    public function treatUserCapabilityAsSensitiveData() : bool
    {
        $envVariable = \PoPCMSSchema\UserRoles\Environment::TREAT_USER_CAPABILITY_AS_SENSITIVE_DATA;
        $defaultValue = \true;
        $callback = \Closure::fromCallable([EnvironmentValueHelpers::class, 'toBool']);
        return $this->retrieveConfigurationValueOrUseDefault($envVariable, $defaultValue, $callback);
    }
}
