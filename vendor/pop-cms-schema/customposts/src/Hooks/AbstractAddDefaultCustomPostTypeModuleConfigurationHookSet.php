<?php

declare (strict_types=1);
namespace PoPCMSSchema\CustomPosts\Hooks;

use PoPCMSSchema\CustomPosts\Environment;
use PoPCMSSchema\CustomPosts\Module;
use PoPCMSSchema\CustomPosts\ModuleConfiguration;
use PoP\Root\App;
use PoP\Root\Hooks\AbstractHookSet;
use PoP\Root\Module\ModuleConfigurationHelpers;
/**
 * Add a default CPT for Custom Posts
 */
abstract class AbstractAddDefaultCustomPostTypeModuleConfigurationHookSet extends AbstractHookSet
{
    protected function init() : void
    {
        /**
         * Allow the GraphQL API plugin to set its own values,
         * completely overriding the hooks set by the independent
         * packages Posts and Pages.
         */
        /** @var ModuleConfiguration */
        $moduleConfiguration = App::getModule(Module::class)->getConfiguration();
        if ($moduleConfiguration->disablePackagesAddingDefaultQueryableCustomTypes()) {
            return;
        }
        $hookName = ModuleConfigurationHelpers::getHookName(Module::class, Environment::QUERYABLE_CUSTOMPOST_TYPES);
        App::addFilter($hookName, function (array $queryableCustomPostTypes) {
            return \array_values(\array_unique(\array_merge($queryableCustomPostTypes, [$this->getCustomPostType()])));
        });
    }
    protected abstract function getCustomPostType() : string;
}
