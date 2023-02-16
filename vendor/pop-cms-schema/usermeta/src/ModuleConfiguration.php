<?php

declare (strict_types=1);
namespace PoPCMSSchema\UserMeta;

use PoP\Root\Module\AbstractModuleConfiguration;
use PoP\Root\Module\EnvironmentValueHelpers;
use PoPSchema\SchemaCommons\Constants\Behaviors;
class ModuleConfiguration extends AbstractModuleConfiguration
{
    /**
     * @return string[]
     */
    public function getUserMetaEntries() : array
    {
        $envVariable = \PoPCMSSchema\UserMeta\Environment::USER_META_ENTRIES;
        $defaultValue = [];
        $callback = \Closure::fromCallable([EnvironmentValueHelpers::class, 'commaSeparatedStringToArray']);
        return $this->retrieveConfigurationValueOrUseDefault($envVariable, $defaultValue, $callback);
    }
    public function getUserMetaBehavior() : string
    {
        $envVariable = \PoPCMSSchema\UserMeta\Environment::USER_META_BEHAVIOR;
        $defaultValue = Behaviors::ALLOW;
        return $this->retrieveConfigurationValueOrUseDefault($envVariable, $defaultValue);
    }
}
