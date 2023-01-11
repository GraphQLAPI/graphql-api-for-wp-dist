<?php

declare (strict_types=1);
namespace PoPCMSSchema\CommentMutations\TypeResolvers\ObjectType;

use PoPCMSSchema\CommentMutations\RelationalTypeDataLoaders\ObjectType\CommentAuthorNameIsMissingErrorPayloadObjectTypeDataLoader;
use PoPSchema\SchemaCommons\TypeResolvers\ObjectType\AbstractErrorPayloadObjectTypeResolver;
use PoP\ComponentModel\RelationalTypeDataLoaders\RelationalTypeDataLoaderInterface;
class CommentAuthorNameIsMissingErrorPayloadObjectTypeResolver extends AbstractErrorPayloadObjectTypeResolver
{
    /**
     * @var \PoPCMSSchema\CommentMutations\RelationalTypeDataLoaders\ObjectType\CommentAuthorNameIsMissingErrorPayloadObjectTypeDataLoader|null
     */
    private $commentAuthorNameIsMissingErrorPayloadObjectTypeDataLoader;
    /**
     * @param \PoPCMSSchema\CommentMutations\RelationalTypeDataLoaders\ObjectType\CommentAuthorNameIsMissingErrorPayloadObjectTypeDataLoader $commentAuthorNameIsMissingErrorPayloadObjectTypeDataLoader
     */
    public final function setCommentAuthorNameIsMissingErrorPayloadObjectTypeDataLoader($commentAuthorNameIsMissingErrorPayloadObjectTypeDataLoader) : void
    {
        $this->commentAuthorNameIsMissingErrorPayloadObjectTypeDataLoader = $commentAuthorNameIsMissingErrorPayloadObjectTypeDataLoader;
    }
    protected final function getCommentAuthorNameIsMissingErrorPayloadObjectTypeDataLoader() : CommentAuthorNameIsMissingErrorPayloadObjectTypeDataLoader
    {
        /** @var CommentAuthorNameIsMissingErrorPayloadObjectTypeDataLoader */
        return $this->commentAuthorNameIsMissingErrorPayloadObjectTypeDataLoader = $this->commentAuthorNameIsMissingErrorPayloadObjectTypeDataLoader ?? $this->instanceManager->getInstance(CommentAuthorNameIsMissingErrorPayloadObjectTypeDataLoader::class);
    }
    public function getTypeName() : string
    {
        return 'CommentAuthorNameIsMissingErrorPayload';
    }
    public function getTypeDescription() : ?string
    {
        return $this->__('Error payload for: "The comment\'s author name is missing"', 'comment-mutations');
    }
    public function getRelationalTypeDataLoader() : RelationalTypeDataLoaderInterface
    {
        return $this->getCommentAuthorNameIsMissingErrorPayloadObjectTypeDataLoader();
    }
}
