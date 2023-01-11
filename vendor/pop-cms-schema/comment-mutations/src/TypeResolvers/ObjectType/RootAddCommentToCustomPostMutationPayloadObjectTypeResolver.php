<?php

declare (strict_types=1);
namespace PoPCMSSchema\CommentMutations\TypeResolvers\ObjectType;

class RootAddCommentToCustomPostMutationPayloadObjectTypeResolver extends \PoPCMSSchema\CommentMutations\TypeResolvers\ObjectType\AbstractCommentMutationPayloadObjectTypeResolver
{
    public function getTypeName() : string
    {
        return 'RootAddCommentToCustomPostMutationPayload';
    }
    public function getTypeDescription() : ?string
    {
        return $this->__('Payload of adding a comment to a custom post', 'comment-mutations');
    }
}
