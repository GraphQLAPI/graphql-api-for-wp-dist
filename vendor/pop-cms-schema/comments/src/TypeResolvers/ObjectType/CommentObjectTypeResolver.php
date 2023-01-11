<?php

declare (strict_types=1);
namespace PoPCMSSchema\Comments\TypeResolvers\ObjectType;

use PoP\ComponentModel\RelationalTypeDataLoaders\RelationalTypeDataLoaderInterface;
use PoP\ComponentModel\TypeResolvers\ObjectType\AbstractObjectTypeResolver;
use PoPCMSSchema\Comments\RelationalTypeDataLoaders\ObjectType\CommentTypeDataLoader;
use PoPCMSSchema\Comments\TypeAPIs\CommentTypeAPIInterface;
class CommentObjectTypeResolver extends AbstractObjectTypeResolver
{
    /**
     * @var \PoPCMSSchema\Comments\TypeAPIs\CommentTypeAPIInterface|null
     */
    private $commentTypeAPI;
    /**
     * @var \PoPCMSSchema\Comments\RelationalTypeDataLoaders\ObjectType\CommentTypeDataLoader|null
     */
    private $commentTypeDataLoader;
    /**
     * @param \PoPCMSSchema\Comments\TypeAPIs\CommentTypeAPIInterface $commentTypeAPI
     */
    public final function setCommentTypeAPI($commentTypeAPI) : void
    {
        $this->commentTypeAPI = $commentTypeAPI;
    }
    protected final function getCommentTypeAPI() : CommentTypeAPIInterface
    {
        /** @var CommentTypeAPIInterface */
        return $this->commentTypeAPI = $this->commentTypeAPI ?? $this->instanceManager->getInstance(CommentTypeAPIInterface::class);
    }
    /**
     * @param \PoPCMSSchema\Comments\RelationalTypeDataLoaders\ObjectType\CommentTypeDataLoader $commentTypeDataLoader
     */
    public final function setCommentTypeDataLoader($commentTypeDataLoader) : void
    {
        $this->commentTypeDataLoader = $commentTypeDataLoader;
    }
    protected final function getCommentTypeDataLoader() : CommentTypeDataLoader
    {
        /** @var CommentTypeDataLoader */
        return $this->commentTypeDataLoader = $this->commentTypeDataLoader ?? $this->instanceManager->getInstance(CommentTypeDataLoader::class);
    }
    public function getTypeName() : string
    {
        return 'Comment';
    }
    public function getTypeDescription() : ?string
    {
        return $this->__('Comments added to custom posts', 'comments');
    }
    /**
     * @return string|int|null
     * @param object $object
     */
    public function getID($object)
    {
        $comment = $object;
        return $this->getCommentTypeAPI()->getCommentID($comment);
    }
    public function getRelationalTypeDataLoader() : RelationalTypeDataLoaderInterface
    {
        return $this->getCommentTypeDataLoader();
    }
}
