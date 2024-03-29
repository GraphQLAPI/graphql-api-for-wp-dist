<?php

declare (strict_types=1);
namespace PoPAPI\GraphQLAPI;

use PoP\Root\Module\AbstractModuleConfiguration;
use PoP\Root\Module\EnvironmentValueHelpers;
class ModuleConfiguration extends AbstractModuleConfiguration
{
    public function printDynamicFieldInExtensionsOutput() : bool
    {
        $envVariable = \PoPAPI\GraphQLAPI\Environment::PRINT_DYNAMIC_FIELD_IN_EXTENSIONS_OUTPUT;
        $defaultValue = \false;
        $callback = \Closure::fromCallable([EnvironmentValueHelpers::class, 'toBool']);
        return $this->retrieveConfigurationValueOrUseDefault($envVariable, $defaultValue, $callback);
    }
}
