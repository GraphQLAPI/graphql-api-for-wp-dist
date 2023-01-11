<?php

declare (strict_types=1);
namespace PoPCMSSchema\CommentMeta\FieldResolvers\ObjectType;

use PoP\ComponentModel\QueryResolution\FieldDataAccessorInterface;
use PoP\ComponentModel\Feedback\ObjectTypeFieldResolutionFeedbackStore;
use PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface;
use PoPCMSSchema\CommentMeta\TypeAPIs\CommentMetaTypeAPIInterface;
use PoPCMSSchema\Comments\TypeResolvers\ObjectType\CommentObjectTypeResolver;
use PoPCMSSchema\Meta\FieldResolvers\ObjectType\AbstractWithMetaObjectTypeFieldResolver;
use PoPCMSSchema\Meta\TypeAPIs\MetaTypeAPIInterface;
class CommentObjectTypeFieldResolver extends AbstractWithMetaObjectTypeFieldResolver
{
    /**
     * @var \PoPCMSSchema\CommentMeta\TypeAPIs\CommentMetaTypeAPIInterface|null
     */
    private $commentMetaTypeAPI;
    /**
     * @param \PoPCMSSchema\CommentMeta\TypeAPIs\CommentMetaTypeAPIInterface $commentMetaTypeAPI
     */
    public final function setCommentMetaTypeAPI($commentMetaTypeAPI) : void
    {
        $this->commentMetaTypeAPI = $commentMetaTypeAPI;
    }
    protected final function getCommentMetaTypeAPI() : CommentMetaTypeAPIInterface
    {
        /** @var CommentMetaTypeAPIInterface */
        return $this->commentMetaTypeAPI = $this->commentMetaTypeAPI ?? $this->instanceManager->getInstance(CommentMetaTypeAPIInterface::class);
    }
    /**
     * @return array<class-string<ObjectTypeResolverInterface>>
     */
    public function getObjectTypeResolverClassesToAttachTo() : array
    {
        return [CommentObjectTypeResolver::class];
    }
    protected function getMetaTypeAPI() : MetaTypeAPIInterface
    {
        return $this->getCommentMetaTypeAPI();
    }
    /**
     * @return mixed
     * @param object $object
     * @param \PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface $objectTypeResolver
     * @param \PoP\ComponentModel\QueryResolution\FieldDataAccessorInterface $fieldDataAccessor
     * @param \PoP\ComponentModel\Feedback\ObjectTypeFieldResolutionFeedbackStore $objectTypeFieldResolutionFeedbackStore
     */
    public function resolveValue($objectTypeResolver, $object, $fieldDataAccessor, $objectTypeFieldResolutionFeedbackStore)
    {
        $comment = $object;
        switch ($fieldDataAccessor->getFieldName()) {
            case 'metaValue':
            case 'metaValues':
                return $this->getCommentMetaTypeAPI()->getCommentMeta($comment, $fieldDataAccessor->getValue('key'), $fieldDataAccessor->getFieldName() === 'metaValue');
        }
        return parent::resolveValue($objectTypeResolver, $object, $fieldDataAccessor, $objectTypeFieldResolutionFeedbackStore);
    }
}
