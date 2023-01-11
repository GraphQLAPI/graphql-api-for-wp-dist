<?php

declare (strict_types=1);
namespace PoPCMSSchema\PostCategoryMutations\RelationalTypeDataLoaders\UnionType;

use PoPCMSSchema\PostCategoryMutations\TypeResolvers\UnionType\RootSetCategoriesOnPostMutationErrorPayloadUnionTypeResolver;
use PoP\ComponentModel\RelationalTypeDataLoaders\UnionType\AbstractUnionTypeDataLoader;
use PoP\ComponentModel\TypeResolvers\UnionType\UnionTypeResolverInterface;
class RootSetCategoriesOnPostMutationErrorPayloadUnionTypeDataLoader extends AbstractUnionTypeDataLoader
{
    /**
     * @var \PoPCMSSchema\PostCategoryMutations\TypeResolvers\UnionType\RootSetCategoriesOnPostMutationErrorPayloadUnionTypeResolver|null
     */
    private $rootSetCategoriesOnPostMutationErrorPayloadUnionTypeResolver;
    /**
     * @param \PoPCMSSchema\PostCategoryMutations\TypeResolvers\UnionType\RootSetCategoriesOnPostMutationErrorPayloadUnionTypeResolver $rootSetCategoriesOnPostMutationErrorPayloadUnionTypeResolver
     */
    public final function setRootSetCategoriesOnPostMutationErrorPayloadUnionTypeResolver($rootSetCategoriesOnPostMutationErrorPayloadUnionTypeResolver) : void
    {
        $this->rootSetCategoriesOnPostMutationErrorPayloadUnionTypeResolver = $rootSetCategoriesOnPostMutationErrorPayloadUnionTypeResolver;
    }
    protected final function getRootSetCategoriesOnPostMutationErrorPayloadUnionTypeResolver() : RootSetCategoriesOnPostMutationErrorPayloadUnionTypeResolver
    {
        /** @var RootSetCategoriesOnPostMutationErrorPayloadUnionTypeResolver */
        return $this->rootSetCategoriesOnPostMutationErrorPayloadUnionTypeResolver = $this->rootSetCategoriesOnPostMutationErrorPayloadUnionTypeResolver ?? $this->instanceManager->getInstance(RootSetCategoriesOnPostMutationErrorPayloadUnionTypeResolver::class);
    }
    protected function getUnionTypeResolver() : UnionTypeResolverInterface
    {
        return $this->getRootSetCategoriesOnPostMutationErrorPayloadUnionTypeResolver();
    }
}
