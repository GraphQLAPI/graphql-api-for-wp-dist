<?php

declare (strict_types=1);
namespace GraphQLByPoP\GraphQLServer\RelationalTypeDataLoaders\ObjectType;

use GraphQLByPoP\GraphQLServer\ObjectModels\QueryRoot;
use PoP\ComponentModel\RelationalTypeDataLoaders\ObjectType\AbstractObjectTypeDataLoader;
class QueryRootTypeDataLoader extends AbstractObjectTypeDataLoader
{
    /**
     * @var \GraphQLByPoP\GraphQLServer\ObjectModels\QueryRoot|null
     */
    private $queryRoot;
    /**
     * @param \GraphQLByPoP\GraphQLServer\ObjectModels\QueryRoot $queryRoot
     */
    public final function setQueryRoot($queryRoot) : void
    {
        $this->queryRoot = $queryRoot;
    }
    protected final function getQueryRoot() : QueryRoot
    {
        /** @var QueryRoot */
        return $this->queryRoot = $this->queryRoot ?? $this->instanceManager->getInstance(QueryRoot::class);
    }
    /**
     * @param array<string|int> $ids
     * @return array<object|null>
     */
    public function getObjects($ids) : array
    {
        return [$this->getQueryRoot()];
    }
}
