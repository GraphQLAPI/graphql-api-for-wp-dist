<?php

declare (strict_types=1);
namespace PoPCMSSchema\CustomPosts\TypeResolvers\UnionType;

use PoP\ComponentModel\RelationalTypeDataLoaders\RelationalTypeDataLoaderInterface;
use PoP\ComponentModel\TypeResolvers\InterfaceType\InterfaceTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\UnionType\AbstractUnionTypeResolver;
use PoPCMSSchema\CustomPosts\RelationalTypeDataLoaders\UnionType\CustomPostUnionTypeDataLoader;
use PoPCMSSchema\CustomPosts\TypeResolvers\InterfaceType\CustomPostInterfaceTypeResolver;
class CustomPostUnionTypeResolver extends AbstractUnionTypeResolver
{
    /**
     * @var \PoPCMSSchema\CustomPosts\RelationalTypeDataLoaders\UnionType\CustomPostUnionTypeDataLoader|null
     */
    private $customPostUnionTypeDataLoader;
    /**
     * @var \PoPCMSSchema\CustomPosts\TypeResolvers\InterfaceType\CustomPostInterfaceTypeResolver|null
     */
    private $customPostInterfaceTypeResolver;
    /**
     * @param \PoPCMSSchema\CustomPosts\RelationalTypeDataLoaders\UnionType\CustomPostUnionTypeDataLoader $customPostUnionTypeDataLoader
     */
    public final function setCustomPostUnionTypeDataLoader($customPostUnionTypeDataLoader) : void
    {
        $this->customPostUnionTypeDataLoader = $customPostUnionTypeDataLoader;
    }
    protected final function getCustomPostUnionTypeDataLoader() : CustomPostUnionTypeDataLoader
    {
        /** @var CustomPostUnionTypeDataLoader */
        return $this->customPostUnionTypeDataLoader = $this->customPostUnionTypeDataLoader ?? $this->instanceManager->getInstance(CustomPostUnionTypeDataLoader::class);
    }
    /**
     * @param \PoPCMSSchema\CustomPosts\TypeResolvers\InterfaceType\CustomPostInterfaceTypeResolver $customPostInterfaceTypeResolver
     */
    public final function setCustomPostInterfaceTypeResolver($customPostInterfaceTypeResolver) : void
    {
        $this->customPostInterfaceTypeResolver = $customPostInterfaceTypeResolver;
    }
    protected final function getCustomPostInterfaceTypeResolver() : CustomPostInterfaceTypeResolver
    {
        /** @var CustomPostInterfaceTypeResolver */
        return $this->customPostInterfaceTypeResolver = $this->customPostInterfaceTypeResolver ?? $this->instanceManager->getInstance(CustomPostInterfaceTypeResolver::class);
    }
    public function getTypeName() : string
    {
        return 'CustomPostUnion';
    }
    public function getTypeDescription() : ?string
    {
        return $this->__('Union of \'custom post\' type resolvers', 'customposts');
    }
    public function getRelationalTypeDataLoader() : RelationalTypeDataLoaderInterface
    {
        return $this->getCustomPostUnionTypeDataLoader();
    }
    /**
     * @return InterfaceTypeResolverInterface[]
     */
    public function getUnionTypeInterfaceTypeResolvers() : array
    {
        return [$this->getCustomPostInterfaceTypeResolver()];
    }
}
