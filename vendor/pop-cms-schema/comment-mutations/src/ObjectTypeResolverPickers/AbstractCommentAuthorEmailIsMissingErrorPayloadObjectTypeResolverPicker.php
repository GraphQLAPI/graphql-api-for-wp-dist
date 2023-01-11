<?php

declare (strict_types=1);
namespace PoPCMSSchema\CommentMutations\ObjectTypeResolverPickers;

use PoPCMSSchema\CommentMutations\ObjectModels\CommentAuthorEmailIsMissingErrorPayload;
use PoPCMSSchema\CommentMutations\TypeResolvers\ObjectType\CommentAuthorEmailIsMissingErrorPayloadObjectTypeResolver;
use PoPSchema\SchemaCommons\ObjectTypeResolverPickers\AbstractErrorPayloadObjectTypeResolverPicker;
use PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface;
abstract class AbstractCommentAuthorEmailIsMissingErrorPayloadObjectTypeResolverPicker extends AbstractErrorPayloadObjectTypeResolverPicker
{
    /**
     * @var \PoPCMSSchema\CommentMutations\TypeResolvers\ObjectType\CommentAuthorEmailIsMissingErrorPayloadObjectTypeResolver|null
     */
    private $commentAuthorEmailIsMissingErrorPayloadObjectTypeResolver;
    /**
     * @param \PoPCMSSchema\CommentMutations\TypeResolvers\ObjectType\CommentAuthorEmailIsMissingErrorPayloadObjectTypeResolver $commentAuthorEmailIsMissingErrorPayloadObjectTypeResolver
     */
    public final function setCommentAuthorEmailIsMissingErrorPayloadObjectTypeResolver($commentAuthorEmailIsMissingErrorPayloadObjectTypeResolver) : void
    {
        $this->commentAuthorEmailIsMissingErrorPayloadObjectTypeResolver = $commentAuthorEmailIsMissingErrorPayloadObjectTypeResolver;
    }
    protected final function getCommentAuthorEmailIsMissingErrorPayloadObjectTypeResolver() : CommentAuthorEmailIsMissingErrorPayloadObjectTypeResolver
    {
        /** @var CommentAuthorEmailIsMissingErrorPayloadObjectTypeResolver */
        return $this->commentAuthorEmailIsMissingErrorPayloadObjectTypeResolver = $this->commentAuthorEmailIsMissingErrorPayloadObjectTypeResolver ?? $this->instanceManager->getInstance(CommentAuthorEmailIsMissingErrorPayloadObjectTypeResolver::class);
    }
    public function getObjectTypeResolver() : ObjectTypeResolverInterface
    {
        return $this->getCommentAuthorEmailIsMissingErrorPayloadObjectTypeResolver();
    }
    protected function getTargetObjectClass() : string
    {
        return CommentAuthorEmailIsMissingErrorPayload::class;
    }
}
