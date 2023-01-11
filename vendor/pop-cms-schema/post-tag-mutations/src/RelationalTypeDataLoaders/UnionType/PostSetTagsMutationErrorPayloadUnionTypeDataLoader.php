<?php

declare (strict_types=1);
namespace PoPCMSSchema\PostTagMutations\RelationalTypeDataLoaders\UnionType;

use PoPCMSSchema\PostTagMutations\TypeResolvers\UnionType\PostSetTagsMutationErrorPayloadUnionTypeResolver;
use PoP\ComponentModel\RelationalTypeDataLoaders\UnionType\AbstractUnionTypeDataLoader;
use PoP\ComponentModel\TypeResolvers\UnionType\UnionTypeResolverInterface;
class PostSetTagsMutationErrorPayloadUnionTypeDataLoader extends AbstractUnionTypeDataLoader
{
    /**
     * @var \PoPCMSSchema\PostTagMutations\TypeResolvers\UnionType\PostSetTagsMutationErrorPayloadUnionTypeResolver|null
     */
    private $postSetTagsMutationErrorPayloadUnionTypeResolver;
    /**
     * @param \PoPCMSSchema\PostTagMutations\TypeResolvers\UnionType\PostSetTagsMutationErrorPayloadUnionTypeResolver $postSetTagsMutationErrorPayloadUnionTypeResolver
     */
    public final function setPostSetTagsMutationErrorPayloadUnionTypeResolver($postSetTagsMutationErrorPayloadUnionTypeResolver) : void
    {
        $this->postSetTagsMutationErrorPayloadUnionTypeResolver = $postSetTagsMutationErrorPayloadUnionTypeResolver;
    }
    protected final function getPostSetTagsMutationErrorPayloadUnionTypeResolver() : PostSetTagsMutationErrorPayloadUnionTypeResolver
    {
        /** @var PostSetTagsMutationErrorPayloadUnionTypeResolver */
        return $this->postSetTagsMutationErrorPayloadUnionTypeResolver = $this->postSetTagsMutationErrorPayloadUnionTypeResolver ?? $this->instanceManager->getInstance(PostSetTagsMutationErrorPayloadUnionTypeResolver::class);
    }
    protected function getUnionTypeResolver() : UnionTypeResolverInterface
    {
        return $this->getPostSetTagsMutationErrorPayloadUnionTypeResolver();
    }
}
