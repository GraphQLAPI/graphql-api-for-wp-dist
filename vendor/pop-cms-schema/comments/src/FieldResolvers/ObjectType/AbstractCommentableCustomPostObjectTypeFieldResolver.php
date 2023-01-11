<?php

declare (strict_types=1);
namespace PoPCMSSchema\Comments\FieldResolvers\ObjectType;

use PoP\ComponentModel\FieldResolvers\InterfaceType\InterfaceTypeFieldResolverInterface;
use PoP\ComponentModel\QueryResolution\FieldDataAccessorInterface;
use PoP\ComponentModel\Feedback\ObjectTypeFieldResolutionFeedbackStore;
use PoP\ComponentModel\FieldResolvers\ObjectType\AbstractQueryableObjectTypeFieldResolver;
use PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface;
use PoP\GraphQLParser\Spec\Parser\Ast\LeafField;
use PoPCMSSchema\Comments\FieldResolvers\InterfaceType\CommentableInterfaceTypeFieldResolver;
use PoPCMSSchema\Comments\TypeAPIs\CommentTypeAPIInterface;
use PoPCMSSchema\SchemaCommons\DataLoading\ReturnTypes;
use PoPSchema\SchemaCommons\Constants\QueryOptions;
abstract class AbstractCommentableCustomPostObjectTypeFieldResolver extends AbstractQueryableObjectTypeFieldResolver
{
    use \PoPCMSSchema\Comments\FieldResolvers\ObjectType\MaybeCommentableCustomPostObjectTypeFieldResolverTrait;
    /**
     * @var \PoPCMSSchema\Comments\TypeAPIs\CommentTypeAPIInterface|null
     */
    private $commentTypeAPI;
    /**
     * @var \PoPCMSSchema\Comments\FieldResolvers\InterfaceType\CommentableInterfaceTypeFieldResolver|null
     */
    private $commentableInterfaceTypeFieldResolver;
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
     * @param \PoPCMSSchema\Comments\FieldResolvers\InterfaceType\CommentableInterfaceTypeFieldResolver $commentableInterfaceTypeFieldResolver
     */
    public final function setCommentableInterfaceTypeFieldResolver($commentableInterfaceTypeFieldResolver) : void
    {
        $this->commentableInterfaceTypeFieldResolver = $commentableInterfaceTypeFieldResolver;
    }
    protected final function getCommentableInterfaceTypeFieldResolver() : CommentableInterfaceTypeFieldResolver
    {
        /** @var CommentableInterfaceTypeFieldResolver */
        return $this->commentableInterfaceTypeFieldResolver = $this->commentableInterfaceTypeFieldResolver ?? $this->instanceManager->getInstance(CommentableInterfaceTypeFieldResolver::class);
    }
    /**
     * @return array<InterfaceTypeFieldResolverInterface>
     */
    public function getImplementedInterfaceTypeFieldResolvers() : array
    {
        return [$this->getCommentableInterfaceTypeFieldResolver()];
    }
    /**
     * @return string[]
     */
    public function getFieldNamesToResolve() : array
    {
        return ['areCommentsOpen', 'hasComments', 'commentCount', 'comments'];
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
        $customPost = $object;
        switch ($fieldDataAccessor->getFieldName()) {
            case 'areCommentsOpen':
                return $this->getCommentTypeAPI()->areCommentsOpen($customPost);
            case 'hasComments':
                return $objectTypeResolver->resolveValue($customPost, new LeafField('commentCount', null, [], [], $fieldDataAccessor->getField()->getLocation()), $objectTypeFieldResolutionFeedbackStore) > 0;
        }
        $query = \array_merge($this->convertFieldArgsToFilteringQueryArgs($objectTypeResolver, $fieldDataAccessor), ['customPostID' => $objectTypeResolver->getID($customPost)]);
        switch ($fieldDataAccessor->getFieldName()) {
            case 'commentCount':
                return $this->getCommentTypeAPI()->getCommentCount($query);
            case 'comments':
                return $this->getCommentTypeAPI()->getComments($query, [QueryOptions::RETURN_TYPE => ReturnTypes::IDS]);
        }
        return parent::resolveValue($objectTypeResolver, $object, $fieldDataAccessor, $objectTypeFieldResolutionFeedbackStore);
    }
}
