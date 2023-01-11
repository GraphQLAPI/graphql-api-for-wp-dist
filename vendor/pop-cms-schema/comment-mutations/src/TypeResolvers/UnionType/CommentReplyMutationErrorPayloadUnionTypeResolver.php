<?php

declare (strict_types=1);
namespace PoPCMSSchema\CommentMutations\TypeResolvers\UnionType;

use PoPCMSSchema\CommentMutations\RelationalTypeDataLoaders\UnionType\CommentReplyMutationErrorPayloadUnionTypeDataLoader;
use PoP\ComponentModel\RelationalTypeDataLoaders\RelationalTypeDataLoaderInterface;
class CommentReplyMutationErrorPayloadUnionTypeResolver extends \PoPCMSSchema\CommentMutations\TypeResolvers\UnionType\AbstractCommentMutationErrorPayloadUnionTypeResolver
{
    /**
     * @var \PoPCMSSchema\CommentMutations\RelationalTypeDataLoaders\UnionType\CommentReplyMutationErrorPayloadUnionTypeDataLoader|null
     */
    private $commentReplyMutationErrorPayloadUnionTypeDataLoader;
    /**
     * @param \PoPCMSSchema\CommentMutations\RelationalTypeDataLoaders\UnionType\CommentReplyMutationErrorPayloadUnionTypeDataLoader $commentReplyMutationErrorPayloadUnionTypeDataLoader
     */
    public final function setCommentReplyMutationErrorPayloadUnionTypeDataLoader($commentReplyMutationErrorPayloadUnionTypeDataLoader) : void
    {
        $this->commentReplyMutationErrorPayloadUnionTypeDataLoader = $commentReplyMutationErrorPayloadUnionTypeDataLoader;
    }
    protected final function getCommentReplyMutationErrorPayloadUnionTypeDataLoader() : CommentReplyMutationErrorPayloadUnionTypeDataLoader
    {
        /** @var CommentReplyMutationErrorPayloadUnionTypeDataLoader */
        return $this->commentReplyMutationErrorPayloadUnionTypeDataLoader = $this->commentReplyMutationErrorPayloadUnionTypeDataLoader ?? $this->instanceManager->getInstance(CommentReplyMutationErrorPayloadUnionTypeDataLoader::class);
    }
    public function getTypeName() : string
    {
        return 'CommentReplyMutationErrorPayloadUnion';
    }
    public function getTypeDescription() : ?string
    {
        return $this->__('Union of \'Error Payload\' types when replying to a comment (using nested mutations)', 'comment-mutations');
    }
    public function getRelationalTypeDataLoader() : RelationalTypeDataLoaderInterface
    {
        return $this->getCommentReplyMutationErrorPayloadUnionTypeDataLoader();
    }
}
