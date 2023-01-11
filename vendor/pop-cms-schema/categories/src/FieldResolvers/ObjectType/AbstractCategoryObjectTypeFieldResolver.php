<?php

declare (strict_types=1);
namespace PoPCMSSchema\Categories\FieldResolvers\ObjectType;

use PoPCMSSchema\Categories\FieldResolvers\InterfaceType\CategoryInterfaceTypeFieldResolver;
use PoPCMSSchema\Categories\ModuleContracts\CategoryAPIRequestedContractObjectTypeFieldResolverInterface;
use PoPCMSSchema\QueriedObject\FieldResolvers\InterfaceType\QueryableInterfaceTypeFieldResolver;
use PoP\ComponentModel\Feedback\ObjectTypeFieldResolutionFeedbackStore;
use PoP\ComponentModel\FieldResolvers\InterfaceType\InterfaceTypeFieldResolverInterface;
use PoP\ComponentModel\FieldResolvers\ObjectType\AbstractObjectTypeFieldResolver;
use PoP\ComponentModel\QueryResolution\FieldDataAccessorInterface;
use PoP\ComponentModel\TypeResolvers\ConcreteTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\ScalarType\IntScalarTypeResolver;
use PoP\ComponentModel\TypeResolvers\ScalarType\StringScalarTypeResolver;
abstract class AbstractCategoryObjectTypeFieldResolver extends AbstractObjectTypeFieldResolver implements CategoryAPIRequestedContractObjectTypeFieldResolverInterface
{
    /**
     * @var \PoP\ComponentModel\TypeResolvers\ScalarType\StringScalarTypeResolver|null
     */
    private $stringScalarTypeResolver;
    /**
     * @var \PoP\ComponentModel\TypeResolvers\ScalarType\IntScalarTypeResolver|null
     */
    private $intScalarTypeResolver;
    /**
     * @var \PoPCMSSchema\QueriedObject\FieldResolvers\InterfaceType\QueryableInterfaceTypeFieldResolver|null
     */
    private $queryableInterfaceTypeFieldResolver;
    /**
     * @var \PoPCMSSchema\Categories\FieldResolvers\InterfaceType\CategoryInterfaceTypeFieldResolver|null
     */
    private $categoryInterfaceTypeFieldResolver;
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
     * @param \PoPCMSSchema\QueriedObject\FieldResolvers\InterfaceType\QueryableInterfaceTypeFieldResolver $queryableInterfaceTypeFieldResolver
     */
    public final function setQueryableInterfaceTypeFieldResolver($queryableInterfaceTypeFieldResolver) : void
    {
        $this->queryableInterfaceTypeFieldResolver = $queryableInterfaceTypeFieldResolver;
    }
    protected final function getQueryableInterfaceTypeFieldResolver() : QueryableInterfaceTypeFieldResolver
    {
        /** @var QueryableInterfaceTypeFieldResolver */
        return $this->queryableInterfaceTypeFieldResolver = $this->queryableInterfaceTypeFieldResolver ?? $this->instanceManager->getInstance(QueryableInterfaceTypeFieldResolver::class);
    }
    /**
     * @param \PoPCMSSchema\Categories\FieldResolvers\InterfaceType\CategoryInterfaceTypeFieldResolver $categoryInterfaceTypeFieldResolver
     */
    public final function setCategoryInterfaceTypeFieldResolver($categoryInterfaceTypeFieldResolver) : void
    {
        $this->categoryInterfaceTypeFieldResolver = $categoryInterfaceTypeFieldResolver;
    }
    protected final function getCategoryInterfaceTypeFieldResolver() : CategoryInterfaceTypeFieldResolver
    {
        /** @var CategoryInterfaceTypeFieldResolver */
        return $this->categoryInterfaceTypeFieldResolver = $this->categoryInterfaceTypeFieldResolver ?? $this->instanceManager->getInstance(CategoryInterfaceTypeFieldResolver::class);
    }
    /**
     * @return array<InterfaceTypeFieldResolverInterface>
     */
    public function getImplementedInterfaceTypeFieldResolvers() : array
    {
        return [$this->getQueryableInterfaceTypeFieldResolver(), $this->getCategoryInterfaceTypeFieldResolver()];
    }
    /**
     * @return string[]
     */
    public function getFieldNamesToResolve() : array
    {
        return [
            // Queryable interface
            'url',
            'urlAbsolutePath',
            'slug',
            // Category interface
            'name',
            'description',
            'count',
            // Own
            'parent',
        ];
    }
    /**
     * @param \PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface $objectTypeResolver
     * @param string $fieldName
     */
    public function getFieldTypeResolver($objectTypeResolver, $fieldName) : ConcreteTypeResolverInterface
    {
        switch ($fieldName) {
            case 'parent':
                return $this->getCategoryTypeResolver();
            default:
                return parent::getFieldTypeResolver($objectTypeResolver, $fieldName);
        }
    }
    /**
     * @param \PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface $objectTypeResolver
     * @param string $fieldName
     */
    public function getFieldDescription($objectTypeResolver, $fieldName) : ?string
    {
        switch ($fieldName) {
            case 'url':
                return $this->__('Category URL', 'pop-categories');
            case 'urlAbsolutePath':
                return $this->__('Category URL path', 'pop-categories');
            case 'slug':
                return $this->__('Category slug', 'pop-categories');
            case 'parent':
                return $this->__('Parent category (if this category is a child of another one)', 'pop-categories');
            default:
                return parent::getFieldDescription($objectTypeResolver, $fieldName);
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
        $category = $object;
        switch ($fieldDataAccessor->getFieldName()) {
            case 'url':
                /** @var string */
                return $categoryTypeAPI->getCategoryURL($category);
            case 'urlAbsolutePath':
                /** @var string */
                return $categoryTypeAPI->getCategoryURLPath($category);
            case 'name':
                /** @var string */
                return $categoryTypeAPI->getCategoryName($category);
            case 'slug':
                /** @var string */
                return $categoryTypeAPI->getCategorySlug($category);
            case 'description':
                /** @var string */
                return $categoryTypeAPI->getCategoryDescription($category);
            case 'parent':
                return $categoryTypeAPI->getCategoryParentID($category);
            case 'count':
                /** @var int */
                return $categoryTypeAPI->getCategoryItemCount($category);
        }
        return parent::resolveValue($objectTypeResolver, $object, $fieldDataAccessor, $objectTypeFieldResolutionFeedbackStore);
    }
}
