<?php

declare (strict_types=1);
namespace GraphQLByPoP\GraphQLServer\Hooks;

use PoP\API\Cache\CacheUtils;
use PoP\Hooks\AbstractHookSet;
use PoP\ComponentModel\State\ApplicationState;
class SchemaCacheHooks extends \PoP\Hooks\AbstractHookSet
{
    protected function init()
    {
        $this->hooksAPI->addFilter(\PoP\API\Cache\CacheUtils::HOOK_SCHEMA_CACHE_KEY_COMPONENTS, array($this, 'getSchemaCacheKeyComponents'));
    }
    public function getSchemaCacheKeyComponents(array $components) : array
    {
        $vars = \PoP\ComponentModel\State\ApplicationState::getVars();
        if ($graphQLOperationType = $vars['graphql-operation-type']) {
            $components['graphql-operation-type'] = $graphQLOperationType;
        }
        $components['nested-mutations-enabled'] = $vars['nested-mutations-enabled'];
        return $components;
    }
}
