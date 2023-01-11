<?php

declare (strict_types=1);
namespace PoPCMSSchema\CommentMutations\TypeResolvers\UnionType;

use PoPCMSSchema\CommentMutations\RelationalTypeDataLoaders\UnionType\RootAddCommentToCustomPostMutationErrorPayloadUnionTypeDataLoader;
use PoP\ComponentModel\RelationalTypeDataLoaders\RelationalTypeDataLoaderInterface;
class RootAddCommentToCustomPostMutationErrorPayloadUnionTypeResolver extends \PoPCMSSchema\CommentMutations\TypeResolvers\UnionType\AbstractCommentMutationErrorPayloadUnionTypeResolver
{
    /**
     * @var \PoPCMSSchema\CommentMutations\RelationalTypeDataLoaders\UnionType\RootAddCommentToCustomPostMutationErrorPayloadUnionTypeDataLoader|null
     */
    private $rootAddCommentToCustomPostMutationErrorPayloadUnionTypeDataLoader;
    /**
     * @param \PoPCMSSchema\CommentMutations\RelationalTypeDataLoaders\UnionType\RootAddCommentToCustomPostMutationErrorPayloadUnionTypeDataLoader $rootAddCommentToCustomPostMutationErrorPayloadUnionTypeDataLoader
     */
    public final function setRootAddCommentToCustomPostMutationErrorPayloadUnionTypeDataLoader($rootAddCommentToCustomPostMutationErrorPayloadUnionTypeDataLoader) : void
    {
        $this->rootAddCommentToCustomPostMutationErrorPayloadUnionTypeDataLoader = $rootAddCommentToCustomPostMutationErrorPayloadUnionTypeDataLoader;
    }
    protected final function getRootAddCommentToCustomPostMutationErrorPayloadUnionTypeDataLoader() : RootAddCommentToCustomPostMutationErrorPayloadUnionTypeDataLoader
    {
        /** @var RootAddCommentToCustomPostMutationErrorPayloadUnionTypeDataLoader */
        return $this->rootAddCommentToCustomPostMutationErrorPayloadUnionTypeDataLoader = $this->rootAddCommentToCustomPostMutationErrorPayloadUnionTypeDataLoader ?? $this->instanceManager->getInstance(RootAddCommentToCustomPostMutationErrorPayloadUnionTypeDataLoader::class);
    }
    public function getTypeName() : string
    {
        return 'RootAddCommentToCustomPostMutationErrorPayloadUnion';
    }
    public function getTypeDescription() : ?string
    {
        return $this->__('Union of \'Error Payload\' types when adding a comment to a custom post', 'comment-mutations');
    }
    public function getRelationalTypeDataLoader() : RelationalTypeDataLoaderInterface
    {
        return $this->getRootAddCommentToCustomPostMutationErrorPayloadUnionTypeDataLoader();
    }
}
