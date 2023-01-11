<?php

declare (strict_types=1);
namespace PoPCMSSchema\Tags\RelationalTypeDataLoaders\UnionType;

use PoP\ComponentModel\RelationalTypeDataLoaders\UnionType\AbstractUnionTypeDataLoader;
use PoP\ComponentModel\TypeResolvers\UnionType\UnionTypeResolverInterface;
use PoPCMSSchema\Tags\TypeResolvers\UnionType\TagUnionTypeResolver;
class TagUnionTypeDataLoader extends AbstractUnionTypeDataLoader
{
    /**
     * @var \PoPCMSSchema\Tags\TypeResolvers\UnionType\TagUnionTypeResolver|null
     */
    private $tagUnionTypeResolver;
    /**
     * @param \PoPCMSSchema\Tags\TypeResolvers\UnionType\TagUnionTypeResolver $tagUnionTypeResolver
     */
    public final function setTagUnionTypeResolver($tagUnionTypeResolver) : void
    {
        $this->tagUnionTypeResolver = $tagUnionTypeResolver;
    }
    protected final function getTagUnionTypeResolver() : TagUnionTypeResolver
    {
        /** @var TagUnionTypeResolver */
        return $this->tagUnionTypeResolver = $this->tagUnionTypeResolver ?? $this->instanceManager->getInstance(TagUnionTypeResolver::class);
    }
    protected function getUnionTypeResolver() : UnionTypeResolverInterface
    {
        return $this->getTagUnionTypeResolver();
    }
}
