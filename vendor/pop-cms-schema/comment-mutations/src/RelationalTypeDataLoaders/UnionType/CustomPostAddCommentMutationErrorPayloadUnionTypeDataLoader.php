<?php

declare (strict_types=1);
namespace PoPCMSSchema\CommentMutations\RelationalTypeDataLoaders\UnionType;

use PoPCMSSchema\CommentMutations\TypeResolvers\UnionType\CustomPostAddCommentMutationErrorPayloadUnionTypeResolver;
use PoP\ComponentModel\RelationalTypeDataLoaders\UnionType\AbstractUnionTypeDataLoader;
use PoP\ComponentModel\TypeResolvers\UnionType\UnionTypeResolverInterface;
class CustomPostAddCommentMutationErrorPayloadUnionTypeDataLoader extends AbstractUnionTypeDataLoader
{
    /**
     * @var \PoPCMSSchema\CommentMutations\TypeResolvers\UnionType\CustomPostAddCommentMutationErrorPayloadUnionTypeResolver|null
     */
    private $customPostAddCommentMutationErrorPayloadUnionTypeResolver;
    /**
     * @param \PoPCMSSchema\CommentMutations\TypeResolvers\UnionType\CustomPostAddCommentMutationErrorPayloadUnionTypeResolver $customPostAddCommentMutationErrorPayloadUnionTypeResolver
     */
    public final function setCustomPostAddCommentMutationErrorPayloadUnionTypeResolver($customPostAddCommentMutationErrorPayloadUnionTypeResolver) : void
    {
        $this->customPostAddCommentMutationErrorPayloadUnionTypeResolver = $customPostAddCommentMutationErrorPayloadUnionTypeResolver;
    }
    protected final function getCustomPostAddCommentMutationErrorPayloadUnionTypeResolver() : CustomPostAddCommentMutationErrorPayloadUnionTypeResolver
    {
        /** @var CustomPostAddCommentMutationErrorPayloadUnionTypeResolver */
        return $this->customPostAddCommentMutationErrorPayloadUnionTypeResolver = $this->customPostAddCommentMutationErrorPayloadUnionTypeResolver ?? $this->instanceManager->getInstance(CustomPostAddCommentMutationErrorPayloadUnionTypeResolver::class);
    }
    protected function getUnionTypeResolver() : UnionTypeResolverInterface
    {
        return $this->getCustomPostAddCommentMutationErrorPayloadUnionTypeResolver();
    }
}
