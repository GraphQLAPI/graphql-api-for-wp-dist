<?php

declare (strict_types=1);
namespace GraphQLByPoP\GraphQLServer\RouteModuleProcessors;

use GraphQLByPoP\GraphQLQuery\Schema\OperationTypes;
use PoP\Routing\RouteNatures;
use PoP\ModuleRouting\AbstractEntryRouteModuleProcessor;
use GraphQLByPoP\GraphQLServer\ModuleProcessors\RootRelationalFieldDataloadModuleProcessor;
use PoP\API\Response\Schemes as APISchemes;
class EntryRouteModuleProcessor extends \PoP\ModuleRouting\AbstractEntryRouteModuleProcessor
{
    /**
     * @return array<string, array<array>>
     */
    public function getModulesVarsPropertiesByNature() : array
    {
        $ret = array();
        $ret[\PoP\Routing\RouteNatures::HOME][] = ['module' => [\GraphQLByPoP\GraphQLServer\ModuleProcessors\RootRelationalFieldDataloadModuleProcessor::class, \GraphQLByPoP\GraphQLServer\ModuleProcessors\RootRelationalFieldDataloadModuleProcessor::MODULE_DATALOAD_RELATIONALFIELDS_QUERYROOT], 'conditions' => ['scheme' => \PoP\API\Response\Schemes::API, 'graphql-operation-type' => \GraphQLByPoP\GraphQLQuery\Schema\OperationTypes::QUERY]];
        $ret[\PoP\Routing\RouteNatures::HOME][] = ['module' => [\GraphQLByPoP\GraphQLServer\ModuleProcessors\RootRelationalFieldDataloadModuleProcessor::class, \GraphQLByPoP\GraphQLServer\ModuleProcessors\RootRelationalFieldDataloadModuleProcessor::MODULE_DATALOAD_RELATIONALFIELDS_MUTATIONROOT], 'conditions' => ['scheme' => \PoP\API\Response\Schemes::API, 'graphql-operation-type' => \GraphQLByPoP\GraphQLQuery\Schema\OperationTypes::MUTATION]];
        return $ret;
    }
}
