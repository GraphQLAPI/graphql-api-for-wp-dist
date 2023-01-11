<?php

declare (strict_types=1);
namespace PoPCMSSchema\Categories\RelationalTypeDataLoaders\UnionType;

use PoP\ComponentModel\RelationalTypeDataLoaders\UnionType\AbstractUnionTypeDataLoader;
use PoP\ComponentModel\TypeResolvers\UnionType\UnionTypeResolverInterface;
use PoPCMSSchema\Categories\TypeResolvers\UnionType\CategoryUnionTypeResolver;
class CategoryUnionTypeDataLoader extends AbstractUnionTypeDataLoader
{
    /**
     * @var \PoPCMSSchema\Categories\TypeResolvers\UnionType\CategoryUnionTypeResolver|null
     */
    private $categoryUnionTypeResolver;
    /**
     * @param \PoPCMSSchema\Categories\TypeResolvers\UnionType\CategoryUnionTypeResolver $categoryUnionTypeResolver
     */
    public final function setCategoryUnionTypeResolver($categoryUnionTypeResolver) : void
    {
        $this->categoryUnionTypeResolver = $categoryUnionTypeResolver;
    }
    protected final function getCategoryUnionTypeResolver() : CategoryUnionTypeResolver
    {
        /** @var CategoryUnionTypeResolver */
        return $this->categoryUnionTypeResolver = $this->categoryUnionTypeResolver ?? $this->instanceManager->getInstance(CategoryUnionTypeResolver::class);
    }
    protected function getUnionTypeResolver() : UnionTypeResolverInterface
    {
        return $this->getCategoryUnionTypeResolver();
    }
}
