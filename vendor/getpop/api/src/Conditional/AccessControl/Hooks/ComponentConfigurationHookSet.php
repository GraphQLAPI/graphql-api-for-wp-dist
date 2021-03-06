<?php

declare (strict_types=1);
namespace PoP\API\Conditional\AccessControl\Hooks;

use PoP\API\Environment;
use PoP\API\ComponentConfiguration;
use PoP\AccessControl\ComponentConfiguration as AccessControlComponentConfiguration;
use PoP\Hooks\AbstractHookSet;
use PoP\ComponentModel\ComponentConfiguration\ComponentConfigurationHelpers;
class ComponentConfigurationHookSet extends \PoP\Hooks\AbstractHookSet
{
    protected function init()
    {
        /**
         * Do not enable caching when doing a private schema mode
         */
        if (\PoP\AccessControl\ComponentConfiguration::canSchemaBePrivate()) {
            $hookName = \PoP\ComponentModel\ComponentConfiguration\ComponentConfigurationHelpers::getHookName(\PoP\API\ComponentConfiguration::class, \PoP\API\Environment::USE_SCHEMA_DEFINITION_CACHE);
            $this->hooksAPI->addFilter($hookName, function () {
                return \false;
            });
        }
    }
}
