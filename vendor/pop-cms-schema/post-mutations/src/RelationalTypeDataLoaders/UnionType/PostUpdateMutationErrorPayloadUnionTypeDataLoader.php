<?php

declare (strict_types=1);
namespace PoPCMSSchema\PostMutations\RelationalTypeDataLoaders\UnionType;

use PoPCMSSchema\PostMutations\TypeResolvers\UnionType\PostUpdateMutationErrorPayloadUnionTypeResolver;
use PoP\ComponentModel\RelationalTypeDataLoaders\UnionType\AbstractUnionTypeDataLoader;
use PoP\ComponentModel\TypeResolvers\UnionType\UnionTypeResolverInterface;
class PostUpdateMutationErrorPayloadUnionTypeDataLoader extends AbstractUnionTypeDataLoader
{
    /**
     * @var \PoPCMSSchema\PostMutations\TypeResolvers\UnionType\PostUpdateMutationErrorPayloadUnionTypeResolver|null
     */
    private $postUpdateMutationErrorPayloadUnionTypeResolver;
    /**
     * @param \PoPCMSSchema\PostMutations\TypeResolvers\UnionType\PostUpdateMutationErrorPayloadUnionTypeResolver $postUpdateMutationErrorPayloadUnionTypeResolver
     */
    public final function setPostUpdateMutationErrorPayloadUnionTypeResolver($postUpdateMutationErrorPayloadUnionTypeResolver) : void
    {
        $this->postUpdateMutationErrorPayloadUnionTypeResolver = $postUpdateMutationErrorPayloadUnionTypeResolver;
    }
    protected final function getPostUpdateMutationErrorPayloadUnionTypeResolver() : PostUpdateMutationErrorPayloadUnionTypeResolver
    {
        /** @var PostUpdateMutationErrorPayloadUnionTypeResolver */
        return $this->postUpdateMutationErrorPayloadUnionTypeResolver = $this->postUpdateMutationErrorPayloadUnionTypeResolver ?? $this->instanceManager->getInstance(PostUpdateMutationErrorPayloadUnionTypeResolver::class);
    }
    protected function getUnionTypeResolver() : UnionTypeResolverInterface
    {
        return $this->getPostUpdateMutationErrorPayloadUnionTypeResolver();
    }
}
