<?php

declare (strict_types=1);
namespace PoP\Engine;

use PoP\Root\Module\AbstractModuleConfiguration;
use PoP\Root\Module\EnvironmentValueHelpers;
class ModuleConfiguration extends AbstractModuleConfiguration
{
    public function disableRedundantRootTypeMutationFields() : bool
    {
        $envVariable = \PoP\Engine\Environment::DISABLE_REDUNDANT_ROOT_TYPE_MUTATION_FIELDS;
        $defaultValue = \false;
        $callback = \Closure::fromCallable([EnvironmentValueHelpers::class, 'toBool']);
        return $this->retrieveConfigurationValueOrUseDefault($envVariable, $defaultValue, $callback);
    }
    public function enableQueryingAppStateFields() : bool
    {
        $envVariable = \PoP\Engine\Environment::ENABLE_QUERYING_APP_STATE_FIELDS;
        $defaultValue = \false;
        $callback = \Closure::fromCallable([EnvironmentValueHelpers::class, 'toBool']);
        return $this->retrieveConfigurationValueOrUseDefault($envVariable, $defaultValue, $callback);
    }
}
