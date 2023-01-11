<?php

declare (strict_types=1);
namespace PoPCMSSchema\CommentMutations\TypeResolvers\UnionType;

use PoPCMSSchema\CommentMutations\RelationalTypeDataLoaders\UnionType\CustomPostAddCommentMutationErrorPayloadUnionTypeDataLoader;
use PoP\ComponentModel\RelationalTypeDataLoaders\RelationalTypeDataLoaderInterface;
class CustomPostAddCommentMutationErrorPayloadUnionTypeResolver extends \PoPCMSSchema\CommentMutations\TypeResolvers\UnionType\AbstractCommentMutationErrorPayloadUnionTypeResolver
{
    /**
     * @var \PoPCMSSchema\CommentMutations\RelationalTypeDataLoaders\UnionType\CustomPostAddCommentMutationErrorPayloadUnionTypeDataLoader|null
     */
    private $customPostAddCommentMutationErrorPayloadUnionTypeDataLoader;
    /**
     * @param \PoPCMSSchema\CommentMutations\RelationalTypeDataLoaders\UnionType\CustomPostAddCommentMutationErrorPayloadUnionTypeDataLoader $customPostAddCommentMutationErrorPayloadUnionTypeDataLoader
     */
    public final function setCustomPostAddCommentMutationErrorPayloadUnionTypeDataLoader($customPostAddCommentMutationErrorPayloadUnionTypeDataLoader) : void
    {
        $this->customPostAddCommentMutationErrorPayloadUnionTypeDataLoader = $customPostAddCommentMutationErrorPayloadUnionTypeDataLoader;
    }
    protected final function getCustomPostAddCommentMutationErrorPayloadUnionTypeDataLoader() : CustomPostAddCommentMutationErrorPayloadUnionTypeDataLoader
    {
        /** @var CustomPostAddCommentMutationErrorPayloadUnionTypeDataLoader */
        return $this->customPostAddCommentMutationErrorPayloadUnionTypeDataLoader = $this->customPostAddCommentMutationErrorPayloadUnionTypeDataLoader ?? $this->instanceManager->getInstance(CustomPostAddCommentMutationErrorPayloadUnionTypeDataLoader::class);
    }
    public function getTypeName() : string
    {
        return 'CustomPostAddCommentMutationErrorPayloadUnion';
    }
    public function getTypeDescription() : ?string
    {
        return $this->__('Union of \'Error Payload\' types when adding a comment to a custom post (using nested mutations)', 'comment-mutations');
    }
    public function getRelationalTypeDataLoader() : RelationalTypeDataLoaderInterface
    {
        return $this->getCustomPostAddCommentMutationErrorPayloadUnionTypeDataLoader();
    }
}
