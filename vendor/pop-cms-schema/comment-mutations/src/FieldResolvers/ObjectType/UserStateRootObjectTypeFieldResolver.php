<?php

declare (strict_types=1);
namespace PoPCMSSchema\CommentMutations\FieldResolvers\ObjectType;

use PoP\ComponentModel\TypeResolvers\InputTypeResolverInterface;
use PoP\ComponentModel\Checkpoints\CheckpointInterface;
use PoP\ComponentModel\Component\Component;
use PoP\ComponentModel\Feedback\ObjectTypeFieldResolutionFeedbackStore;
use PoP\ComponentModel\FieldResolvers\ObjectType\AbstractQueryableObjectTypeFieldResolver;
use PoP\ComponentModel\QueryResolution\FieldDataAccessorInterface;
use PoP\ComponentModel\Schema\SchemaTypeModifiers;
use PoP\ComponentModel\TypeResolvers\ConcreteTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\ScalarType\IntScalarTypeResolver;
use PoP\Engine\TypeResolvers\ObjectType\RootObjectTypeResolver;
use PoP\Root\App;
use PoPCMSSchema\CommentMutations\TypeResolvers\InputObjectType\RootMyCommentsFilterInputObjectTypeResolver;
use PoPCMSSchema\Comments\ComponentProcessors\SingleCommentFilterInputContainerComponentProcessor;
use PoPCMSSchema\Comments\TypeAPIs\CommentTypeAPIInterface;
use PoPCMSSchema\Comments\TypeResolvers\InputObjectType\CommentByInputObjectTypeResolver;
use PoPCMSSchema\Comments\TypeResolvers\InputObjectType\CommentSortInputObjectTypeResolver;
use PoPCMSSchema\Comments\TypeResolvers\InputObjectType\RootCommentPaginationInputObjectTypeResolver;
use PoPCMSSchema\Comments\TypeResolvers\ObjectType\CommentObjectTypeResolver;
use PoPCMSSchema\SchemaCommons\DataLoading\ReturnTypes;
use PoPCMSSchema\SchemaCommons\Resolvers\WithLimitFieldArgResolverTrait;
use PoPCMSSchema\UserState\Checkpoints\UserLoggedInCheckpoint;
use PoPSchema\SchemaCommons\Constants\QueryOptions;
class UserStateRootObjectTypeFieldResolver extends AbstractQueryableObjectTypeFieldResolver
{
    use WithLimitFieldArgResolverTrait;
    /**
     * @var \PoPCMSSchema\Comments\TypeAPIs\CommentTypeAPIInterface|null
     */
    private $commentTypeAPI;
    /**
     * @var \PoP\ComponentModel\TypeResolvers\ScalarType\IntScalarTypeResolver|null
     */
    private $intScalarTypeResolver;
    /**
     * @var \PoPCMSSchema\Comments\TypeResolvers\ObjectType\CommentObjectTypeResolver|null
     */
    private $commentObjectTypeResolver;
    /**
     * @var \PoPCMSSchema\Comments\TypeResolvers\InputObjectType\CommentByInputObjectTypeResolver|null
     */
    private $commentByInputObjectTypeResolver;
    /**
     * @var \PoPCMSSchema\CommentMutations\TypeResolvers\InputObjectType\RootMyCommentsFilterInputObjectTypeResolver|null
     */
    private $rootMyCommentsFilterInputObjectTypeResolver;
    /**
     * @var \PoPCMSSchema\Comments\TypeResolvers\InputObjectType\RootCommentPaginationInputObjectTypeResolver|null
     */
    private $rootCommentPaginationInputObjectTypeResolver;
    /**
     * @var \PoPCMSSchema\Comments\TypeResolvers\InputObjectType\CommentSortInputObjectTypeResolver|null
     */
    private $commentSortInputObjectTypeResolver;
    /**
     * @var \PoPCMSSchema\UserState\Checkpoints\UserLoggedInCheckpoint|null
     */
    private $userLoggedInCheckpoint;
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
     * @param \PoP\ComponentModel\TypeResolvers\ScalarType\IntScalarTypeResolver $intScalarTypeResolver
     */
    public final function setIntScalarTypeResolver($intScalarTypeResolver) : void
    {
        $this->intScalarTypeResolver = $intScalarTypeResolver;
    }
    protected final function getIntScalarTypeResolver() : IntScalarTypeResolver
    {
        /** @var IntScalarTypeResolver */
        return $this->intScalarTypeResolver = $this->intScalarTypeResolver ?? $this->instanceManager->getInstance(IntScalarTypeResolver::class);
    }
    /**
     * @param \PoPCMSSchema\Comments\TypeResolvers\ObjectType\CommentObjectTypeResolver $commentObjectTypeResolver
     */
    public final function setCommentObjectTypeResolver($commentObjectTypeResolver) : void
    {
        $this->commentObjectTypeResolver = $commentObjectTypeResolver;
    }
    protected final function getCommentObjectTypeResolver() : CommentObjectTypeResolver
    {
        /** @var CommentObjectTypeResolver */
        return $this->commentObjectTypeResolver = $this->commentObjectTypeResolver ?? $this->instanceManager->getInstance(CommentObjectTypeResolver::class);
    }
    /**
     * @param \PoPCMSSchema\Comments\TypeResolvers\InputObjectType\CommentByInputObjectTypeResolver $commentByInputObjectTypeResolver
     */
    public final function setCommentByInputObjectTypeResolver($commentByInputObjectTypeResolver) : void
    {
        $this->commentByInputObjectTypeResolver = $commentByInputObjectTypeResolver;
    }
    protected final function getCommentByInputObjectTypeResolver() : CommentByInputObjectTypeResolver
    {
        /** @var CommentByInputObjectTypeResolver */
        return $this->commentByInputObjectTypeResolver = $this->commentByInputObjectTypeResolver ?? $this->instanceManager->getInstance(CommentByInputObjectTypeResolver::class);
    }
    /**
     * @param \PoPCMSSchema\CommentMutations\TypeResolvers\InputObjectType\RootMyCommentsFilterInputObjectTypeResolver $rootMyCommentsFilterInputObjectTypeResolver
     */
    public final function setRootMyCommentsFilterInputObjectTypeResolver($rootMyCommentsFilterInputObjectTypeResolver) : void
    {
        $this->rootMyCommentsFilterInputObjectTypeResolver = $rootMyCommentsFilterInputObjectTypeResolver;
    }
    protected final function getRootMyCommentsFilterInputObjectTypeResolver() : RootMyCommentsFilterInputObjectTypeResolver
    {
        /** @var RootMyCommentsFilterInputObjectTypeResolver */
        return $this->rootMyCommentsFilterInputObjectTypeResolver = $this->rootMyCommentsFilterInputObjectTypeResolver ?? $this->instanceManager->getInstance(RootMyCommentsFilterInputObjectTypeResolver::class);
    }
    /**
     * @param \PoPCMSSchema\Comments\TypeResolvers\InputObjectType\RootCommentPaginationInputObjectTypeResolver $rootCommentPaginationInputObjectTypeResolver
     */
    public final function setRootCommentPaginationInputObjectTypeResolver($rootCommentPaginationInputObjectTypeResolver) : void
    {
        $this->rootCommentPaginationInputObjectTypeResolver = $rootCommentPaginationInputObjectTypeResolver;
    }
    protected final function getRootCommentPaginationInputObjectTypeResolver() : RootCommentPaginationInputObjectTypeResolver
    {
        /** @var RootCommentPaginationInputObjectTypeResolver */
        return $this->rootCommentPaginationInputObjectTypeResolver = $this->rootCommentPaginationInputObjectTypeResolver ?? $this->instanceManager->getInstance(RootCommentPaginationInputObjectTypeResolver::class);
    }
    /**
     * @param \PoPCMSSchema\Comments\TypeResolvers\InputObjectType\CommentSortInputObjectTypeResolver $commentSortInputObjectTypeResolver
     */
    public final function setCommentSortInputObjectTypeResolver($commentSortInputObjectTypeResolver) : void
    {
        $this->commentSortInputObjectTypeResolver = $commentSortInputObjectTypeResolver;
    }
    protected final function getCommentSortInputObjectTypeResolver() : CommentSortInputObjectTypeResolver
    {
        /** @var CommentSortInputObjectTypeResolver */
        return $this->commentSortInputObjectTypeResolver = $this->commentSortInputObjectTypeResolver ?? $this->instanceManager->getInstance(CommentSortInputObjectTypeResolver::class);
    }
    /**
     * @param \PoPCMSSchema\UserState\Checkpoints\UserLoggedInCheckpoint $userLoggedInCheckpoint
     */
    public final function setUserLoggedInCheckpoint($userLoggedInCheckpoint) : void
    {
        $this->userLoggedInCheckpoint = $userLoggedInCheckpoint;
    }
    protected final function getUserLoggedInCheckpoint() : UserLoggedInCheckpoint
    {
        /** @var UserLoggedInCheckpoint */
        return $this->userLoggedInCheckpoint = $this->userLoggedInCheckpoint ?? $this->instanceManager->getInstance(UserLoggedInCheckpoint::class);
    }
    /**
     * @return array<class-string<ObjectTypeResolverInterface>>
     */
    public function getObjectTypeResolverClassesToAttachTo() : array
    {
        return [RootObjectTypeResolver::class];
    }
    /**
     * @return string[]
     */
    public function getFieldNamesToResolve() : array
    {
        return ['myComment', 'myCommentCount', 'myComments'];
    }
    /**
     * @param \PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface $objectTypeResolver
     * @param string $fieldName
     */
    public function getFieldTypeResolver($objectTypeResolver, $fieldName) : ConcreteTypeResolverInterface
    {
        switch ($fieldName) {
            case 'myCommentCount':
                return $this->getIntScalarTypeResolver();
            case 'myComments':
            case 'myComment':
                return $this->getCommentObjectTypeResolver();
            default:
                return parent::getFieldTypeResolver($objectTypeResolver, $fieldName);
        }
    }
    /**
     * @param \PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface $objectTypeResolver
     * @param string $fieldName
     */
    public function getFieldTypeModifiers($objectTypeResolver, $fieldName) : int
    {
        switch ($fieldName) {
            case 'myCommentCount':
                return SchemaTypeModifiers::NON_NULLABLE;
            case 'myComments':
                return SchemaTypeModifiers::NON_NULLABLE | SchemaTypeModifiers::IS_ARRAY | SchemaTypeModifiers::IS_NON_NULLABLE_ITEMS_IN_ARRAY;
            default:
                return parent::getFieldTypeModifiers($objectTypeResolver, $fieldName);
        }
    }
    /**
     * @param \PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface $objectTypeResolver
     * @param string $fieldName
     */
    public function getFieldDescription($objectTypeResolver, $fieldName) : ?string
    {
        switch ($fieldName) {
            case 'myComment':
                return $this->__('Comment by the logged-in user on the site with a specific ID', 'pop-comments');
            case 'myCommentCount':
                return $this->__('Number of comments by the logged-in user on the site', 'pop-comments');
            case 'myComments':
                return $this->__('Comments by the logged-in user on the site', 'pop-comments');
            default:
                return parent::getFieldDescription($objectTypeResolver, $fieldName);
        }
    }
    /**
     * @param \PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface $objectTypeResolver
     * @param string $fieldName
     */
    public function getFieldFilterInputContainerComponent($objectTypeResolver, $fieldName) : ?Component
    {
        switch ($fieldName) {
            case 'myComment':
                return new Component(SingleCommentFilterInputContainerComponentProcessor::class, SingleCommentFilterInputContainerComponentProcessor::COMPONENT_FILTERINPUTCONTAINER_COMMENT_STATUS);
            default:
                return parent::getFieldFilterInputContainerComponent($objectTypeResolver, $fieldName);
        }
    }
    /**
     * @return array<string,InputTypeResolverInterface>
     * @param \PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface $objectTypeResolver
     * @param string $fieldName
     */
    public function getFieldArgNameTypeResolvers($objectTypeResolver, $fieldName) : array
    {
        $fieldArgNameTypeResolvers = parent::getFieldArgNameTypeResolvers($objectTypeResolver, $fieldName);
        switch ($fieldName) {
            case 'myComment':
                return \array_merge($fieldArgNameTypeResolvers, ['by' => $this->getCommentByInputObjectTypeResolver()]);
            case 'myComments':
                return \array_merge($fieldArgNameTypeResolvers, ['filter' => $this->getRootMyCommentsFilterInputObjectTypeResolver(), 'pagination' => $this->getRootCommentPaginationInputObjectTypeResolver(), 'sort' => $this->getCommentSortInputObjectTypeResolver()]);
            case 'myCommentCount':
                return \array_merge($fieldArgNameTypeResolvers, ['filter' => $this->getRootMyCommentsFilterInputObjectTypeResolver()]);
            default:
                return $fieldArgNameTypeResolvers;
        }
    }
    /**
     * @param \PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface $objectTypeResolver
     * @param string $fieldName
     * @param string $fieldArgName
     */
    public function getFieldArgTypeModifiers($objectTypeResolver, $fieldName, $fieldArgName) : int
    {
        switch ([$fieldName => $fieldArgName]) {
            case ['myComment' => 'by']:
                return SchemaTypeModifiers::MANDATORY;
            default:
                return parent::getFieldArgTypeModifiers($objectTypeResolver, $fieldName, $fieldArgName);
        }
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
        $query = \array_merge($this->convertFieldArgsToFilteringQueryArgs($objectTypeResolver, $fieldDataAccessor), ['authors' => [App::getState('current-user-id')]]);
        switch ($fieldDataAccessor->getFieldName()) {
            case 'myCommentCount':
                return $this->getCommentTypeAPI()->getCommentCount($query);
            case 'myComments':
                return $this->getCommentTypeAPI()->getComments($query, [QueryOptions::RETURN_TYPE => ReturnTypes::IDS]);
            case 'myComment':
                if ($comments = $this->getCommentTypeAPI()->getComments($query, [QueryOptions::RETURN_TYPE => ReturnTypes::IDS])) {
                    return $comments[0];
                }
                return null;
        }
        return parent::resolveValue($objectTypeResolver, $object, $fieldDataAccessor, $objectTypeFieldResolutionFeedbackStore);
    }
    /**
     * @return CheckpointInterface[]
     * @param object $object
     * @param \PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface $objectTypeResolver
     * @param \PoP\ComponentModel\QueryResolution\FieldDataAccessorInterface $fieldDataAccessor
     */
    public function getValidationCheckpoints($objectTypeResolver, $fieldDataAccessor, $object) : array
    {
        $validationCheckpoints = parent::getValidationCheckpoints($objectTypeResolver, $fieldDataAccessor, $object);
        $validationCheckpoints[] = $this->getUserLoggedInCheckpoint();
        return $validationCheckpoints;
    }
}
