<?php

declare (strict_types=1);
namespace PoPCMSSchema\CommentMutations\RelationalTypeDataLoaders\UnionType;

use PoPCMSSchema\CommentMutations\TypeResolvers\UnionType\RootAddCommentToCustomPostMutationErrorPayloadUnionTypeResolver;
use PoP\ComponentModel\RelationalTypeDataLoaders\UnionType\AbstractUnionTypeDataLoader;
use PoP\ComponentModel\TypeResolvers\UnionType\UnionTypeResolverInterface;
class RootAddCommentToCustomPostMutationErrorPayloadUnionTypeDataLoader extends AbstractUnionTypeDataLoader
{
    /**
     * @var \PoPCMSSchema\CommentMutations\TypeResolvers\UnionType\RootAddCommentToCustomPostMutationErrorPayloadUnionTypeResolver|null
     */
    private $rootAddCommentToCustomPostMutationErrorPayloadUnionTypeResolver;
    /**
     * @param \PoPCMSSchema\CommentMutations\TypeResolvers\UnionType\RootAddCommentToCustomPostMutationErrorPayloadUnionTypeResolver $rootAddCommentToCustomPostMutationErrorPayloadUnionTypeResolver
     */
    public final function setRootAddCommentToCustomPostMutationErrorPayloadUnionTypeResolver($rootAddCommentToCustomPostMutationErrorPayloadUnionTypeResolver) : void
    {
        $this->rootAddCommentToCustomPostMutationErrorPayloadUnionTypeResolver = $rootAddCommentToCustomPostMutationErrorPayloadUnionTypeResolver;
    }
    protected final function getRootAddCommentToCustomPostMutationErrorPayloadUnionTypeResolver() : RootAddCommentToCustomPostMutationErrorPayloadUnionTypeResolver
    {
        /** @var RootAddCommentToCustomPostMutationErrorPayloadUnionTypeResolver */
        return $this->rootAddCommentToCustomPostMutationErrorPayloadUnionTypeResolver = $this->rootAddCommentToCustomPostMutationErrorPayloadUnionTypeResolver ?? $this->instanceManager->getInstance(RootAddCommentToCustomPostMutationErrorPayloadUnionTypeResolver::class);
    }
    protected function getUnionTypeResolver() : UnionTypeResolverInterface
    {
        return $this->getRootAddCommentToCustomPostMutationErrorPayloadUnionTypeResolver();
    }
}
