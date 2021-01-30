<?php

declare (strict_types=1);
namespace PoP\ComponentModel\Registries;

class TypeRegistry implements \PoP\ComponentModel\Registries\TypeRegistryInterface
{
    /**
     * @var string[]
     */
    protected $typeResolverClasses = [];
    public function addTypeResolverClass(string $typeResolverClass) : void
    {
        $this->typeResolverClasses[] = $typeResolverClass;
    }
    public function getTypeResolverClasses() : array
    {
        return $this->typeResolverClasses;
    }
}
