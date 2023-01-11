<?php

declare (strict_types=1);
namespace GraphQLByPoP\GraphQLServer\RelationalTypeDataLoaders\ObjectType;

use GraphQLByPoP\GraphQLServer\ObjectModels\MutationRoot;
use PoP\ComponentModel\RelationalTypeDataLoaders\ObjectType\AbstractObjectTypeDataLoader;
class MutationRootTypeDataLoader extends AbstractObjectTypeDataLoader
{
    /**
     * @var \GraphQLByPoP\GraphQLServer\ObjectModels\MutationRoot|null
     */
    private $mutationRoot;
    /**
     * @param \GraphQLByPoP\GraphQLServer\ObjectModels\MutationRoot $mutationRoot
     */
    public final function setMutationRoot($mutationRoot) : void
    {
        $this->mutationRoot = $mutationRoot;
    }
    protected final function getMutationRoot() : MutationRoot
    {
        /** @var MutationRoot */
        return $this->mutationRoot = $this->mutationRoot ?? $this->instanceManager->getInstance(MutationRoot::class);
    }
    /**
     * @param array<string|int> $ids
     * @return array<object|null>
     */
    public function getObjects($ids) : array
    {
        return [$this->getMutationRoot()];
    }
}
