<?php

declare (strict_types=1);
namespace PoPCMSSchema\CustomPostMeta;

use PoP\Root\Module\AbstractModuleConfiguration;
use PoP\Root\Module\EnvironmentValueHelpers;
use PoPSchema\SchemaCommons\Constants\Behaviors;
class ModuleConfiguration extends AbstractModuleConfiguration
{
    /**
     * @return string[]
     */
    public function getCustomPostMetaEntries() : array
    {
        $envVariable = \PoPCMSSchema\CustomPostMeta\Environment::CUSTOMPOST_META_ENTRIES;
        $defaultValue = [];
        $callback = \Closure::fromCallable([EnvironmentValueHelpers::class, 'commaSeparatedStringToArray']);
        return $this->retrieveConfigurationValueOrUseDefault($envVariable, $defaultValue, $callback);
    }
    public function getCustomPostMetaBehavior() : string
    {
        $envVariable = \PoPCMSSchema\CustomPostMeta\Environment::CUSTOMPOST_META_BEHAVIOR;
        $defaultValue = Behaviors::ALLOW;
        return $this->retrieveConfigurationValueOrUseDefault($envVariable, $defaultValue);
    }
}
