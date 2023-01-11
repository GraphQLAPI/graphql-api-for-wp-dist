<?php

declare (strict_types=1);
namespace PoPCMSSchema\PostCategoryMutations\RelationalTypeDataLoaders\UnionType;

use PoPCMSSchema\PostCategoryMutations\TypeResolvers\UnionType\PostSetCategoriesMutationErrorPayloadUnionTypeResolver;
use PoP\ComponentModel\RelationalTypeDataLoaders\UnionType\AbstractUnionTypeDataLoader;
use PoP\ComponentModel\TypeResolvers\UnionType\UnionTypeResolverInterface;
class PostSetCategoriesMutationErrorPayloadUnionTypeDataLoader extends AbstractUnionTypeDataLoader
{
    /**
     * @var \PoPCMSSchema\PostCategoryMutations\TypeResolvers\UnionType\PostSetCategoriesMutationErrorPayloadUnionTypeResolver|null
     */
    private $postSetCategoriesMutationErrorPayloadUnionTypeResolver;
    /**
     * @param \PoPCMSSchema\PostCategoryMutations\TypeResolvers\UnionType\PostSetCategoriesMutationErrorPayloadUnionTypeResolver $postSetCategoriesMutationErrorPayloadUnionTypeResolver
     */
    public final function setPostSetCategoriesMutationErrorPayloadUnionTypeResolver($postSetCategoriesMutationErrorPayloadUnionTypeResolver) : void
    {
        $this->postSetCategoriesMutationErrorPayloadUnionTypeResolver = $postSetCategoriesMutationErrorPayloadUnionTypeResolver;
    }
    protected final function getPostSetCategoriesMutationErrorPayloadUnionTypeResolver() : PostSetCategoriesMutationErrorPayloadUnionTypeResolver
    {
        /** @var PostSetCategoriesMutationErrorPayloadUnionTypeResolver */
        return $this->postSetCategoriesMutationErrorPayloadUnionTypeResolver = $this->postSetCategoriesMutationErrorPayloadUnionTypeResolver ?? $this->instanceManager->getInstance(PostSetCategoriesMutationErrorPayloadUnionTypeResolver::class);
    }
    protected function getUnionTypeResolver() : UnionTypeResolverInterface
    {
        return $this->getPostSetCategoriesMutationErrorPayloadUnionTypeResolver();
    }
}
