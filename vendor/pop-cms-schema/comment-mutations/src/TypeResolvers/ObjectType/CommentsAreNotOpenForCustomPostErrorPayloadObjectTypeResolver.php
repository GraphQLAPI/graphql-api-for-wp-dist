<?php

declare (strict_types=1);
namespace PoPCMSSchema\CommentMutations\TypeResolvers\ObjectType;

use PoPCMSSchema\CommentMutations\RelationalTypeDataLoaders\ObjectType\CommentsAreNotOpenForCustomPostErrorPayloadObjectTypeDataLoader;
use PoPSchema\SchemaCommons\TypeResolvers\ObjectType\AbstractErrorPayloadObjectTypeResolver;
use PoP\ComponentModel\RelationalTypeDataLoaders\RelationalTypeDataLoaderInterface;
class CommentsAreNotOpenForCustomPostErrorPayloadObjectTypeResolver extends AbstractErrorPayloadObjectTypeResolver
{
    /**
     * @var \PoPCMSSchema\CommentMutations\RelationalTypeDataLoaders\ObjectType\CommentsAreNotOpenForCustomPostErrorPayloadObjectTypeDataLoader|null
     */
    private $commentParentCommentDoesNotExistErrorPayloadObjectTypeDataLoader;
    /**
     * @param \PoPCMSSchema\CommentMutations\RelationalTypeDataLoaders\ObjectType\CommentsAreNotOpenForCustomPostErrorPayloadObjectTypeDataLoader $commentParentCommentDoesNotExistErrorPayloadObjectTypeDataLoader
     */
    public final function setCommentsAreNotOpenForCustomPostErrorPayloadObjectTypeDataLoader($commentParentCommentDoesNotExistErrorPayloadObjectTypeDataLoader) : void
    {
        $this->commentParentCommentDoesNotExistErrorPayloadObjectTypeDataLoader = $commentParentCommentDoesNotExistErrorPayloadObjectTypeDataLoader;
    }
    protected final function getCommentsAreNotOpenForCustomPostErrorPayloadObjectTypeDataLoader() : CommentsAreNotOpenForCustomPostErrorPayloadObjectTypeDataLoader
    {
        /** @var CommentsAreNotOpenForCustomPostErrorPayloadObjectTypeDataLoader */
        return $this->commentParentCommentDoesNotExistErrorPayloadObjectTypeDataLoader = $this->commentParentCommentDoesNotExistErrorPayloadObjectTypeDataLoader ?? $this->instanceManager->getInstance(CommentsAreNotOpenForCustomPostErrorPayloadObjectTypeDataLoader::class);
    }
    public function getTypeName() : string
    {
        return 'CommentsAreNotOpenForCustomPostErrorPayload';
    }
    public function getTypeDescription() : ?string
    {
        return $this->__('Error payload for: "Comments are not open for the custom post"', 'comment-mutations');
    }
    public function getRelationalTypeDataLoader() : RelationalTypeDataLoaderInterface
    {
        return $this->getCommentsAreNotOpenForCustomPostErrorPayloadObjectTypeDataLoader();
    }
}
