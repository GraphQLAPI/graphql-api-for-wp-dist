<?php

declare (strict_types=1);
namespace PoPCMSSchema\Users;

use PoP\Root\Module\AbstractModuleConfiguration;
use PoP\Root\Module\EnvironmentValueHelpers;
class ModuleConfiguration extends AbstractModuleConfiguration
{
    public function getUserListDefaultLimit() : ?int
    {
        $envVariable = \PoPCMSSchema\Users\Environment::USER_LIST_DEFAULT_LIMIT;
        $defaultValue = 10;
        $callback = \Closure::fromCallable([EnvironmentValueHelpers::class, 'toInt']);
        return $this->retrieveConfigurationValueOrUseDefault($envVariable, $defaultValue, $callback);
    }
    public function getUserListMaxLimit() : ?int
    {
        $envVariable = \PoPCMSSchema\Users\Environment::USER_LIST_MAX_LIMIT;
        $defaultValue = -1;
        // Unlimited
        $callback = \Closure::fromCallable([EnvironmentValueHelpers::class, 'toInt']);
        return $this->retrieveConfigurationValueOrUseDefault($envVariable, $defaultValue, $callback);
    }
    public function getUsersRoute() : string
    {
        $envVariable = \PoPCMSSchema\Users\Environment::USERS_ROUTE;
        $defaultValue = 'users';
        return $this->retrieveConfigurationValueOrUseDefault($envVariable, $defaultValue);
    }
    public function treatUserEmailAsSensitiveData() : bool
    {
        $envVariable = \PoPCMSSchema\Users\Environment::TREAT_USER_EMAIL_AS_SENSITIVE_DATA;
        $defaultValue = \true;
        $callback = \Closure::fromCallable([EnvironmentValueHelpers::class, 'toBool']);
        return $this->retrieveConfigurationValueOrUseDefault($envVariable, $defaultValue, $callback);
    }
}
