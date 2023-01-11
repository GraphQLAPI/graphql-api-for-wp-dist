<?php

declare (strict_types=1);
namespace PoPCMSSchema\Pages\FieldResolvers\ObjectType;

use PoPCMSSchema\CustomPosts\ComponentProcessors\CommonCustomPostFilterInputContainerComponentProcessor;
use PoPCMSSchema\CustomPosts\ComponentProcessors\FormInputs\FilterInputComponentProcessor;
use PoPCMSSchema\CustomPosts\Module;
use PoPCMSSchema\CustomPosts\ModuleConfiguration;
use PoPCMSSchema\CustomPosts\TypeResolvers\InputObjectType\CustomPostSortInputObjectTypeResolver;
use PoPCMSSchema\Pages\TypeAPIs\PageTypeAPIInterface;
use PoPCMSSchema\Pages\TypeResolvers\InputObjectType\PageByInputObjectTypeResolver;
use PoPCMSSchema\Pages\TypeResolvers\InputObjectType\PagePaginationInputObjectTypeResolver;
use PoPCMSSchema\Pages\TypeResolvers\InputObjectType\RootPagesFilterInputObjectTypeResolver;
use PoPCMSSchema\Pages\TypeResolvers\ObjectType\PageObjectTypeResolver;
use PoPCMSSchema\SchemaCommons\DataLoading\ReturnTypes;
use PoPCMSSchema\SchemaCommons\Resolvers\WithLimitFieldArgResolverTrait;
use PoPSchema\SchemaCommons\Constants\QueryOptions;
use PoP\ComponentModel\App;
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
class RootPageObjectTypeFieldResolver extends AbstractQueryableObjectTypeFieldResolver
{
    use WithLimitFieldArgResolverTrait;
    /**
     * @var \PoP\ComponentModel\TypeResolvers\ScalarType\IntScalarTypeResolver|null
     */
    private $intScalarTypeResolver;
    /**
     * @var \PoPCMSSchema\Pages\TypeResolvers\ObjectType\PageObjectTypeResolver|null
     */
    private $pageObjectTypeResolver;
    /**
     * @var \PoPCMSSchema\Pages\TypeAPIs\PageTypeAPIInterface|null
     */
    private $pageTypeAPI;
    /**
     * @var \PoPCMSSchema\Pages\TypeResolvers\InputObjectType\PageByInputObjectTypeResolver|null
     */
    private $pageByInputObjectTypeResolver;
    /**
     * @var \PoPCMSSchema\Pages\TypeResolvers\InputObjectType\RootPagesFilterInputObjectTypeResolver|null
     */
    private $rootPagesFilterInputObjectTypeResolver;
    /**
     * @var \PoPCMSSchema\Pages\TypeResolvers\InputObjectType\PagePaginationInputObjectTypeResolver|null
     */
    private $pagePaginationInputObjectTypeResolver;
    /**
     * @var \PoPCMSSchema\CustomPosts\TypeResolvers\InputObjectType\CustomPostSortInputObjectTypeResolver|null
     */
    private $customPostSortInputObjectTypeResolver;
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
     * @param \PoPCMSSchema\Pages\TypeResolvers\ObjectType\PageObjectTypeResolver $pageObjectTypeResolver
     */
    public final function setPageObjectTypeResolver($pageObjectTypeResolver) : void
    {
        $this->pageObjectTypeResolver = $pageObjectTypeResolver;
    }
    protected final function getPageObjectTypeResolver() : PageObjectTypeResolver
    {
        /** @var PageObjectTypeResolver */
        return $this->pageObjectTypeResolver = $this->pageObjectTypeResolver ?? $this->instanceManager->getInstance(PageObjectTypeResolver::class);
    }
    /**
     * @param \PoPCMSSchema\Pages\TypeAPIs\PageTypeAPIInterface $pageTypeAPI
     */
    public final function setPageTypeAPI($pageTypeAPI) : void
    {
        $this->pageTypeAPI = $pageTypeAPI;
    }
    protected final function getPageTypeAPI() : PageTypeAPIInterface
    {
        /** @var PageTypeAPIInterface */
        return $this->pageTypeAPI = $this->pageTypeAPI ?? $this->instanceManager->getInstance(PageTypeAPIInterface::class);
    }
    /**
     * @param \PoPCMSSchema\Pages\TypeResolvers\InputObjectType\PageByInputObjectTypeResolver $pageByInputObjectTypeResolver
     */
    public final function setPageByInputObjectTypeResolver($pageByInputObjectTypeResolver) : void
    {
        $this->pageByInputObjectTypeResolver = $pageByInputObjectTypeResolver;
    }
    protected final function getPageByInputObjectTypeResolver() : PageByInputObjectTypeResolver
    {
        /** @var PageByInputObjectTypeResolver */
        return $this->pageByInputObjectTypeResolver = $this->pageByInputObjectTypeResolver ?? $this->instanceManager->getInstance(PageByInputObjectTypeResolver::class);
    }
    /**
     * @param \PoPCMSSchema\Pages\TypeResolvers\InputObjectType\RootPagesFilterInputObjectTypeResolver $rootPagesFilterInputObjectTypeResolver
     */
    public final function setRootPagesFilterInputObjectTypeResolver($rootPagesFilterInputObjectTypeResolver) : void
    {
        $this->rootPagesFilterInputObjectTypeResolver = $rootPagesFilterInputObjectTypeResolver;
    }
    protected final function getRootPagesFilterInputObjectTypeResolver() : RootPagesFilterInputObjectTypeResolver
    {
        /** @var RootPagesFilterInputObjectTypeResolver */
        return $this->rootPagesFilterInputObjectTypeResolver = $this->rootPagesFilterInputObjectTypeResolver ?? $this->instanceManager->getInstance(RootPagesFilterInputObjectTypeResolver::class);
    }
    /**
     * @param \PoPCMSSchema\Pages\TypeResolvers\InputObjectType\PagePaginationInputObjectTypeResolver $pagePaginationInputObjectTypeResolver
     */
    public final function setPagePaginationInputObjectTypeResolver($pagePaginationInputObjectTypeResolver) : void
    {
        $this->pagePaginationInputObjectTypeResolver = $pagePaginationInputObjectTypeResolver;
    }
    protected final function getPagePaginationInputObjectTypeResolver() : PagePaginationInputObjectTypeResolver
    {
        /** @var PagePaginationInputObjectTypeResolver */
        return $this->pagePaginationInputObjectTypeResolver = $this->pagePaginationInputObjectTypeResolver ?? $this->instanceManager->getInstance(PagePaginationInputObjectTypeResolver::class);
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
        return ['page', 'pages', 'pageCount'];
    }
    /**
     * @param \PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface $objectTypeResolver
     * @param string $fieldName
     */
    public function getFieldDescription($objectTypeResolver, $fieldName) : ?string
    {
        switch ($fieldName) {
            case 'page':
                return $this->__('Retrieve a single page', 'pages');
            case 'pages':
                return $this->__('Pages', 'pages');
            case 'pageCount':
                return $this->__('Number of pages', 'pages');
            default:
                return parent::getFieldDescription($objectTypeResolver, $fieldName);
        }
    }
    /**
     * @param \PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface $objectTypeResolver
     * @param string $fieldName
     */
    public function getFieldTypeResolver($objectTypeResolver, $fieldName) : ConcreteTypeResolverInterface
    {
        switch ($fieldName) {
            case 'page':
            case 'pages':
                return $this->getPageObjectTypeResolver();
            case 'pageCount':
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
            case 'pageCount':
                return SchemaTypeModifiers::NON_NULLABLE;
            case 'pages':
                return SchemaTypeModifiers::NON_NULLABLE | SchemaTypeModifiers::IS_ARRAY | SchemaTypeModifiers::IS_NON_NULLABLE_ITEMS_IN_ARRAY;
            default:
                return parent::getFieldTypeModifiers($objectTypeResolver, $fieldName);
        }
    }
    /**
     * @param \PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface $objectTypeResolver
     * @param string $fieldName
     */
    public function getFieldFilterInputContainerComponent($objectTypeResolver, $fieldName) : ?Component
    {
        switch ($fieldName) {
            case 'page':
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
            case 'page':
                return \array_merge($fieldArgNameTypeResolvers, ['by' => $this->getPageByInputObjectTypeResolver()]);
            case 'pages':
                return \array_merge($fieldArgNameTypeResolvers, ['filter' => $this->getRootPagesFilterInputObjectTypeResolver(), 'pagination' => $this->getPagePaginationInputObjectTypeResolver(), 'sort' => $this->getCustomPostSortInputObjectTypeResolver()]);
            case 'pageCount':
                return \array_merge($fieldArgNameTypeResolvers, ['filter' => $this->getRootPagesFilterInputObjectTypeResolver()]);
            default:
                return $fieldArgNameTypeResolvers;
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
            case 'page':
                if ($moduleConfiguration->treatCustomPostStatusAsSensitiveData()) {
                    $customPostStatusFilterInputName = FilterInputHelper::getFilterInputName(new Component(FilterInputComponentProcessor::class, FilterInputComponentProcessor::COMPONENT_FILTERINPUT_CUSTOMPOSTSTATUS));
                    $sensitiveFieldArgNames[] = $customPostStatusFilterInputName;
                }
                break;
        }
        return $sensitiveFieldArgNames;
    }
    /**
     * @param \PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface $objectTypeResolver
     * @param string $fieldName
     * @param string $fieldArgName
     */
    public function getFieldArgTypeModifiers($objectTypeResolver, $fieldName, $fieldArgName) : int
    {
        switch ([$fieldName => $fieldArgName]) {
            case ['page' => 'by']:
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
            case 'page':
                if ($pages = $this->getPageTypeAPI()->getPages($query, [QueryOptions::RETURN_TYPE => ReturnTypes::IDS])) {
                    return $pages[0];
                }
                return null;
            case 'pages':
                return $this->getPageTypeAPI()->getPages($query, [QueryOptions::RETURN_TYPE => ReturnTypes::IDS]);
            case 'pageCount':
                return $this->getPageTypeAPI()->getPageCount($query);
        }
        return parent::resolveValue($objectTypeResolver, $object, $fieldDataAccessor, $objectTypeFieldResolutionFeedbackStore);
    }
}
