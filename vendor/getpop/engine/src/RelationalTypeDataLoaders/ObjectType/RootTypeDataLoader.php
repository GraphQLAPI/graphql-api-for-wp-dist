<?php

declare (strict_types=1);
namespace PoP\Engine\RelationalTypeDataLoaders\ObjectType;

use PoP\ComponentModel\RelationalTypeDataLoaders\ObjectType\AbstractObjectTypeDataLoader;
use PoP\Engine\ObjectModels\Root;
class RootTypeDataLoader extends AbstractObjectTypeDataLoader
{
    /**
     * @var \PoP\Engine\ObjectModels\Root|null
     */
    private $root;
    /**
     * @param \PoP\Engine\ObjectModels\Root $root
     */
    public final function setRoot($root) : void
    {
        $this->root = $root;
    }
    protected final function getRoot() : Root
    {
        /** @var Root */
        return $this->root = $this->root ?? $this->instanceManager->getInstance(Root::class);
    }
    /**
     * @param array<string|int> $ids
     * @return array<object|null>
     */
    public function getObjects($ids) : array
    {
        return [$this->getRoot()];
    }
}
