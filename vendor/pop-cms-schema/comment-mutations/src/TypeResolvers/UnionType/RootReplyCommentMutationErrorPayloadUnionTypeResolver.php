<?php

declare (strict_types=1);
namespace PoPCMSSchema\CommentMutations\TypeResolvers\UnionType;

use PoPCMSSchema\CommentMutations\RelationalTypeDataLoaders\UnionType\RootReplyCommentMutationErrorPayloadUnionTypeDataLoader;
use PoP\ComponentModel\RelationalTypeDataLoaders\RelationalTypeDataLoaderInterface;
class RootReplyCommentMutationErrorPayloadUnionTypeResolver extends \PoPCMSSchema\CommentMutations\TypeResolvers\UnionType\AbstractCommentMutationErrorPayloadUnionTypeResolver
{
    /**
     * @var \PoPCMSSchema\CommentMutations\RelationalTypeDataLoaders\UnionType\RootReplyCommentMutationErrorPayloadUnionTypeDataLoader|null
     */
    private $rootReplyCommentMutationErrorPayloadUnionTypeDataLoader;
    /**
     * @param \PoPCMSSchema\CommentMutations\RelationalTypeDataLoaders\UnionType\RootReplyCommentMutationErrorPayloadUnionTypeDataLoader $rootReplyCommentMutationErrorPayloadUnionTypeDataLoader
     */
    public final function setRootReplyCommentMutationErrorPayloadUnionTypeDataLoader($rootReplyCommentMutationErrorPayloadUnionTypeDataLoader) : void
    {
        $this->rootReplyCommentMutationErrorPayloadUnionTypeDataLoader = $rootReplyCommentMutationErrorPayloadUnionTypeDataLoader;
    }
    protected final function getRootReplyCommentMutationErrorPayloadUnionTypeDataLoader() : RootReplyCommentMutationErrorPayloadUnionTypeDataLoader
    {
        /** @var RootReplyCommentMutationErrorPayloadUnionTypeDataLoader */
        return $this->rootReplyCommentMutationErrorPayloadUnionTypeDataLoader = $this->rootReplyCommentMutationErrorPayloadUnionTypeDataLoader ?? $this->instanceManager->getInstance(RootReplyCommentMutationErrorPayloadUnionTypeDataLoader::class);
    }
    public function getTypeName() : string
    {
        return 'RootReplyCommentMutationErrorPayloadUnion';
    }
    public function getTypeDescription() : ?string
    {
        return $this->__('Union of \'Error Payload\' types when replying to a comment', 'comment-mutations');
    }
    public function getRelationalTypeDataLoader() : RelationalTypeDataLoaderInterface
    {
        return $this->getRootReplyCommentMutationErrorPayloadUnionTypeDataLoader();
    }
}
