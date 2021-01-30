<?php

declare (strict_types=1);
namespace GraphQLByPoP\GraphQLServer\ObjectFacades;

use GraphQLByPoP\GraphQLServer\ObjectModels\QueryRoot;
use PoP\Root\Container\ContainerBuilderFactory;
class QueryRootObjectFacade
{
    public static function getInstance() : \GraphQLByPoP\GraphQLServer\ObjectModels\QueryRoot
    {
        $containerBuilderFactory = \PoP\Root\Container\ContainerBuilderFactory::getInstance();
        return $containerBuilderFactory->get(\GraphQLByPoP\GraphQLServer\ObjectModels\QueryRoot::class);
    }
}
