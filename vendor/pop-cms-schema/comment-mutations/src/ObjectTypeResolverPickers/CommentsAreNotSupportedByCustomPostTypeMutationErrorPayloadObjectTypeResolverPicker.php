<?php

declare (strict_types=1);
namespace PoPCMSSchema\CommentMutations\ObjectTypeResolverPickers;

use PoPCMSSchema\CommentMutations\TypeResolvers\UnionType\CustomPostAddCommentMutationErrorPayloadUnionTypeResolver;
use PoPCMSSchema\CommentMutations\TypeResolvers\UnionType\RootAddCommentToCustomPostMutationErrorPayloadUnionTypeResolver;
use PoP\ComponentModel\TypeResolvers\UnionType\UnionTypeResolverInterface;
class CommentsAreNotSupportedByCustomPostTypeMutationErrorPayloadObjectTypeResolverPicker extends \PoPCMSSchema\CommentMutations\ObjectTypeResolverPickers\AbstractCommentsAreNotSupportedByCustomPostTypeMutationErrorPayloadObjectTypeResolverPicker
{
    /**
     * @return array<class-string<UnionTypeResolverInterface>>
     */
    public function getUnionTypeResolverClassesToAttachTo() : array
    {
        return [RootAddCommentToCustomPostMutationErrorPayloadUnionTypeResolver::class, CustomPostAddCommentMutationErrorPayloadUnionTypeResolver::class];
    }
}
