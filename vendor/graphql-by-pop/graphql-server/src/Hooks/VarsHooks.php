<?php

declare (strict_types=1);
namespace GraphQLByPoP\GraphQLServer\Hooks;

use GraphQLByPoP\GraphQLServer\ComponentConfiguration;
use GraphQLByPoP\GraphQLServer\Configuration\Request;
use PoP\API\Response\Schemes as APISchemes;
use PoP\ComponentModel\Facades\Instances\InstanceManagerFacade;
use PoP\ComponentModel\ModelInstance\ModelInstance;
use PoP\ComponentModel\State\ApplicationState;
use PoP\GraphQLAPI\DataStructureFormatters\GraphQLDataStructureFormatter;
use PoP\Hooks\AbstractHookSet;
use PoP\Translation\Facades\TranslationAPIFacade;
class VarsHooks extends \PoP\Hooks\AbstractHookSet
{
    protected function init()
    {
        $this->hooksAPI->addAction('ApplicationState:addVars', array($this, 'addVars'), 10, 1);
        $this->hooksAPI->addAction('augmentVarsProperties', [$this, 'augmentVarsProperties'], 10, 1);
        $this->hooksAPI->addFilter(\PoP\ComponentModel\ModelInstance\ModelInstance::HOOK_COMPONENTS_RESULT, array($this, 'getModelInstanceComponentsFromVars'));
    }
    /**
     * Check if to use nested mutations from the GraphQL server config
     * @param array<array> $vars_in_array
     */
    public function augmentVarsProperties(array $vars_in_array) : void
    {
        $vars =& $vars_in_array[0];
        $vars['nested-mutations-enabled'] = $vars['standard-graphql'] ? \GraphQLByPoP\GraphQLServer\ComponentConfiguration::enableNestedMutations() : \true;
        // Check if the value has been defined by configuration. If so, use it.
        // Otherwise, use the defaults:
        // By default, Standard GraphQL has introspection enabled, and PQL is not
        $enableGraphQLIntrospection = \GraphQLByPoP\GraphQLServer\ComponentConfiguration::enableGraphQLIntrospection();
        $vars['graphql-introspection-enabled'] = $enableGraphQLIntrospection !== null ? $enableGraphQLIntrospection : $vars['standard-graphql'];
    }
    /**
     * @param array<array> $vars_in_array
     */
    public function addVars(array $vars_in_array) : void
    {
        $vars =& $vars_in_array[0];
        $instanceManager = \PoP\ComponentModel\Facades\Instances\InstanceManagerFacade::getInstance();
        /** @var GraphQLDataStructureFormatter */
        $graphQLDataStructureFormatter = $instanceManager->getInstance(\PoP\GraphQLAPI\DataStructureFormatters\GraphQLDataStructureFormatter::class);
        if ($vars['scheme'] == \PoP\API\Response\Schemes::API && $vars['datastructure'] == $graphQLDataStructureFormatter->getName()) {
            $vars['edit-schema'] = \GraphQLByPoP\GraphQLServer\Configuration\Request::editSchema();
        }
    }
    public function getModelInstanceComponentsFromVars($components)
    {
        $translationAPI = \PoP\Translation\Facades\TranslationAPIFacade::getInstance();
        $vars = \PoP\ComponentModel\State\ApplicationState::getVars();
        if (isset($vars['edit-schema'])) {
            $components[] = $translationAPI->__('edit schema:', 'graphql-server') . $vars['edit-schema'];
        }
        if ($graphQLOperationType = $vars['graphql-operation-type'] ?? null) {
            $components[] = $translationAPI->__('GraphQL operation type:', 'graphql-server') . $graphQLOperationType;
        }
        $components[] = $translationAPI->__('enable nested mutations:', 'graphql-server') . $vars['nested-mutations-enabled'];
        $components[] = $translationAPI->__('enable GraphQL introspection:', 'graphql-server') . $vars['graphql-introspection-enabled'];
        return $components;
    }
}
