<?php

declare (strict_types=1);
namespace PoPCMSSchema\Categories\TypeResolvers\UnionType;

use PoP\ComponentModel\RelationalTypeDataLoaders\RelationalTypeDataLoaderInterface;
use PoP\ComponentModel\TypeResolvers\InterfaceType\InterfaceTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\UnionType\AbstractUnionTypeResolver;
use PoPCMSSchema\Categories\RelationalTypeDataLoaders\UnionType\CategoryUnionTypeDataLoader;
use PoPCMSSchema\Categories\TypeResolvers\InterfaceType\CategoryInterfaceTypeResolver;
class CategoryUnionTypeResolver extends AbstractUnionTypeResolver
{
    /**
     * @var \PoPCMSSchema\Categories\RelationalTypeDataLoaders\UnionType\CategoryUnionTypeDataLoader|null
     */
    private $categoryUnionTypeDataLoader;
    /**
     * @var \PoPCMSSchema\Categories\TypeResolvers\InterfaceType\CategoryInterfaceTypeResolver|null
     */
    private $categoryInterfaceTypeResolver;
    /**
     * @param \PoPCMSSchema\Categories\RelationalTypeDataLoaders\UnionType\CategoryUnionTypeDataLoader $categoryUnionTypeDataLoader
     */
    public final function setCategoryUnionTypeDataLoader($categoryUnionTypeDataLoader) : void
    {
        $this->categoryUnionTypeDataLoader = $categoryUnionTypeDataLoader;
    }
    protected final function getCategoryUnionTypeDataLoader() : CategoryUnionTypeDataLoader
    {
        /** @var CategoryUnionTypeDataLoader */
        return $this->categoryUnionTypeDataLoader = $this->categoryUnionTypeDataLoader ?? $this->instanceManager->getInstance(CategoryUnionTypeDataLoader::class);
    }
    /**
     * @param \PoPCMSSchema\Categories\TypeResolvers\InterfaceType\CategoryInterfaceTypeResolver $categoryInterfaceTypeResolver
     */
    public final function setCategoryInterfaceTypeResolver($categoryInterfaceTypeResolver) : void
    {
        $this->categoryInterfaceTypeResolver = $categoryInterfaceTypeResolver;
    }
    protected final function getCategoryInterfaceTypeResolver() : CategoryInterfaceTypeResolver
    {
        /** @var CategoryInterfaceTypeResolver */
        return $this->categoryInterfaceTypeResolver = $this->categoryInterfaceTypeResolver ?? $this->instanceManager->getInstance(CategoryInterfaceTypeResolver::class);
    }
    public function getTypeName() : string
    {
        return 'CategoryUnion';
    }
    public function getTypeDescription() : ?string
    {
        return $this->__('Union of \'category\' type resolvers', 'categories');
    }
    public function getRelationalTypeDataLoader() : RelationalTypeDataLoaderInterface
    {
        return $this->getCategoryUnionTypeDataLoader();
    }
    /**
     * @return InterfaceTypeResolverInterface[]
     */
    public function getUnionTypeInterfaceTypeResolvers() : array
    {
        return [$this->getCategoryInterfaceTypeResolver()];
    }
}
