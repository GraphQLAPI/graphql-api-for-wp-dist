<?php

declare (strict_types=1);
namespace PoPCMSSchema\Comments\FieldResolvers\ObjectType;

use PoPCMSSchema\Comments\ComponentProcessors\FormInputs\FilterInputComponentProcessor;
use PoPCMSSchema\Comments\ComponentProcessors\SingleCommentFilterInputContainerComponentProcessor;
use PoPCMSSchema\Comments\Constants\CommentStatus;
use PoPCMSSchema\Comments\Module;
use PoPCMSSchema\Comments\ModuleConfiguration;
use PoPCMSSchema\Comments\TypeAPIs\CommentTypeAPIInterface;
use PoPCMSSchema\Comments\TypeResolvers\InputObjectType\CommentByInputObjectTypeResolver;
use PoPCMSSchema\Comments\TypeResolvers\InputObjectType\CommentSortInputObjectTypeResolver;
use PoPCMSSchema\Comments\TypeResolvers\InputObjectType\RootCommentPaginationInputObjectTypeResolver;
use PoPCMSSchema\Comments\TypeResolvers\InputObjectType\RootCommentsFilterInputObjectTypeResolver;
use PoPCMSSchema\Comments\TypeResolvers\ObjectType\CommentObjectTypeResolver;
use PoPCMSSchema\CustomPosts\Module as CustomPostsModule;
use PoPCMSSchema\CustomPosts\ModuleConfiguration as CustomPostsModuleConfiguration;
use PoPCMSSchema\SchemaCommons\DataLoading\ReturnTypes;
use PoPCMSSchema\SchemaCommons\Resolvers\WithLimitFieldArgResolverTrait;
use PoPSchema\SchemaCommons\Constants\QueryOptions;
use PoP\ComponentModel\Component\Component;
use PoP\ComponentModel\Feedback\ObjectTypeFieldResolutionFeedbackStore;
use PoP\ComponentModel\FieldResolvers\ObjectType\AbstractQueryableObjectTypeFieldResolver;
use PoP\ComponentModel\FilterInput\FilterInputHelper;
use PoP\ComponentModel\QueryResolution\FieldDataAccessorInterface;
use PoP\ComponentModel\Schema\SchemaTypeModifiers;
use PoP\ComponentModel\TypeResolvers\ConcreteTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\InputTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\ScalarType\IntScalarTypeResolver;
use PoP\Engine\TypeResolvers\ObjectType\RootObjectTypeResolver;
use PoP\Root\App;
class RootObjectTypeFieldResolver extends AbstractQueryableObjectTypeFieldResolver
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
     * @var \PoPCMSSchema\Comments\TypeResolvers\InputObjectType\RootCommentsFilterInputObjectTypeResolver|null
     */
    private $rootCommentsFilterInputObjectTypeResolver;
    /**
     * @var \PoPCMSSchema\Comments\TypeResolvers\InputObjectType\RootCommentPaginationInputObjectTypeResolver|null
     */
    private $rootCommentPaginationInputObjectTypeResolver;
    /**
     * @var \PoPCMSSchema\Comments\TypeResolvers\InputObjectType\CommentSortInputObjectTypeResolver|null
     */
    private $commentSortInputObjectTypeResolver;
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
     * @param \PoPCMSSchema\Comments\TypeResolvers\InputObjectType\RootCommentsFilterInputObjectTypeResolver $rootCommentsFilterInputObjectTypeResolver
     */
    public final function setRootCommentsFilterInputObjectTypeResolver($rootCommentsFilterInputObjectTypeResolver) : void
    {
        $this->rootCommentsFilterInputObjectTypeResolver = $rootCommentsFilterInputObjectTypeResolver;
    }
    protected final function getRootCommentsFilterInputObjectTypeResolver() : RootCommentsFilterInputObjectTypeResolver
    {
        /** @var RootCommentsFilterInputObjectTypeResolver */
        return $this->rootCommentsFilterInputObjectTypeResolver = $this->rootCommentsFilterInputObjectTypeResolver ?? $this->instanceManager->getInstance(RootCommentsFilterInputObjectTypeResolver::class);
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
        return ['comment', 'commentCount', 'comments'];
    }
    /**
     * @param \PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface $objectTypeResolver
     * @param string $fieldName
     */
    public function getFieldTypeResolver($objectTypeResolver, $fieldName) : ConcreteTypeResolverInterface
    {
        switch ($fieldName) {
            case 'commentCount':
                return $this->getIntScalarTypeResolver();
            case 'comment':
            case 'comments':
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
            case 'commentCount':
                return SchemaTypeModifiers::NON_NULLABLE;
            case 'comments':
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
            case 'comment':
                return $this->__('Retrieve a single comment', 'pop-comments');
            case 'commentCount':
                return $this->__('Number of comments on the site', 'pop-comments');
            case 'comments':
                return $this->__('Comments on the site', 'pop-comments');
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
            case 'comment':
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
            case 'comment':
                return \array_merge($fieldArgNameTypeResolvers, ['by' => $this->getCommentByInputObjectTypeResolver()]);
            case 'comments':
                return \array_merge($fieldArgNameTypeResolvers, ['filter' => $this->getRootCommentsFilterInputObjectTypeResolver(), 'pagination' => $this->getRootCommentPaginationInputObjectTypeResolver(), 'sort' => $this->getCommentSortInputObjectTypeResolver()]);
            case 'commentCount':
                return \array_merge($fieldArgNameTypeResolvers, ['filter' => $this->getRootCommentsFilterInputObjectTypeResolver()]);
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
            case ['comment' => 'by']:
                return SchemaTypeModifiers::MANDATORY;
            default:
                return parent::getFieldArgTypeModifiers($objectTypeResolver, $fieldName, $fieldArgName);
        }
    }
    /**
     * @return string[]
     * @param \PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface $objectTypeResolver
     * @param string $fieldName
     */
    public function getSensitiveFieldArgNames($objectTypeResolver, $fieldName) : array
    {
        $sensitiveFieldArgNames = parent::getSensitiveFieldArgNames($objectTypeResolver, $fieldName);
        /** @var ModuleConfiguration */
        $moduleConfiguration = App::getModule(Module::class)->getConfiguration();
        switch ($fieldName) {
            case 'comment':
                if ($moduleConfiguration->treatCommentStatusAsSensitiveData()) {
                    $commentStatusFilterInputName = FilterInputHelper::getFilterInputName(new Component(FilterInputComponentProcessor::class, FilterInputComponentProcessor::COMPONENT_FILTERINPUT_COMMENT_STATUS));
                    $sensitiveFieldArgNames[] = $commentStatusFilterInputName;
                }
                break;
        }
        return $sensitiveFieldArgNames;
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
        $query = $this->convertFieldArgsToFilteringQueryArgs($objectTypeResolver, $fieldDataAccessor);
        /**
         * If "status" is admin and won't be shown, then default to "approve" only
         */
        if (!\array_key_exists('status', $query)) {
            $query['status'] = CommentStatus::APPROVE;
        }
        switch ($fieldDataAccessor->getFieldName()) {
            case 'commentCount':
                return $this->getCommentTypeAPI()->getCommentCount($query);
            case 'comments':
                return $this->getCommentTypeAPI()->getComments($query, [QueryOptions::RETURN_TYPE => ReturnTypes::IDS]);
            case 'comment':
                /**
                 * Only from the mapped CPTs, otherwise we may get an error when
                 * the custom post to which the comment was added, is not accesible
                 * via field `Comment.customPost`:
                 *
                 *   ```
                 *   comments {
                 *     customPost {
                 *       id
                 *     }
                 *   }
                 *   ```
                 */
                /** @var CustomPostsModuleConfiguration */
                $moduleConfiguration = App::getModule(CustomPostsModule::class)->getConfiguration();
                $query['custompost-types'] = $moduleConfiguration->getQueryableCustomPostTypes();
                if ($comments = $this->getCommentTypeAPI()->getComments($query, [QueryOptions::RETURN_TYPE => ReturnTypes::IDS])) {
                    return $comments[0];
                }
                return null;
        }
        return parent::resolveValue($objectTypeResolver, $object, $fieldDataAccessor, $objectTypeFieldResolutionFeedbackStore);
    }
}
