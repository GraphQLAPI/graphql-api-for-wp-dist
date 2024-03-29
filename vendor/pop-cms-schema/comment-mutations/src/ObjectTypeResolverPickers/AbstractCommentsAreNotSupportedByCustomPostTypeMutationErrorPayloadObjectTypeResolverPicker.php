<?php

declare (strict_types=1);
namespace PoPCMSSchema\CommentMutations\ObjectTypeResolverPickers;

use PoPCMSSchema\CommentMutations\ObjectModels\CommentsAreNotSupportedByCustomPostTypeErrorPayload;
use PoPCMSSchema\CommentMutations\TypeResolvers\ObjectType\CommentsAreNotSupportedByCustomPostTypeErrorPayloadObjectTypeResolver;
use PoPSchema\SchemaCommons\ObjectTypeResolverPickers\AbstractErrorPayloadObjectTypeResolverPicker;
use PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface;
abstract class AbstractCommentsAreNotSupportedByCustomPostTypeMutationErrorPayloadObjectTypeResolverPicker extends AbstractErrorPayloadObjectTypeResolverPicker
{
    /**
     * @var \PoPCMSSchema\CommentMutations\TypeResolvers\ObjectType\CommentsAreNotSupportedByCustomPostTypeErrorPayloadObjectTypeResolver|null
     */
    private $commentsAreNotSupportedByCustomPostTypeErrorPayloadObjectTypeResolver;
    /**
     * @param \PoPCMSSchema\CommentMutations\TypeResolvers\ObjectType\CommentsAreNotSupportedByCustomPostTypeErrorPayloadObjectTypeResolver $commentsAreNotSupportedByCustomPostTypeErrorPayloadObjectTypeResolver
     */
    public final function setCommentsAreNotSupportedByCustomPostTypeErrorPayloadObjectTypeResolver($commentsAreNotSupportedByCustomPostTypeErrorPayloadObjectTypeResolver) : void
    {
        $this->commentsAreNotSupportedByCustomPostTypeErrorPayloadObjectTypeResolver = $commentsAreNotSupportedByCustomPostTypeErrorPayloadObjectTypeResolver;
    }
    protected final function getCommentsAreNotSupportedByCustomPostTypeErrorPayloadObjectTypeResolver() : CommentsAreNotSupportedByCustomPostTypeErrorPayloadObjectTypeResolver
    {
        /** @var CommentsAreNotSupportedByCustomPostTypeErrorPayloadObjectTypeResolver */
        return $this->commentsAreNotSupportedByCustomPostTypeErrorPayloadObjectTypeResolver = $this->commentsAreNotSupportedByCustomPostTypeErrorPayloadObjectTypeResolver ?? $this->instanceManager->getInstance(CommentsAreNotSupportedByCustomPostTypeErrorPayloadObjectTypeResolver::class);
    }
    public function getObjectTypeResolver() : ObjectTypeResolverInterface
    {
        return $this->getCommentsAreNotSupportedByCustomPostTypeErrorPayloadObjectTypeResolver();
    }
    protected function getTargetObjectClass() : string
    {
        return CommentsAreNotSupportedByCustomPostTypeErrorPayload::class;
    }
}
