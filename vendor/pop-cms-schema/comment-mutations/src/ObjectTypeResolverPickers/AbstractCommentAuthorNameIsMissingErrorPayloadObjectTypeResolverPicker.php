<?php

declare (strict_types=1);
namespace PoPCMSSchema\CommentMutations\ObjectTypeResolverPickers;

use PoPCMSSchema\CommentMutations\ObjectModels\CommentAuthorNameIsMissingErrorPayload;
use PoPCMSSchema\CommentMutations\TypeResolvers\ObjectType\CommentAuthorNameIsMissingErrorPayloadObjectTypeResolver;
use PoPSchema\SchemaCommons\ObjectTypeResolverPickers\AbstractErrorPayloadObjectTypeResolverPicker;
use PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface;
abstract class AbstractCommentAuthorNameIsMissingErrorPayloadObjectTypeResolverPicker extends AbstractErrorPayloadObjectTypeResolverPicker
{
    /**
     * @var \PoPCMSSchema\CommentMutations\TypeResolvers\ObjectType\CommentAuthorNameIsMissingErrorPayloadObjectTypeResolver|null
     */
    private $commentAuthorNameIsMissingErrorPayloadObjectTypeResolver;
    /**
     * @param \PoPCMSSchema\CommentMutations\TypeResolvers\ObjectType\CommentAuthorNameIsMissingErrorPayloadObjectTypeResolver $commentAuthorNameIsMissingErrorPayloadObjectTypeResolver
     */
    public final function setCommentAuthorNameIsMissingErrorPayloadObjectTypeResolver($commentAuthorNameIsMissingErrorPayloadObjectTypeResolver) : void
    {
        $this->commentAuthorNameIsMissingErrorPayloadObjectTypeResolver = $commentAuthorNameIsMissingErrorPayloadObjectTypeResolver;
    }
    protected final function getCommentAuthorNameIsMissingErrorPayloadObjectTypeResolver() : CommentAuthorNameIsMissingErrorPayloadObjectTypeResolver
    {
        /** @var CommentAuthorNameIsMissingErrorPayloadObjectTypeResolver */
        return $this->commentAuthorNameIsMissingErrorPayloadObjectTypeResolver = $this->commentAuthorNameIsMissingErrorPayloadObjectTypeResolver ?? $this->instanceManager->getInstance(CommentAuthorNameIsMissingErrorPayloadObjectTypeResolver::class);
    }
    public function getObjectTypeResolver() : ObjectTypeResolverInterface
    {
        return $this->getCommentAuthorNameIsMissingErrorPayloadObjectTypeResolver();
    }
    protected function getTargetObjectClass() : string
    {
        return CommentAuthorNameIsMissingErrorPayload::class;
    }
}
