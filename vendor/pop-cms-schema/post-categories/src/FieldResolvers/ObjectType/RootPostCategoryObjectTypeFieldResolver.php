<?php

declare (strict_types=1);
namespace PoPCMSSchema\PostCategories\FieldResolvers\ObjectType;

use PoP\ComponentModel\TypeResolvers\InputTypeResolverInterface;
use PoP\ComponentModel\QueryResolution\FieldDataAccessorInterface;
use PoP\ComponentModel\Feedback\ObjectTypeFieldResolutionFeedbackStore;
use PoP\ComponentModel\FieldResolvers\ObjectType\AbstractQueryableObjectTypeFieldResolver;
use PoP\ComponentModel\Schema\SchemaTypeModifiers;
use PoP\ComponentModel\TypeResolvers\ConcreteTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface;
use PoP\Engine\TypeResolvers\ObjectType\RootObjectTypeResolver;
use PoP\ComponentModel\TypeResolvers\ScalarType\IntScalarTypeResolver;
use PoP\ComponentModel\TypeResolvers\ScalarType\StringScalarTypeResolver;
use PoPCMSSchema\Categories\TypeResolvers\InputObjectType\CategoryPaginationInputObjectTypeResolver;
use PoPCMSSchema\Categories\TypeResolvers\InputObjectType\RootCategoriesFilterInputObjectTypeResolver;
use PoPCMSSchema\PostCategories\TypeAPIs\PostCategoryTypeAPIInterface;
use PoPCMSSchema\PostCategories\TypeResolvers\InputObjectType\PostCategoryByInputObjectTypeResolver;
use PoPCMSSchema\PostCategories\TypeResolvers\ObjectType\PostCategoryObjectTypeResolver;
use PoPSchema\SchemaCommons\Constants\QueryOptions;
use PoPCMSSchema\SchemaCommons\DataLoading\ReturnTypes;
use PoPCMSSchema\SchemaCommons\Resolvers\WithLimitFieldArgResolverTrait;
use PoPCMSSchema\Taxonomies\TypeResolvers\InputObjectType\TaxonomySortInputObjectTypeResolver;
class RootPostCategoryObjectTypeFieldResolver extends AbstractQueryableObjectTypeFieldResolver
{
    use WithLimitFieldArgResolverTrait;
    /**
     * @var \PoP\ComponentModel\TypeResolvers\ScalarType\IntScalarTypeResolver|null
     */
    private $intScalarTypeResolver;
    /**
     * @var \PoP\ComponentModel\TypeResolvers\ScalarType\StringScalarTypeResolver|null
     */
    private $stringScalarTypeResolver;
    /**
     * @var \PoPCMSSchema\PostCategories\TypeResolvers\ObjectType\PostCategoryObjectTypeResolver|null
     */
    private $postCategoryObjectTypeResolver;
    /**
     * @var \PoPCMSSchema\PostCategories\TypeAPIs\PostCategoryTypeAPIInterface|null
     */
    private $postCategoryTypeAPI;
    /**
     * @var \PoPCMSSchema\PostCategories\TypeResolvers\InputObjectType\PostCategoryByInputObjectTypeResolver|null
     */
    private $postCategoryByInputObjectTypeResolver;
    /**
     * @var \PoPCMSSchema\Categories\TypeResolvers\InputObjectType\RootCategoriesFilterInputObjectTypeResolver|null
     */
    private $rootCategoriesFilterInputObjectTypeResolver;
    /**
     * @var \PoPCMSSchema\Categories\TypeResolvers\InputObjectType\CategoryPaginationInputObjectTypeResolver|null
     */
    private $categoryPaginationInputObjectTypeResolver;
    /**
     * @var \PoPCMSSchema\Taxonomies\TypeResolvers\InputObjectType\TaxonomySortInputObjectTypeResolver|null
     */
    private $taxonomySortInputObjectTypeResolver;
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
     * @param \PoP\ComponentModel\TypeResolvers\ScalarType\StringScalarTypeResolver $stringScalarTypeResolver
     */
    public final function setStringScalarTypeResolver($stringScalarTypeResolver) : void
    {
        $this->stringScalarTypeResolver = $stringScalarTypeResolver;
    }
    protected final function getStringScalarTypeResolver() : StringScalarTypeResolver
    {
        /** @var StringScalarTypeResolver */
        return $this->stringScalarTypeResolver = $this->stringScalarTypeResolver ?? $this->instanceManager->getInstance(StringScalarTypeResolver::class);
    }
    /**
     * @param \PoPCMSSchema\PostCategories\TypeResolvers\ObjectType\PostCategoryObjectTypeResolver $postCategoryObjectTypeResolver
     */
    public final function setPostCategoryObjectTypeResolver($postCategoryObjectTypeResolver) : void
    {
        $this->postCategoryObjectTypeResolver = $postCategoryObjectTypeResolver;
    }
    protected final function getPostCategoryObjectTypeResolver() : PostCategoryObjectTypeResolver
    {
        /** @var PostCategoryObjectTypeResolver */
        return $this->postCategoryObjectTypeResolver = $this->postCategoryObjectTypeResolver ?? $this->instanceManager->getInstance(PostCategoryObjectTypeResolver::class);
    }
    /**
     * @param \PoPCMSSchema\PostCategories\TypeAPIs\PostCategoryTypeAPIInterface $postCategoryTypeAPI
     */
    public final function setPostCategoryTypeAPI($postCategoryTypeAPI) : void
    {
        $this->postCategoryTypeAPI = $postCategoryTypeAPI;
    }
    protected final function getPostCategoryTypeAPI() : PostCategoryTypeAPIInterface
    {
        /** @var PostCategoryTypeAPIInterface */
        return $this->postCategoryTypeAPI = $this->postCategoryTypeAPI ?? $this->instanceManager->getInstance(PostCategoryTypeAPIInterface::class);
    }
    /**
     * @param \PoPCMSSchema\PostCategories\TypeResolvers\InputObjectType\PostCategoryByInputObjectTypeResolver $postCategoryByInputObjectTypeResolver
     */
    public final function setPostCategoryByInputObjectTypeResolver($postCategoryByInputObjectTypeResolver) : void
    {
        $this->postCategoryByInputObjectTypeResolver = $postCategoryByInputObjectTypeResolver;
    }
    protected final function getPostCategoryByInputObjectTypeResolver() : PostCategoryByInputObjectTypeResolver
    {
        /** @var PostCategoryByInputObjectTypeResolver */
        return $this->postCategoryByInputObjectTypeResolver = $this->postCategoryByInputObjectTypeResolver ?? $this->instanceManager->getInstance(PostCategoryByInputObjectTypeResolver::class);
    }
    /**
     * @param \PoPCMSSchema\Categories\TypeResolvers\InputObjectType\RootCategoriesFilterInputObjectTypeResolver $rootCategoriesFilterInputObjectTypeResolver
     */
    public final function setRootCategoriesFilterInputObjectTypeResolver($rootCategoriesFilterInputObjectTypeResolver) : void
    {
        $this->rootCategoriesFilterInputObjectTypeResolver = $rootCategoriesFilterInputObjectTypeResolver;
    }
    protected final function getRootCategoriesFilterInputObjectTypeResolver() : RootCategoriesFilterInputObjectTypeResolver
    {
        /** @var RootCategoriesFilterInputObjectTypeResolver */
        return $this->rootCategoriesFilterInputObjectTypeResolver = $this->rootCategoriesFilterInputObjectTypeResolver ?? $this->instanceManager->getInstance(RootCategoriesFilterInputObjectTypeResolver::class);
    }
    /**
     * @param \PoPCMSSchema\Categories\TypeResolvers\InputObjectType\CategoryPaginationInputObjectTypeResolver $categoryPaginationInputObjectTypeResolver
     */
    public final function setCategoryPaginationInputObjectTypeResolver($categoryPaginationInputObjectTypeResolver) : void
    {
        $this->categoryPaginationInputObjectTypeResolver = $categoryPaginationInputObjectTypeResolver;
    }
    protected final function getCategoryPaginationInputObjectTypeResolver() : CategoryPaginationInputObjectTypeResolver
    {
        /** @var CategoryPaginationInputObjectTypeResolver */
        return $this->categoryPaginationInputObjectTypeResolver = $this->categoryPaginationInputObjectTypeResolver ?? $this->instanceManager->getInstance(CategoryPaginationInputObjectTypeResolver::class);
    }
    /**
     * @param \PoPCMSSchema\Taxonomies\TypeResolvers\InputObjectType\TaxonomySortInputObjectTypeResolver $taxonomySortInputObjectTypeResolver
     */
    public final function setTaxonomySortInputObjectTypeResolver($taxonomySortInputObjectTypeResolver) : void
    {
        $this->taxonomySortInputObjectTypeResolver = $taxonomySortInputObjectTypeResolver;
    }
    protected final function getTaxonomySortInputObjectTypeResolver() : TaxonomySortInputObjectTypeResolver
    {
        /** @var TaxonomySortInputObjectTypeResolver */
        return $this->taxonomySortInputObjectTypeResolver = $this->taxonomySortInputObjectTypeResolver ?? $this->instanceManager->getInstance(TaxonomySortInputObjectTypeResolver::class);
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
        return ['postCategory', 'postCategories', 'postCategoryCount', 'postCategoryNames'];
    }
    /**
     * @param \PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface $objectTypeResolver
     * @param string $fieldName
     */
    public function getFieldTypeResolver($objectTypeResolver, $fieldName) : ConcreteTypeResolverInterface
    {
        switch ($fieldName) {
            case 'postCategory':
            case 'postCategories':
                return $this->getPostCategoryObjectTypeResolver();
            case 'postCategoryCount':
                return $this->getIntScalarTypeResolver();
            case 'postCategoryNames':
                return $this->getStringScalarTypeResolver();
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
            case 'postCategoryCount':
                return SchemaTypeModifiers::NON_NULLABLE;
            case 'postCategories':
            case 'postCategoryNames':
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
            case 'postCategory':
                return $this->__('Retrieve a single post category', 'post-categories');
            case 'postCategories':
                return $this->__('Post categories', 'post-categories');
            case 'postCategoryCount':
                return $this->__('Number of post categories', 'post-categories');
            case 'postCategoryNames':
                return $this->__('Names of the post categories', 'post-categories');
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
            case 'postCategory':
                return \array_merge($fieldArgNameTypeResolvers, ['by' => $this->getPostCategoryByInputObjectTypeResolver()]);
            case 'postCategories':
            case 'postCategoryNames':
                return \array_merge($fieldArgNameTypeResolvers, ['filter' => $this->getRootCategoriesFilterInputObjectTypeResolver(), 'pagination' => $this->getCategoryPaginationInputObjectTypeResolver(), 'sort' => $this->getTaxonomySortInputObjectTypeResolver()]);
            case 'postCategoryCount':
                return \array_merge($fieldArgNameTypeResolvers, ['filter' => $this->getRootCategoriesFilterInputObjectTypeResolver()]);
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
            case ['postCategory' => 'by']:
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
        $query = $this->convertFieldArgsToFilteringQueryArgs($objectTypeResolver, $fieldDataAccessor);
        switch ($fieldDataAccessor->getFieldName()) {
            case 'postCategory':
                if ($categories = $this->getPostCategoryTypeAPI()->getCategories($query, [QueryOptions::RETURN_TYPE => ReturnTypes::IDS])) {
                    return $categories[0];
                }
                return null;
            case 'postCategories':
                return $this->getPostCategoryTypeAPI()->getCategories($query, [QueryOptions::RETURN_TYPE => ReturnTypes::IDS]);
            case 'postCategoryNames':
                return $this->getPostCategoryTypeAPI()->getCategories($query, [QueryOptions::RETURN_TYPE => ReturnTypes::NAMES]);
            case 'postCategoryCount':
                return $this->getPostCategoryTypeAPI()->getCategoryCount($query);
        }
        return parent::resolveValue($objectTypeResolver, $object, $fieldDataAccessor, $objectTypeFieldResolutionFeedbackStore);
    }
}
