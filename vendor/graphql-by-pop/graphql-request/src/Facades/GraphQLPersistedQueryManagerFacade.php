<?php

declare (strict_types=1);
namespace GraphQLByPoP\GraphQLRequest\Facades;

use GraphQLByPoP\GraphQLRequest\PersistedQueries\GraphQLPersistedQueryManagerInterface;
use PoP\Root\Container\ContainerBuilderFactory;
class GraphQLPersistedQueryManagerFacade
{
    public static function getInstance() : \GraphQLByPoP\GraphQLRequest\PersistedQueries\GraphQLPersistedQueryManagerInterface
    {
        /**
         * @var GraphQLPersistedQueryManagerInterface
         */
        $service = \PoP\Root\Container\ContainerBuilderFactory::getInstance()->get(\GraphQLByPoP\GraphQLRequest\PersistedQueries\GraphQLPersistedQueryManagerInterface::class);
        return $service;
    }
}
