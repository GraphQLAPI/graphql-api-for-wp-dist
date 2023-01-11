<?php

declare (strict_types=1);
namespace GraphQLByPoP\GraphQLServer\State;

use GraphQLByPoP\GraphQLServer\Configuration\Request;
use GraphQLByPoP\GraphQLServer\Module;
use GraphQLByPoP\GraphQLServer\ModuleConfiguration;
use PoP\Root\App;
use PoP\Root\Module as RootModule;
use PoP\Root\ModuleConfiguration as RootModuleConfiguration;
use PoP\Root\State\AbstractAppStateProvider;
use PoPAPI\GraphQLAPI\DataStructureFormatters\GraphQLDataStructureFormatter;
class AppStateProvider extends AbstractAppStateProvider
{
    /**
     * @var \PoPAPI\GraphQLAPI\DataStructureFormatters\GraphQLDataStructureFormatter|null
     */
    private $graphQLDataStructureFormatter;
    /**
     * @param \PoPAPI\GraphQLAPI\DataStructureFormatters\GraphQLDataStructureFormatter $graphQLDataStructureFormatter
     */
    public final function setGraphQLDataStructureFormatter($graphQLDataStructureFormatter) : void
    {
        $this->graphQLDataStructureFormatter = $graphQLDataStructureFormatter;
    }
    protected final function getGraphQLDataStructureFormatter() : GraphQLDataStructureFormatter
    {
        /** @var GraphQLDataStructureFormatter */
        return $this->graphQLDataStructureFormatter = $this->graphQLDataStructureFormatter ?? $this->instanceManager->getInstance(GraphQLDataStructureFormatter::class);
    }
    /**
     * @param array<string,mixed> $state
     */
    public function initialize(&$state) : void
    {
        /** @var RootModuleConfiguration */
        $rootModuleConfiguration = App::getModule(RootModule::class)->getConfiguration();
        if ($rootModuleConfiguration->enablePassingStateViaRequest()) {
            $state['edit-schema'] = Request::editSchema();
        } else {
            $state['edit-schema'] = null;
        }
    }
    /**
     * @param array<string,mixed> $state
     */
    public function execute(&$state) : void
    {
        /**
         * Call ModuleConfiguration only after hooks from
         * SchemaConfigurationExecuter have been initialized.
         * That's why these are called on `execute` and not `initialize`.
         *
         * @var ModuleConfiguration
         */
        $moduleConfiguration = App::getModule(Module::class)->getConfiguration();
        $state['graphql-introspection-enabled'] = $moduleConfiguration->enableGraphQLIntrospection() ?? \true;
    }
}
