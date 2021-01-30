<?php

declare (strict_types=1);
namespace GraphQLByPoP\GraphQLServer\Facades\Schema;

use GraphQLByPoP\GraphQLServer\Schema\GraphQLSchemaDefinitionServiceInterface;
use PoP\Root\Container\ContainerBuilderFactory;
class GraphQLSchemaDefinitionServiceFacade
{
    public static function getInstance() : \GraphQLByPoP\GraphQLServer\Schema\GraphQLSchemaDefinitionServiceInterface
    {
        /**
         * @var GraphQLSchemaDefinitionServiceInterface
         */
        $service = \PoP\Root\Container\ContainerBuilderFactory::getInstance()->get(\GraphQLByPoP\GraphQLServer\Schema\GraphQLSchemaDefinitionServiceInterface::class);
        return $service;
    }
}
