<?php

declare (strict_types=1);
namespace GraphQLByPoP\GraphQLServer\TypeDataLoaders;

use GraphQLByPoP\GraphQLServer\ObjectFacades\QueryRootObjectFacade;
use PoP\ComponentModel\TypeDataLoaders\AbstractTypeDataLoader;
class QueryRootTypeDataLoader extends \PoP\ComponentModel\TypeDataLoaders\AbstractTypeDataLoader
{
    public function getObjects(array $ids) : array
    {
        return [\GraphQLByPoP\GraphQLServer\ObjectFacades\QueryRootObjectFacade::getInstance()];
    }
}
