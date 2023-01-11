<?php

declare (strict_types=1);
namespace PoPCMSSchema\CommentMutations\RelationalTypeDataLoaders\UnionType;

use PoPCMSSchema\CommentMutations\TypeResolvers\UnionType\RootReplyCommentMutationErrorPayloadUnionTypeResolver;
use PoP\ComponentModel\RelationalTypeDataLoaders\UnionType\AbstractUnionTypeDataLoader;
use PoP\ComponentModel\TypeResolvers\UnionType\UnionTypeResolverInterface;
class RootReplyCommentMutationErrorPayloadUnionTypeDataLoader extends AbstractUnionTypeDataLoader
{
    /**
     * @var \PoPCMSSchema\CommentMutations\TypeResolvers\UnionType\RootReplyCommentMutationErrorPayloadUnionTypeResolver|null
     */
    private $rootReplyCommentMutationErrorPayloadUnionTypeResolver;
    /**
     * @param \PoPCMSSchema\CommentMutations\TypeResolvers\UnionType\RootReplyCommentMutationErrorPayloadUnionTypeResolver $rootReplyCommentMutationErrorPayloadUnionTypeResolver
     */
    public final function setRootReplyCommentMutationErrorPayloadUnionTypeResolver($rootReplyCommentMutationErrorPayloadUnionTypeResolver) : void
    {
        $this->rootReplyCommentMutationErrorPayloadUnionTypeResolver = $rootReplyCommentMutationErrorPayloadUnionTypeResolver;
    }
    protected final function getRootReplyCommentMutationErrorPayloadUnionTypeResolver() : RootReplyCommentMutationErrorPayloadUnionTypeResolver
    {
        /** @var RootReplyCommentMutationErrorPayloadUnionTypeResolver */
        return $this->rootReplyCommentMutationErrorPayloadUnionTypeResolver = $this->rootReplyCommentMutationErrorPayloadUnionTypeResolver ?? $this->instanceManager->getInstance(RootReplyCommentMutationErrorPayloadUnionTypeResolver::class);
    }
    protected function getUnionTypeResolver() : UnionTypeResolverInterface
    {
        return $this->getRootReplyCommentMutationErrorPayloadUnionTypeResolver();
    }
}
