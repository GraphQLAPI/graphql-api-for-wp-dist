<?php

declare (strict_types=1);
namespace PoPCMSSchema\CustomPostMutations\FieldResolvers\ObjectType;

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
use PoPCMSSchema\CustomPostMutations\TypeResolvers\InputObjectType\RootMyCustomPostsFilterInputObjectTypeResolver;
use PoPCMSSchema\CustomPosts\ComponentProcessors\CommonCustomPostFilterInputContainerComponentProcessor;
use PoPCMSSchema\CustomPosts\TypeAPIs\CustomPostTypeAPIInterface;
use PoPCMSSchema\CustomPosts\TypeHelpers\CustomPostUnionTypeHelpers;
use PoPCMSSchema\CustomPosts\TypeResolvers\InputObjectType\CustomPostByInputObjectTypeResolver;
use PoPCMSSchema\CustomPosts\TypeResolvers\InputObjectType\CustomPostPaginationInputObjectTypeResolver;
use PoPCMSSchema\CustomPosts\TypeResolvers\InputObjectType\CustomPostSortInputObjectTypeResolver;
use PoPCMSSchema\SchemaCommons\DataLoading\ReturnTypes;
use PoPCMSSchema\SchemaCommons\Resolvers\WithLimitFieldArgResolverTrait;
use PoPCMSSchema\UserState\Checkpoints\UserLoggedInCheckpoint;
use PoPSchema\SchemaCommons\Constants\QueryOptions;
class RootQueryableObjectTypeFieldResolver extends AbstractQueryableObjectTypeFieldResolver
{
    use WithLimitFieldArgResolverTrait;
    /**
     * @var \PoP\ComponentModel\TypeResolvers\ScalarType\IntScalarTypeResolver|null
     */
    private $intScalarTypeResolver;
    /**
     * @var \PoPCMSSchema\CustomPosts\TypeAPIs\CustomPostTypeAPIInterface|null
     */
    private $customPostTypeAPI;
    /**
     * @var \PoPCMSSchema\CustomPosts\TypeResolvers\InputObjectType\CustomPostByInputObjectTypeResolver|null
     */
    private $customPostByInputObjectTypeResolver;
    /**
     * @var \PoPCMSSchema\CustomPostMutations\TypeResolvers\InputObjectType\RootMyCustomPostsFilterInputObjectTypeResolver|null
     */
    private $rootMyCustomPostsFilterInputObjectTypeResolver;
    /**
     * @var \PoPCMSSchema\CustomPosts\TypeResolvers\InputObjectType\CustomPostPaginationInputObjectTypeResolver|null
     */
    private $customPostPaginationInputObjectTypeResolver;
    /**
     * @var \PoPCMSSchema\CustomPosts\TypeResolvers\InputObjectType\CustomPostSortInputObjectTypeResolver|null
     */
    private $customPostSortInputObjectTypeResolver;
    /**
     * @var \PoPCMSSchema\UserState\Checkpoints\UserLoggedInCheckpoint|null
     */
    private $userLoggedInCheckpoint;
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
     * @param \PoPCMSSchema\CustomPosts\TypeAPIs\CustomPostTypeAPIInterface $customPostTypeAPI
     */
    public final function setCustomPostTypeAPI($customPostTypeAPI) : void
    {
        $this->customPostTypeAPI = $customPostTypeAPI;
    }
    protected final function getCustomPostTypeAPI() : CustomPostTypeAPIInterface
    {
        /** @var CustomPostTypeAPIInterface */
        return $this->customPostTypeAPI = $this->customPostTypeAPI ?? $this->instanceManager->getInstance(CustomPostTypeAPIInterface::class);
    }
    /**
     * @param \PoPCMSSchema\CustomPosts\TypeResolvers\InputObjectType\CustomPostByInputObjectTypeResolver $customPostByInputObjectTypeResolver
     */
    public final function setCustomPostByInputObjectTypeResolver($customPostByInputObjectTypeResolver) : void
    {
        $this->customPostByInputObjectTypeResolver = $customPostByInputObjectTypeResolver;
    }
    protected final function getCustomPostByInputObjectTypeResolver() : CustomPostByInputObjectTypeResolver
    {
        /** @var CustomPostByInputObjectTypeResolver */
        return $this->customPostByInputObjectTypeResolver = $this->customPostByInputObjectTypeResolver ?? $this->instanceManager->getInstance(CustomPostByInputObjectTypeResolver::class);
    }
    /**
     * @param \PoPCMSSchema\CustomPostMutations\TypeResolvers\InputObjectType\RootMyCustomPostsFilterInputObjectTypeResolver $rootMyCustomPostsFilterInputObjectTypeResolver
     */
    public final function setRootMyCustomPostsFilterInputObjectTypeResolver($rootMyCustomPostsFilterInputObjectTypeResolver) : void
    {
        $this->rootMyCustomPostsFilterInputObjectTypeResolver = $rootMyCustomPostsFilterInputObjectTypeResolver;
    }
    protected final function getRootMyCustomPostsFilterInputObjectTypeResolver() : RootMyCustomPostsFilterInputObjectTypeResolver
    {
        /** @var RootMyCustomPostsFilterInputObjectTypeResolver */
        return $this->rootMyCustomPostsFilterInputObjectTypeResolver = $this->rootMyCustomPostsFilterInputObjectTypeResolver ?? $this->instanceManager->getInstance(RootMyCustomPostsFilterInputObjectTypeResolver::class);
    }
    /**
     * @param \PoPCMSSchema\CustomPosts\TypeResolvers\InputObjectType\CustomPostPaginationInputObjectTypeResolver $customPostPaginationInputObjectTypeResolver
     */
    public final function setCustomPostPaginationInputObjectTypeResolver($customPostPaginationInputObjectTypeResolver) : void
    {
        $this->customPostPaginationInputObjectTypeResolver = $customPostPaginationInputObjectTypeResolver;
    }
    protected final function getCustomPostPaginationInputObjectTypeResolver() : CustomPostPaginationInputObjectTypeResolver
    {
        /** @var CustomPostPaginationInputObjectTypeResolver */
        return $this->customPostPaginationInputObjectTypeResolver = $this->customPostPaginationInputObjectTypeResolver ?? $this->instanceManager->getInstance(CustomPostPaginationInputObjectTypeResolver::class);
    }
    /**
     * @param \PoPCMSSchema\CustomPosts\TypeResolvers\InputObjectType\CustomPostSortInputObjectTypeResolver $customPostSortInputObjectTypeResolver
     */
    public final function setCustomPostSortInputObjectTypeResolver($customPostSortInputObjectTypeResolver) : void
    {
        $this->customPostSortInputObjectTypeResolver = $customPostSortInputObjectTypeResolver;
    }
    protected final function getCustomPostSortInputObjectTypeResolver() : CustomPostSortInputObjectTypeResolver
    {
        /** @var CustomPostSortInputObjectTypeResolver */
        return $this->customPostSortInputObjectTypeResolver = $this->customPostSortInputObjectTypeResolver ?? $this->instanceManager->getInstance(CustomPostSortInputObjectTypeResolver::class);
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
        return ['myCustomPosts', 'myCustomPostCount', 'myCustomPost'];
    }
    /**
     * @param \PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface $objectTypeResolver
     * @param string $fieldName
     */
    public function getFieldTypeResolver($objectTypeResolver, $fieldName) : ConcreteTypeResolverInterface
    {
        switch ($fieldName) {
            case 'myCustomPosts':
                return CustomPostUnionTypeHelpers::getCustomPostUnionOrTargetObjectTypeResolver();
            case 'myCustomPost':
                return CustomPostUnionTypeHelpers::getCustomPostUnionOrTargetObjectTypeResolver();
            case 'myCustomPostCount':
                return $this->getIntScalarTypeResolver();
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
            case 'myCustomPostCount':
                return SchemaTypeModifiers::NON_NULLABLE;
            case 'myCustomPosts':
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
            case 'myCustomPosts':
                return $this->__('Custom posts by the logged-in user', 'custompost-mutations');
            case 'myCustomPostCount':
                return $this->__('Number of custom posts by the logged-in user', 'custompost-mutations');
            case 'myCustomPost':
                return $this->__('Retrieve a single custom post', 'custompost-mutations');
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
            case 'myCustomPost':
                return new Component(CommonCustomPostFilterInputContainerComponentProcessor::class, CommonCustomPostFilterInputContainerComponentProcessor::COMPONENT_FILTERINPUTCONTAINER_CUSTOMPOST_BY_STATUS_UNIONTYPE);
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
            case 'myCustomPost':
                return \array_merge($fieldArgNameTypeResolvers, ['by' => $this->getCustomPostByInputObjectTypeResolver()]);
            case 'myCustomPosts':
                return \array_merge($fieldArgNameTypeResolvers, ['filter' => $this->getRootMyCustomPostsFilterInputObjectTypeResolver(), 'pagination' => $this->getCustomPostPaginationInputObjectTypeResolver(), 'sort' => $this->getCustomPostSortInputObjectTypeResolver()]);
            case 'myCustomPostCount':
                return \array_merge($fieldArgNameTypeResolvers, ['filter' => $this->getRootMyCustomPostsFilterInputObjectTypeResolver()]);
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
            case ['myCustomPost' => 'by']:
                return SchemaTypeModifiers::MANDATORY;
            default:
                return parent::getFieldArgTypeModifiers($objectTypeResolver, $fieldName, $fieldArgName);
        }
    }
    /**
     * @return array<string,mixed>
     * @param object $object
     * @param \PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface $objectTypeResolver
     * @param \PoP\ComponentModel\QueryResolution\FieldDataAccessorInterface $fieldDataAccessor
     */
    protected function getQuery($objectTypeResolver, $object, $fieldDataAccessor) : array
    {
        switch ($fieldDataAccessor->getFieldName()) {
            case 'myCustomPost':
            case 'myCustomPosts':
            case 'myCustomPostCount':
                return ['authors' => [App::getState('current-user-id')]];
            default:
                return [];
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
        $query = \array_merge($this->convertFieldArgsToFilteringQueryArgs($objectTypeResolver, $fieldDataAccessor), $this->getQuery($objectTypeResolver, $object, $fieldDataAccessor));
        switch ($fieldDataAccessor->getFieldName()) {
            case 'myCustomPostCount':
                return $this->getCustomPostTypeAPI()->getCustomPostCount($query);
            case 'myCustomPosts':
                return $this->getCustomPostTypeAPI()->getCustomPosts($query, [QueryOptions::RETURN_TYPE => ReturnTypes::IDS]);
            case 'myCustomPost':
                if ($customPosts = $this->getCustomPostTypeAPI()->getCustomPosts($query, [QueryOptions::RETURN_TYPE => ReturnTypes::IDS])) {
                    return $customPosts[0];
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
