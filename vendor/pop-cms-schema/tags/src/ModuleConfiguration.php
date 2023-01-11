<?php

declare (strict_types=1);
namespace PoPCMSSchema\Tags;

use PoP\Root\Module\AbstractModuleConfiguration;
use PoP\Root\Module\EnvironmentValueHelpers;
class ModuleConfiguration extends AbstractModuleConfiguration
{
    public function getTagListDefaultLimit() : ?int
    {
        $envVariable = \PoPCMSSchema\Tags\Environment::TAG_LIST_DEFAULT_LIMIT;
        $defaultValue = 10;
        $callback = \Closure::fromCallable([EnvironmentValueHelpers::class, 'toInt']);
        return $this->retrieveConfigurationValueOrUseDefault($envVariable, $defaultValue, $callback);
    }
    public function getTagListMaxLimit() : ?int
    {
        $envVariable = \PoPCMSSchema\Tags\Environment::TAG_LIST_MAX_LIMIT;
        $defaultValue = -1;
        // Unlimited
        $callback = \Closure::fromCallable([EnvironmentValueHelpers::class, 'toInt']);
        return $this->retrieveConfigurationValueOrUseDefault($envVariable, $defaultValue, $callback);
    }
    /**
     * @return string[]
     */
    public function getQueryableTagTaxonomies() : array
    {
        $envVariable = \PoPCMSSchema\Tags\Environment::QUERYABLE_TAG_TAXONOMIES;
        $defaultValue = [];
        $callback = \Closure::fromCallable([EnvironmentValueHelpers::class, 'commaSeparatedStringToArray']);
        return $this->retrieveConfigurationValueOrUseDefault($envVariable, $defaultValue, $callback);
    }
}
