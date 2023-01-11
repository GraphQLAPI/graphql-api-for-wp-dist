<?php

declare (strict_types=1);
namespace PoPCMSSchema\PostMutations\FieldResolvers\ObjectType;

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
use PoPCMSSchema\CustomPosts\ComponentProcessors\CommonCustomPostFilterInputContainerComponentProcessor;
use PoPCMSSchema\CustomPosts\TypeResolvers\InputObjectType\CustomPostSortInputObjectTypeResolver;
use PoPCMSSchema\PostMutations\TypeResolvers\InputObjectType\RootMyPostsFilterInputObjectTypeResolver;
use PoPCMSSchema\Posts\TypeAPIs\PostTypeAPIInterface;
use PoPCMSSchema\Posts\TypeResolvers\InputObjectType\PostByInputObjectTypeResolver;
use PoPCMSSchema\Posts\TypeResolvers\InputObjectType\PostPaginationInputObjectTypeResolver;
use PoPCMSSchema\Posts\TypeResolvers\ObjectType\PostObjectTypeResolver;
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
     * @var \PoPCMSSchema\Posts\TypeResolvers\ObjectType\PostObjectTypeResolver|null
     */
    private $postObjectTypeResolver;
    /**
     * @var \PoPCMSSchema\Posts\TypeAPIs\PostTypeAPIInterface|null
     */
    private $postTypeAPI;
    /**
     * @var \PoPCMSSchema\Posts\TypeResolvers\InputObjectType\PostByInputObjectTypeResolver|null
     */
    private $postByInputObjectTypeResolver;
    /**
     * @var \PoPCMSSchema\PostMutations\TypeResolvers\InputObjectType\RootMyPostsFilterInputObjectTypeResolver|null
     */
    private $rootMyPostsFilterInputObjectTypeResolver;
    /**
     * @var \PoPCMSSchema\Posts\TypeResolvers\InputObjectType\PostPaginationInputObjectTypeResolver|null
     */
    private $postPaginationInputObjectTypeResolver;
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
     * @param \PoPCMSSchema\Posts\TypeResolvers\ObjectType\PostObjectTypeResolver $postObjectTypeResolver
     */
    public final function setPostObjectTypeResolver($postObjectTypeResolver) : void
    {
        $this->postObjectTypeResolver = $postObjectTypeResolver;
    }
    protected final function getPostObjectTypeResolver() : PostObjectTypeResolver
    {
        /** @var PostObjectTypeResolver */
        return $this->postObjectTypeResolver = $this->postObjectTypeResolver ?? $this->instanceManager->getInstance(PostObjectTypeResolver::class);
    }
    /**
     * @param \PoPCMSSchema\Posts\TypeAPIs\PostTypeAPIInterface $postTypeAPI
     */
    public final function setPostTypeAPI($postTypeAPI) : void
    {
        $this->postTypeAPI = $postTypeAPI;
    }
    protected final function getPostTypeAPI() : PostTypeAPIInterface
    {
        /** @var PostTypeAPIInterface */
        return $this->postTypeAPI = $this->postTypeAPI ?? $this->instanceManager->getInstance(PostTypeAPIInterface::class);
    }
    /**
     * @param \PoPCMSSchema\Posts\TypeResolvers\InputObjectType\PostByInputObjectTypeResolver $postByInputObjectTypeResolver
     */
    public final function setPostByInputObjectTypeResolver($postByInputObjectTypeResolver) : void
    {
        $this->postByInputObjectTypeResolver = $postByInputObjectTypeResolver;
    }
    protected final function getPostByInputObjectTypeResolver() : PostByInputObjectTypeResolver
    {
        /** @var PostByInputObjectTypeResolver */
        return $this->postByInputObjectTypeResolver = $this->postByInputObjectTypeResolver ?? $this->instanceManager->getInstance(PostByInputObjectTypeResolver::class);
    }
    /**
     * @param \PoPCMSSchema\PostMutations\TypeResolvers\InputObjectType\RootMyPostsFilterInputObjectTypeResolver $rootMyPostsFilterInputObjectTypeResolver
     */
    public final function setRootMyPostsFilterInputObjectTypeResolver($rootMyPostsFilterInputObjectTypeResolver) : void
    {
        $this->rootMyPostsFilterInputObjectTypeResolver = $rootMyPostsFilterInputObjectTypeResolver;
    }
    protected final function getRootMyPostsFilterInputObjectTypeResolver() : RootMyPostsFilterInputObjectTypeResolver
    {
        /** @var RootMyPostsFilterInputObjectTypeResolver */
        return $this->rootMyPostsFilterInputObjectTypeResolver = $this->rootMyPostsFilterInputObjectTypeResolver ?? $this->instanceManager->getInstance(RootMyPostsFilterInputObjectTypeResolver::class);
    }
    /**
     * @param \PoPCMSSchema\Posts\TypeResolvers\InputObjectType\PostPaginationInputObjectTypeResolver $postPaginationInputObjectTypeResolver
     */
    public final function setPostPaginationInputObjectTypeResolver($postPaginationInputObjectTypeResolver) : void
    {
        $this->postPaginationInputObjectTypeResolver = $postPaginationInputObjectTypeResolver;
    }
    protected final function getPostPaginationInputObjectTypeResolver() : PostPaginationInputObjectTypeResolver
    {
        /** @var PostPaginationInputObjectTypeResolver */
        return $this->postPaginationInputObjectTypeResolver = $this->postPaginationInputObjectTypeResolver ?? $this->instanceManager->getInstance(PostPaginationInputObjectTypeResolver::class);
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
        return ['myPosts', 'myPostCount', 'myPost'];
    }
    /**
     * @param \PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface $objectTypeResolver
     * @param string $fieldName
     */
    public function getFieldTypeResolver($objectTypeResolver, $fieldName) : ConcreteTypeResolverInterface
    {
        switch ($fieldName) {
            case 'myPosts':
            case 'myPost':
                return $this->getPostObjectTypeResolver();
            case 'myPostCount':
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
            case 'myPostCount':
                return SchemaTypeModifiers::NON_NULLABLE;
            case 'myPosts':
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
            case 'myPosts':
                return $this->__('Posts by the logged-in user', 'post-mutations');
            case 'myPostCount':
                return $this->__('Number of posts by the logged-in user', 'post-mutations');
            case 'myPost':
                return $this->__('Retrieve a single post by the logged-in user', 'post-mutations');
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
            case 'myPost':
                return new Component(CommonCustomPostFilterInputContainerComponentProcessor::class, CommonCustomPostFilterInputContainerComponentProcessor::COMPONENT_FILTERINPUTCONTAINER_CUSTOMPOSTSTATUS);
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
            case 'myPost':
                return \array_merge($fieldArgNameTypeResolvers, ['by' => $this->getPostByInputObjectTypeResolver()]);
            case 'myPosts':
                return \array_merge($fieldArgNameTypeResolvers, ['filter' => $this->getRootMyPostsFilterInputObjectTypeResolver(), 'pagination' => $this->getPostPaginationInputObjectTypeResolver(), 'sort' => $this->getCustomPostSortInputObjectTypeResolver()]);
            case 'myPostCount':
                return \array_merge($fieldArgNameTypeResolvers, ['filter' => $this->getRootMyPostsFilterInputObjectTypeResolver()]);
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
            case ['myPost' => 'by']:
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
            case 'myPost':
            case 'myPosts':
            case 'myPostCount':
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
            case 'myPostCount':
                return $this->getPostTypeAPI()->getPostCount($query);
            case 'myPosts':
                return $this->getPostTypeAPI()->getPosts($query, [QueryOptions::RETURN_TYPE => ReturnTypes::IDS]);
            case 'myPost':
                if ($posts = $this->getPostTypeAPI()->getPosts($query, [QueryOptions::RETURN_TYPE => ReturnTypes::IDS])) {
                    return $posts[0];
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
