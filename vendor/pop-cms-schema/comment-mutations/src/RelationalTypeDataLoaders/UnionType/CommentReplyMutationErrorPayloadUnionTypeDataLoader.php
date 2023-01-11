<?php

declare (strict_types=1);
namespace PoPCMSSchema\CommentMutations\RelationalTypeDataLoaders\UnionType;

use PoPCMSSchema\CommentMutations\TypeResolvers\UnionType\CommentReplyMutationErrorPayloadUnionTypeResolver;
use PoP\ComponentModel\RelationalTypeDataLoaders\UnionType\AbstractUnionTypeDataLoader;
use PoP\ComponentModel\TypeResolvers\UnionType\UnionTypeResolverInterface;
class CommentReplyMutationErrorPayloadUnionTypeDataLoader extends AbstractUnionTypeDataLoader
{
    /**
     * @var \PoPCMSSchema\CommentMutations\TypeResolvers\UnionType\CommentReplyMutationErrorPayloadUnionTypeResolver|null
     */
    private $commentReplyMutationErrorPayloadUnionTypeResolver;
    /**
     * @param \PoPCMSSchema\CommentMutations\TypeResolvers\UnionType\CommentReplyMutationErrorPayloadUnionTypeResolver $commentReplyMutationErrorPayloadUnionTypeResolver
     */
    public final function setCommentReplyMutationErrorPayloadUnionTypeResolver($commentReplyMutationErrorPayloadUnionTypeResolver) : void
    {
        $this->commentReplyMutationErrorPayloadUnionTypeResolver = $commentReplyMutationErrorPayloadUnionTypeResolver;
    }
    protected final function getCommentReplyMutationErrorPayloadUnionTypeResolver() : CommentReplyMutationErrorPayloadUnionTypeResolver
    {
        /** @var CommentReplyMutationErrorPayloadUnionTypeResolver */
        return $this->commentReplyMutationErrorPayloadUnionTypeResolver = $this->commentReplyMutationErrorPayloadUnionTypeResolver ?? $this->instanceManager->getInstance(CommentReplyMutationErrorPayloadUnionTypeResolver::class);
    }
    protected function getUnionTypeResolver() : UnionTypeResolverInterface
    {
        return $this->getCommentReplyMutationErrorPayloadUnionTypeResolver();
    }
}
