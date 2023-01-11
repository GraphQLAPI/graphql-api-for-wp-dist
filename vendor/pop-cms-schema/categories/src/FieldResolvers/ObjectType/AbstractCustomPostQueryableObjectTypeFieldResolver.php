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
use PoP\ComponentModel\TypeResolvers\ScalarType\IntScalarTypeResolver;
use PoP\ComponentModel\TypeResolvers\ScalarType\StringScalarTypeResolver;
use PoPCMSSchema\Categories\ModuleContracts\CategoryAPIRequestedContractObjectTypeFieldResolverInterface;
use PoPCMSSchema\Categories\TypeResolvers\InputObjectType\CategoryPaginationInputObjectTypeResolver;
use PoPSchema\SchemaCommons\Constants\QueryOptions;
use PoPCMSSchema\SchemaCommons\DataLoading\ReturnTypes;
use PoPCMSSchema\SchemaCommons\Resolvers\WithLimitFieldArgResolverTrait;
use PoPCMSSchema\Categories\TypeResolvers\InputObjectType\CustomPostCategoriesFilterInputObjectTypeResolver;
use PoPCMSSchema\Taxonomies\TypeResolvers\InputObjectType\TaxonomySortInputObjectTypeResolver;
abstract class AbstractCustomPostQueryableObjectTypeFieldResolver extends AbstractQueryableObjectTypeFieldResolver implements CategoryAPIRequestedContractObjectTypeFieldResolverInterface
{
    use WithLimitFieldArgResolverTrait;
    /**
     * @var \PoP\ComponentModel\TypeResolvers\ScalarType\StringScalarTypeResolver|null
     */
    private $stringScalarTypeResolver;
    /**
     * @var \PoP\ComponentModel\TypeResolvers\ScalarType\IntScalarTypeResolver|null
     */
    private $intScalarTypeResolver;
    /**
     * @var \PoPCMSSchema\Categories\TypeResolvers\InputObjectType\CustomPostCategoriesFilterInputObjectTypeResolver|null
     */
    private $customPostCategoriesFilterInputObjectTypeResolver;
    /**
     * @var \PoPCMSSchema\Categories\TypeResolvers\InputObjectType\CategoryPaginationInputObjectTypeResolver|null
     */
    private $categoryPaginationInputObjectTypeResolver;
    /**
     * @var \PoPCMSSchema\Taxonomies\TypeResolvers\InputObjectType\TaxonomySortInputObjectTypeResolver|null
     */
    private $taxonomySortInputObjectTypeResolver;
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
     * @param \PoPCMSSchema\Categories\TypeResolvers\InputObjectType\CustomPostCategoriesFilterInputObjectTypeResolver $customPostCategoriesFilterInputObjectTypeResolver
     */
    public final function setCustomPostCategoriesFilterInputObjectTypeResolver($customPostCategoriesFilterInputObjectTypeResolver) : void
    {
        $this->customPostCategoriesFilterInputObjectTypeResolver = $customPostCategoriesFilterInputObjectTypeResolver;
    }
    protected final function getCustomPostCategoriesFilterInputObjectTypeResolver() : CustomPostCategoriesFilterInputObjectTypeResolver
    {
        /** @var CustomPostCategoriesFilterInputObjectTypeResolver */
        return $this->customPostCategoriesFilterInputObjectTypeResolver = $this->customPostCategoriesFilterInputObjectTypeResolver ?? $this->instanceManager->getInstance(CustomPostCategoriesFilterInputObjectTypeResolver::class);
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
     * @return string[]
     */
    public function getFieldNamesToResolve() : array
    {
        return ['categories', 'categoryCount', 'categoryNames'];
    }
    /**
     * @param \PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface $objectTypeResolver
     * @param string $fieldName
     */
    public function getFieldTypeResolver($objectTypeResolver, $fieldName) : ConcreteTypeResolverInterface
    {
        switch ($fieldName) {
            case 'categories':
                return $this->getCategoryTypeResolver();
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
            case 'categories':
                return $this->__('Categories added to this custom post', 'pop-categories');
            case 'categoryCount':
                return $this->__('Number of categories added to this custom post', 'pop-categories');
            case 'categoryNames':
                return $this->__('Names of the categories added to this custom post', 'pop-categories');
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
            case 'categories':
            case 'categoryNames':
                return \array_merge($fieldArgNameTypeResolvers, ['filter' => $this->getCustomPostCategoriesFilterInputObjectTypeResolver(), 'pagination' => $this->getCategoryPaginationInputObjectTypeResolver(), 'sort' => $this->getTaxonomySortInputObjectTypeResolver()]);
            case 'categoryCount':
                return \array_merge($fieldArgNameTypeResolvers, ['filter' => $this->getCustomPostCategoriesFilterInputObjectTypeResolver()]);
            default:
                return $fieldArgNameTypeResolvers;
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
        $categoryTypeAPI = $this->getCategoryTypeAPI();
        $customPost = $object;
        $query = $this->convertFieldArgsToFilteringQueryArgs($objectTypeResolver, $fieldDataAccessor);
        switch ($fieldDataAccessor->getFieldName()) {
            case 'categories':
                return $categoryTypeAPI->getCustomPostCategories($customPost, $query, [QueryOptions::RETURN_TYPE => ReturnTypes::IDS]);
            case 'categoryNames':
                return $categoryTypeAPI->getCustomPostCategories($customPost, $query, [QueryOptions::RETURN_TYPE => ReturnTypes::NAMES]);
            case 'categoryCount':
                /** @var int */
                return $categoryTypeAPI->getCustomPostCategoryCount($customPost, $query);
        }
        return parent::resolveValue($objectTypeResolver, $object, $fieldDataAccessor, $objectTypeFieldResolutionFeedbackStore);
    }
}
