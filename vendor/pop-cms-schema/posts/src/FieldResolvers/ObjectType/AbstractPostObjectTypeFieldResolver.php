<?php

declare (strict_types=1);
namespace PoPCMSSchema\Posts\FieldResolvers\ObjectType;

use PoP\ComponentModel\TypeResolvers\InputTypeResolverInterface;
use PoP\ComponentModel\QueryResolution\FieldDataAccessorInterface;
use PoP\ComponentModel\Feedback\ObjectTypeFieldResolutionFeedbackStore;
use PoP\ComponentModel\FieldResolvers\ObjectType\AbstractQueryableObjectTypeFieldResolver;
use PoP\ComponentModel\Schema\SchemaTypeModifiers;
use PoP\ComponentModel\TypeResolvers\ConcreteTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\ScalarType\IntScalarTypeResolver;
use PoPCMSSchema\CustomPosts\TypeResolvers\InputObjectType\CustomPostSortInputObjectTypeResolver;
use PoPCMSSchema\Posts\TypeAPIs\PostTypeAPIInterface;
use PoPCMSSchema\Posts\TypeResolvers\InputObjectType\PostPaginationInputObjectTypeResolver;
use PoPCMSSchema\Posts\TypeResolvers\InputObjectType\RootPostsFilterInputObjectTypeResolver;
use PoPCMSSchema\Posts\TypeResolvers\ObjectType\PostObjectTypeResolver;
use PoPSchema\SchemaCommons\Constants\QueryOptions;
use PoPCMSSchema\SchemaCommons\DataLoading\ReturnTypes;
use PoPCMSSchema\SchemaCommons\Resolvers\WithLimitFieldArgResolverTrait;
abstract class AbstractPostObjectTypeFieldResolver extends AbstractQueryableObjectTypeFieldResolver
{
    use WithLimitFieldArgResolverTrait;
    /**
     * @var \PoPCMSSchema\Posts\TypeResolvers\InputObjectType\RootPostsFilterInputObjectTypeResolver|null
     */
    private $rootPostsFilterInputObjectTypeResolver;
    /**
     * @var \PoPCMSSchema\Posts\TypeResolvers\InputObjectType\PostPaginationInputObjectTypeResolver|null
     */
    private $postPaginationInputObjectTypeResolver;
    /**
     * @var \PoPCMSSchema\CustomPosts\TypeResolvers\InputObjectType\CustomPostSortInputObjectTypeResolver|null
     */
    private $customPostSortInputObjectTypeResolver;
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
     * @param \PoPCMSSchema\Posts\TypeResolvers\InputObjectType\RootPostsFilterInputObjectTypeResolver $rootPostsFilterInputObjectTypeResolver
     */
    public final function setRootPostsFilterInputObjectTypeResolver($rootPostsFilterInputObjectTypeResolver) : void
    {
        $this->rootPostsFilterInputObjectTypeResolver = $rootPostsFilterInputObjectTypeResolver;
    }
    protected final function getRootPostsFilterInputObjectTypeResolver() : RootPostsFilterInputObjectTypeResolver
    {
        /** @var RootPostsFilterInputObjectTypeResolver */
        return $this->rootPostsFilterInputObjectTypeResolver = $this->rootPostsFilterInputObjectTypeResolver ?? $this->instanceManager->getInstance(RootPostsFilterInputObjectTypeResolver::class);
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
     * @return string[]
     */
    public function getFieldNamesToResolve() : array
    {
        return ['posts', 'postCount'];
    }
    /**
     * @param \PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface $objectTypeResolver
     * @param string $fieldName
     */
    public function getFieldTypeResolver($objectTypeResolver, $fieldName) : ConcreteTypeResolverInterface
    {
        switch ($fieldName) {
            case 'posts':
                return $this->getPostObjectTypeResolver();
            case 'postCount':
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
            case 'postCount':
                return SchemaTypeModifiers::NON_NULLABLE;
            case 'posts':
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
            case 'posts':
                return $this->__('Posts', 'pop-posts');
            case 'postCount':
                return $this->__('Number of posts', 'pop-posts');
            default:
                return parent::getFieldDescription($objectTypeResolver, $fieldName);
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
            case 'posts':
                return \array_merge($fieldArgNameTypeResolvers, ['filter' => $this->getRootPostsFilterInputObjectTypeResolver(), 'pagination' => $this->getPostPaginationInputObjectTypeResolver(), 'sort' => $this->getCustomPostSortInputObjectTypeResolver()]);
            case 'postCount':
                return \array_merge($fieldArgNameTypeResolvers, ['filter' => $this->getRootPostsFilterInputObjectTypeResolver()]);
            default:
                return $fieldArgNameTypeResolvers;
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
        return [];
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
            case 'posts':
                return $this->getPostTypeAPI()->getPosts($query, [QueryOptions::RETURN_TYPE => ReturnTypes::IDS]);
            case 'postCount':
                return $this->getPostTypeAPI()->getPostCount($query);
        }
        return parent::resolveValue($objectTypeResolver, $object, $fieldDataAccessor, $objectTypeFieldResolutionFeedbackStore);
    }
}
