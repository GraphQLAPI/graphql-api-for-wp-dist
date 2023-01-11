<?php

declare (strict_types=1);
namespace PoPCMSSchema\CommentMutations\TypeResolvers\ObjectType;

use PoPCMSSchema\CommentMutations\RelationalTypeDataLoaders\ObjectType\CommentAuthorEmailIsMissingErrorPayloadObjectTypeDataLoader;
use PoPSchema\SchemaCommons\TypeResolvers\ObjectType\AbstractErrorPayloadObjectTypeResolver;
use PoP\ComponentModel\RelationalTypeDataLoaders\RelationalTypeDataLoaderInterface;
class CommentAuthorEmailIsMissingErrorPayloadObjectTypeResolver extends AbstractErrorPayloadObjectTypeResolver
{
    /**
     * @var \PoPCMSSchema\CommentMutations\RelationalTypeDataLoaders\ObjectType\CommentAuthorEmailIsMissingErrorPayloadObjectTypeDataLoader|null
     */
    private $commentAuthorEmailIsMissingErrorPayloadObjectTypeDataLoader;
    /**
     * @param \PoPCMSSchema\CommentMutations\RelationalTypeDataLoaders\ObjectType\CommentAuthorEmailIsMissingErrorPayloadObjectTypeDataLoader $commentAuthorEmailIsMissingErrorPayloadObjectTypeDataLoader
     */
    public final function setCommentAuthorEmailIsMissingErrorPayloadObjectTypeDataLoader($commentAuthorEmailIsMissingErrorPayloadObjectTypeDataLoader) : void
    {
        $this->commentAuthorEmailIsMissingErrorPayloadObjectTypeDataLoader = $commentAuthorEmailIsMissingErrorPayloadObjectTypeDataLoader;
    }
    protected final function getCommentAuthorEmailIsMissingErrorPayloadObjectTypeDataLoader() : CommentAuthorEmailIsMissingErrorPayloadObjectTypeDataLoader
    {
        /** @var CommentAuthorEmailIsMissingErrorPayloadObjectTypeDataLoader */
        return $this->commentAuthorEmailIsMissingErrorPayloadObjectTypeDataLoader = $this->commentAuthorEmailIsMissingErrorPayloadObjectTypeDataLoader ?? $this->instanceManager->getInstance(CommentAuthorEmailIsMissingErrorPayloadObjectTypeDataLoader::class);
    }
    public function getTypeName() : string
    {
        return 'CommentAuthorEmailIsMissingErrorPayload';
    }
    public function getTypeDescription() : ?string
    {
        return $this->__('Error payload for: "The comment\'s author email is missing"', 'comment-mutations');
    }
    public function getRelationalTypeDataLoader() : RelationalTypeDataLoaderInterface
    {
        return $this->getCommentAuthorEmailIsMissingErrorPayloadObjectTypeDataLoader();
    }
}
