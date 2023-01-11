<?php

declare (strict_types=1);
namespace PoPCMSSchema\Categories\FieldResolvers\ObjectType;

use PoPCMSSchema\Categories\ComponentProcessors\CategoryFilterInputContainerComponentProcessor;
use PoPCMSSchema\Categories\FieldResolvers\ObjectType\AbstractCustomPostQueryableObjectTypeFieldResolver;
use PoPCMSSchema\Categories\TypeAPIs\CategoryTypeAPIInterface;
use PoPCMSSchema\Categories\TypeAPIs\QueryableCategoryTypeAPIInterface;
use PoPCMSSchema\Categories\TypeResolvers\ObjectType\CategoryObjectTypeResolverInterface;
use PoPCMSSchema\Categories\TypeResolvers\ObjectType\GenericCategoryObjectTypeResolver;
use PoPCMSSchema\CustomPosts\TypeResolvers\ObjectType\GenericCustomPostObjectTypeResolver;
use PoP\ComponentModel\Component\Component;
use PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface;
class GenericCustomPostQueryableObjectTypeFieldResolver extends AbstractCustomPostQueryableObjectTypeFieldResolver
{
    /**
     * @var \PoPCMSSchema\Categories\TypeAPIs\QueryableCategoryTypeAPIInterface|null
     */
    private $queryableCategoryTypeAPI;
    /**
     * @var \PoPCMSSchema\Categories\TypeResolvers\ObjectType\GenericCategoryObjectTypeResolver|null
     */
    private $genericCategoryObjectTypeResolver;
    /**
     * @param \PoPCMSSchema\Categories\TypeAPIs\QueryableCategoryTypeAPIInterface $queryableCategoryTypeAPI
     */
    public final function setQueryableCategoryTypeAPI($queryableCategoryTypeAPI) : void
    {
        $this->queryableCategoryTypeAPI = $queryableCategoryTypeAPI;
    }
    protected final function getQueryableCategoryTypeAPI() : QueryableCategoryTypeAPIInterface
    {
        /** @var QueryableCategoryTypeAPIInterface */
        return $this->queryableCategoryTypeAPI = $this->queryableCategoryTypeAPI ?? $this->instanceManager->getInstance(QueryableCategoryTypeAPIInterface::class);
    }
    /**
     * @param \PoPCMSSchema\Categories\TypeResolvers\ObjectType\GenericCategoryObjectTypeResolver $genericCategoryObjectTypeResolver
     */
    public final function setGenericCategoryObjectTypeResolver($genericCategoryObjectTypeResolver) : void
    {
        $this->genericCategoryObjectTypeResolver = $genericCategoryObjectTypeResolver;
    }
    protected final function getGenericCategoryObjectTypeResolver() : GenericCategoryObjectTypeResolver
    {
        /** @var GenericCategoryObjectTypeResolver */
        return $this->genericCategoryObjectTypeResolver = $this->genericCategoryObjectTypeResolver ?? $this->instanceManager->getInstance(GenericCategoryObjectTypeResolver::class);
    }
    /**
     * @return array<class-string<ObjectTypeResolverInterface>>
     */
    public function getObjectTypeResolverClassesToAttachTo() : array
    {
        return [GenericCustomPostObjectTypeResolver::class];
    }
    /**
     * @param \PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface $objectTypeResolver
     * @param string $fieldName
     */
    public function getFieldFilterInputContainerComponent($objectTypeResolver, $fieldName) : ?Component
    {
        switch ($fieldName) {
            case 'categories':
            case 'categoryNames':
            case 'categoryCount':
                return new Component(CategoryFilterInputContainerComponentProcessor::class, CategoryFilterInputContainerComponentProcessor::COMPONENT_FILTERINPUTCONTAINER_GENERICCATEGORIES);
            default:
                return parent::getFieldFilterInputContainerComponent($objectTypeResolver, $fieldName);
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
                return $this->__('Categories added to this custom post', 'pop-post-categories');
            case 'categoryCount':
                return $this->__('Number of categories added to this custom post', 'pop-post-categories');
            case 'categoryNames':
                return $this->__('Names of the categories added to this custom post', 'pop-post-categories');
            default:
                return parent::getFieldDescription($objectTypeResolver, $fieldName);
        }
    }
    public function getCategoryTypeAPI() : CategoryTypeAPIInterface
    {
        return $this->getQueryableCategoryTypeAPI();
    }
    public function getCategoryTypeResolver() : CategoryObjectTypeResolverInterface
    {
        return $this->getGenericCategoryObjectTypeResolver();
    }
}
