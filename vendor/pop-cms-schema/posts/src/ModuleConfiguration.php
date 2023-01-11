<?php

declare (strict_types=1);
namespace PoPCMSSchema\Posts;

use PoP\Root\Module\AbstractModuleConfiguration;
use PoP\Root\Module\EnvironmentValueHelpers;
class ModuleConfiguration extends AbstractModuleConfiguration
{
    public function getPostListDefaultLimit() : ?int
    {
        $envVariable = \PoPCMSSchema\Posts\Environment::POST_LIST_DEFAULT_LIMIT;
        $defaultValue = 10;
        $callback = \Closure::fromCallable([EnvironmentValueHelpers::class, 'toInt']);
        return $this->retrieveConfigurationValueOrUseDefault($envVariable, $defaultValue, $callback);
    }
    public function getPostListMaxLimit() : ?int
    {
        $envVariable = \PoPCMSSchema\Posts\Environment::POST_LIST_MAX_LIMIT;
        $defaultValue = -1;
        // Unlimited
        $callback = \Closure::fromCallable([EnvironmentValueHelpers::class, 'toInt']);
        return $this->retrieveConfigurationValueOrUseDefault($envVariable, $defaultValue, $callback);
    }
    public function getPostsRoute() : string
    {
        $envVariable = \PoPCMSSchema\Posts\Environment::POSTS_ROUTE;
        $defaultValue = 'posts';
        return $this->retrieveConfigurationValueOrUseDefault($envVariable, $defaultValue);
    }
}
