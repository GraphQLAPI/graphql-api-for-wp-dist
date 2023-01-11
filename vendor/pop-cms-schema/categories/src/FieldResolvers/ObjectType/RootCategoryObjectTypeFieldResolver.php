<?php

declare (strict_types=1);
namespace PoPCMSSchema\Categories\FieldResolvers\ObjectType;

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
use PoPCMSSchema\Categories\TypeAPIs\QueryableTaxonomyCategoryListTypeAPIInterface;
use PoPCMSSchema\Categories\TypeResolvers\EnumType\CategoryTaxonomyEnumStringScalarTypeResolver;
use PoPCMSSchema\Categories\TypeResolvers\InputObjectType\CategoryByInputObjectTypeResolver;
use PoPCMSSchema\Categories\TypeResolvers\UnionType\CategoryUnionTypeResolver;
use PoPSchema\SchemaCommons\Constants\QueryOptions;
use PoPCMSSchema\SchemaCommons\DataLoading\ReturnTypes;
use PoPCMSSchema\SchemaCommons\Resolvers\WithLimitFieldArgResolverTrait;
use PoPCMSSchema\Taxonomies\TypeResolvers\InputObjectType\TaxonomySortInputObjectTypeResolver;
class RootCategoryObjectTypeFieldResolver extends AbstractQueryableObjectTypeFieldResolver
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
     * @var \PoPCMSSchema\Categories\TypeResolvers\UnionType\CategoryUnionTypeResolver|null
     */
    private $categoryUnionTypeResolver;
    /**
     * @var \PoPCMSSchema\Categories\TypeAPIs\QueryableTaxonomyCategoryListTypeAPIInterface|null
     */
    private $queryableTaxonomyCategoryListTypeAPI;
    /**
     * @var \PoPCMSSchema\Categories\TypeResolvers\InputObjectType\CategoryByInputObjectTypeResolver|null
     */
    private $categoryByInputObjectTypeResolver;
    /**
     * @var \PoPCMSSchema\Categories\TypeResolvers\EnumType\CategoryTaxonomyEnumStringScalarTypeResolver|null
     */
    private $categoryTaxonomyEnumStringScalarTypeResolver;
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
     * @param \PoPCMSSchema\Categories\TypeResolvers\UnionType\CategoryUnionTypeResolver $categoryUnionTypeResolver
     */
    public final function setCategoryUnionTypeResolver($categoryUnionTypeResolver) : void
    {
        $this->categoryUnionTypeResolver = $categoryUnionTypeResolver;
    }
    protected final function getCategoryUnionTypeResolver() : CategoryUnionTypeResolver
    {
        /** @var CategoryUnionTypeResolver */
        return $this->categoryUnionTypeResolver = $this->categoryUnionTypeResolver ?? $this->instanceManager->getInstance(CategoryUnionTypeResolver::class);
    }
    /**
     * @param \PoPCMSSchema\Categories\TypeAPIs\QueryableTaxonomyCategoryListTypeAPIInterface $queryableTaxonomyCategoryListTypeAPI
     */
    public final function setQueryableTaxonomyCategoryListTypeAPI($queryableTaxonomyCategoryListTypeAPI) : void
    {
        $this->queryableTaxonomyCategoryListTypeAPI = $queryableTaxonomyCategoryListTypeAPI;
    }
    protected final function getQueryableTaxonomyCategoryListTypeAPI() : QueryableTaxonomyCategoryListTypeAPIInterface
    {
        /** @var QueryableTaxonomyCategoryListTypeAPIInterface */
        return $this->queryableTaxonomyCategoryListTypeAPI = $this->queryableTaxonomyCategoryListTypeAPI ?? $this->instanceManager->getInstance(QueryableTaxonomyCategoryListTypeAPIInterface::class);
    }
    /**
     * @param \PoPCMSSchema\Categories\TypeResolvers\InputObjectType\CategoryByInputObjectTypeResolver $categoryByInputObjectTypeResolver
     */
    public final function setCategoryByInputObjectTypeResolver($categoryByInputObjectTypeResolver) : void
    {
        $this->categoryByInputObjectTypeResolver = $categoryByInputObjectTypeResolver;
    }
    protected final function getCategoryByInputObjectTypeResolver() : CategoryByInputObjectTypeResolver
    {
        /** @var CategoryByInputObjectTypeResolver */
        return $this->categoryByInputObjectTypeResolver = $this->categoryByInputObjectTypeResolver ?? $this->instanceManager->getInstance(CategoryByInputObjectTypeResolver::class);
    }
    /**
     * @param \PoPCMSSchema\Categories\TypeResolvers\EnumType\CategoryTaxonomyEnumStringScalarTypeResolver $categoryTaxonomyEnumStringScalarTypeResolver
     */
    public final function setCategoryTaxonomyEnumStringScalarTypeResolver($categoryTaxonomyEnumStringScalarTypeResolver) : void
    {
        $this->categoryTaxonomyEnumStringScalarTypeResolver = $categoryTaxonomyEnumStringScalarTypeResolver;
    }
    protected final function getCategoryTaxonomyEnumStringScalarTypeResolver() : CategoryTaxonomyEnumStringScalarTypeResolver
    {
        /** @var CategoryTaxonomyEnumStringScalarTypeResolver */
        return $this->categoryTaxonomyEnumStringScalarTypeResolver = $this->categoryTaxonomyEnumStringScalarTypeResolver ?? $this->instanceManager->getInstance(CategoryTaxonomyEnumStringScalarTypeResolver::class);
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
        return ['category', 'categories', 'categoryCount', 'categoryNames'];
    }
    /**
     * @param \PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface $objectTypeResolver
     * @param string $fieldName
     */
    public function getFieldTypeResolver($objectTypeResolver, $fieldName) : ConcreteTypeResolverInterface
    {
        switch ($fieldName) {
            case 'category':
            case 'categories':
                return $this->getCategoryUnionTypeResolver();
            case 'categoryCount':
                return $this->getIntScalarTypeResolver();
            case 'categoryNames':
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
            case 'categoryCount':
                return SchemaTypeModifiers::NON_NULLABLE;
            case 'categories':
            case 'categoryNames':
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
            case 'category':
                return $this->__('Retrieve a single category', 'categories');
            case 'categories':
                return $this->__('Categories', 'categories');
            case 'categoryCount':
                return $this->__('Number of categories', 'categories');
            case 'categoryNames':
                return $this->__('Names of the categories', 'categories');
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
        $commonFieldArgNameTypeResolvers = ['taxonomy' => $this->getCategoryTaxonomyEnumStringScalarTypeResolver()];
        switch ($fieldName) {
            case 'category':
                return \array_merge($fieldArgNameTypeResolvers, $commonFieldArgNameTypeResolvers, ['by' => $this->getCategoryByInputObjectTypeResolver()]);
            case 'categories':
            case 'categoryNames':
                return \array_merge($fieldArgNameTypeResolvers, $commonFieldArgNameTypeResolvers, ['filter' => $this->getRootCategoriesFilterInputObjectTypeResolver(), 'pagination' => $this->getCategoryPaginationInputObjectTypeResolver(), 'sort' => $this->getTaxonomySortInputObjectTypeResolver()]);
            case 'categoryCount':
                return \array_merge($fieldArgNameTypeResolvers, $commonFieldArgNameTypeResolvers, ['filter' => $this->getRootCategoriesFilterInputObjectTypeResolver()]);
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
        if ($fieldArgName === 'taxonomy') {
            return SchemaTypeModifiers::MANDATORY;
        }
        switch ([$fieldName => $fieldArgName]) {
            case ['category' => 'by']:
                return SchemaTypeModifiers::MANDATORY;
            default:
                return parent::getFieldArgTypeModifiers($objectTypeResolver, $fieldName, $fieldArgName);
        }
    }
    /**
     * @param \PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface $objectTypeResolver
     * @param string $fieldName
     * @param string $fieldArgName
     */
    public function getFieldArgDescription($objectTypeResolver, $fieldName, $fieldArgName) : ?string
    {
        if ($fieldArgName === 'taxonomy') {
            return $this->__('Taxonomy of the category', 'categories');
        }
        switch ([$fieldName => $fieldArgName]) {
            case ['category' => 'by']:
                return $this->__('Parameter by which to select the category', 'categories');
            default:
                return parent::getFieldArgDescription($objectTypeResolver, $fieldName, $fieldArgName);
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
        /** @var string */
        $catTaxonomy = $fieldDataAccessor->getValue('taxonomy');
        switch ($fieldDataAccessor->getFieldName()) {
            case 'category':
                if ($categories = $this->getQueryableTaxonomyCategoryListTypeAPI()->getTaxonomyCategories($catTaxonomy, $query, [QueryOptions::RETURN_TYPE => ReturnTypes::IDS])) {
                    return $categories[0];
                }
                return null;
            case 'categories':
                return $this->getQueryableTaxonomyCategoryListTypeAPI()->getTaxonomyCategories($catTaxonomy, $query, [QueryOptions::RETURN_TYPE => ReturnTypes::IDS]);
            case 'categoryNames':
                return $this->getQueryableTaxonomyCategoryListTypeAPI()->getTaxonomyCategories($catTaxonomy, $query, [QueryOptions::RETURN_TYPE => ReturnTypes::NAMES]);
            case 'categoryCount':
                return $this->getQueryableTaxonomyCategoryListTypeAPI()->getTaxonomyCategoryCount($catTaxonomy, $query);
        }
        return parent::resolveValue($objectTypeResolver, $object, $fieldDataAccessor, $objectTypeFieldResolutionFeedbackStore);
    }
}
