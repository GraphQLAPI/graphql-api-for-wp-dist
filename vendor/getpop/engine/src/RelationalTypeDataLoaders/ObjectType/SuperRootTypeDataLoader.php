<?php

declare (strict_types=1);
namespace PoP\Engine\RelationalTypeDataLoaders\ObjectType;

use PoP\ComponentModel\RelationalTypeDataLoaders\ObjectType\AbstractObjectTypeDataLoader;
use PoP\Engine\ObjectModels\SuperRoot;
class SuperRootTypeDataLoader extends AbstractObjectTypeDataLoader
{
    /**
     * @var \PoP\Engine\ObjectModels\SuperRoot|null
     */
    private $superRoot;
    /**
     * @param \PoP\Engine\ObjectModels\SuperRoot $superRoot
     */
    public final function setSuperRoot($superRoot) : void
    {
        $this->superRoot = $superRoot;
    }
    protected final function getSuperRoot() : SuperRoot
    {
        /** @var SuperRoot */
        return $this->superRoot = $this->superRoot ?? $this->instanceManager->getInstance(SuperRoot::class);
    }
    /**
     * @param array<string|int> $ids
     * @return array<object|null>
     */
    public function getObjects($ids) : array
    {
        return [$this->getSuperRoot()];
    }
}
