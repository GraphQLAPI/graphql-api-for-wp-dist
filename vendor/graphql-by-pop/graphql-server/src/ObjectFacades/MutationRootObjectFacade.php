<?php

declare (strict_types=1);
namespace GraphQLByPoP\GraphQLServer\ObjectFacades;

use GraphQLByPoP\GraphQLServer\ObjectModels\MutationRoot;
use PoP\Root\Container\ContainerBuilderFactory;
class MutationRootObjectFacade
{
    public static function getInstance() : \GraphQLByPoP\GraphQLServer\ObjectModels\MutationRoot
    {
        $containerBuilderFactory = \PoP\Root\Container\ContainerBuilderFactory::getInstance();
        return $containerBuilderFactory->get(\GraphQLByPoP\GraphQLServer\ObjectModels\MutationRoot::class);
    }
}
