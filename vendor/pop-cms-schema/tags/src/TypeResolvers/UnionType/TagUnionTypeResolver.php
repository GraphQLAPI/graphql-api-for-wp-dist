<?php

declare (strict_types=1);
namespace PoPCMSSchema\Tags\TypeResolvers\UnionType;

use PoP\ComponentModel\RelationalTypeDataLoaders\RelationalTypeDataLoaderInterface;
use PoP\ComponentModel\TypeResolvers\InterfaceType\InterfaceTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\UnionType\AbstractUnionTypeResolver;
use PoPCMSSchema\Tags\RelationalTypeDataLoaders\UnionType\TagUnionTypeDataLoader;
use PoPCMSSchema\Tags\TypeResolvers\InterfaceType\TagInterfaceTypeResolver;
class TagUnionTypeResolver extends AbstractUnionTypeResolver
{
    /**
     * @var \PoPCMSSchema\Tags\RelationalTypeDataLoaders\UnionType\TagUnionTypeDataLoader|null
     */
    private $tagUnionTypeDataLoader;
    /**
     * @var \PoPCMSSchema\Tags\TypeResolvers\InterfaceType\TagInterfaceTypeResolver|null
     */
    private $tagInterfaceTypeResolver;
    /**
     * @param \PoPCMSSchema\Tags\RelationalTypeDataLoaders\UnionType\TagUnionTypeDataLoader $tagUnionTypeDataLoader
     */
    public final function setTagUnionTypeDataLoader($tagUnionTypeDataLoader) : void
    {
        $this->tagUnionTypeDataLoader = $tagUnionTypeDataLoader;
    }
    protected final function getTagUnionTypeDataLoader() : TagUnionTypeDataLoader
    {
        /** @var TagUnionTypeDataLoader */
        return $this->tagUnionTypeDataLoader = $this->tagUnionTypeDataLoader ?? $this->instanceManager->getInstance(TagUnionTypeDataLoader::class);
    }
    /**
     * @param \PoPCMSSchema\Tags\TypeResolvers\InterfaceType\TagInterfaceTypeResolver $tagInterfaceTypeResolver
     */
    public final function setTagInterfaceTypeResolver($tagInterfaceTypeResolver) : void
    {
        $this->tagInterfaceTypeResolver = $tagInterfaceTypeResolver;
    }
    protected final function getTagInterfaceTypeResolver() : TagInterfaceTypeResolver
    {
        /** @var TagInterfaceTypeResolver */
        return $this->tagInterfaceTypeResolver = $this->tagInterfaceTypeResolver ?? $this->instanceManager->getInstance(TagInterfaceTypeResolver::class);
    }
    public function getTypeName() : string
    {
        return 'TagUnion';
    }
    public function getTypeDescription() : ?string
    {
        return $this->__('Union of \'tag\' type resolvers', 'tags');
    }
    public function getRelationalTypeDataLoader() : RelationalTypeDataLoaderInterface
    {
        return $this->getTagUnionTypeDataLoader();
    }
    /**
     * @return InterfaceTypeResolverInterface[]
     */
    public function getUnionTypeInterfaceTypeResolvers() : array
    {
        return [$this->getTagInterfaceTypeResolver()];
    }
}
