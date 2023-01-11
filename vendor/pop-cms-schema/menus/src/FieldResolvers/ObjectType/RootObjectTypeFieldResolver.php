<?php

declare (strict_types=1);
namespace PoPCMSSchema\Menus\FieldResolvers\ObjectType;

use PoP\ComponentModel\TypeResolvers\InputTypeResolverInterface;
use PoP\ComponentModel\QueryResolution\FieldDataAccessorInterface;
use PoP\ComponentModel\Feedback\ObjectTypeFieldResolutionFeedbackStore;
use PoP\ComponentModel\FieldResolvers\ObjectType\AbstractQueryableObjectTypeFieldResolver;
use PoP\ComponentModel\Schema\SchemaTypeModifiers;
use PoP\ComponentModel\TypeResolvers\ConcreteTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface;
use PoP\Engine\TypeResolvers\ObjectType\RootObjectTypeResolver;
use PoP\ComponentModel\TypeResolvers\ScalarType\IntScalarTypeResolver;
use PoPCMSSchema\Menus\TypeAPIs\MenuTypeAPIInterface;
use PoPCMSSchema\Menus\TypeResolvers\InputObjectType\MenuByInputObjectTypeResolver;
use PoPCMSSchema\Menus\TypeResolvers\InputObjectType\MenuSortInputObjectTypeResolver;
use PoPCMSSchema\Menus\TypeResolvers\InputObjectType\RootMenuPaginationInputObjectTypeResolver;
use PoPCMSSchema\Menus\TypeResolvers\InputObjectType\RootMenusFilterInputObjectTypeResolver;
use PoPCMSSchema\Menus\TypeResolvers\ObjectType\MenuObjectTypeResolver;
use PoPSchema\SchemaCommons\Constants\QueryOptions;
use PoPCMSSchema\SchemaCommons\DataLoading\ReturnTypes;
class RootObjectTypeFieldResolver extends AbstractQueryableObjectTypeFieldResolver
{
    /**
     * @var \PoP\ComponentModel\TypeResolvers\ScalarType\IntScalarTypeResolver|null
     */
    private $intScalarTypeResolver;
    /**
     * @var \PoPCMSSchema\Menus\TypeResolvers\ObjectType\MenuObjectTypeResolver|null
     */
    private $menuObjectTypeResolver;
    /**
     * @var \PoPCMSSchema\Menus\TypeAPIs\MenuTypeAPIInterface|null
     */
    private $menuTypeAPI;
    /**
     * @var \PoPCMSSchema\Menus\TypeResolvers\InputObjectType\MenuByInputObjectTypeResolver|null
     */
    private $menuByInputObjectTypeResolver;
    /**
     * @var \PoPCMSSchema\Menus\TypeResolvers\InputObjectType\RootMenusFilterInputObjectTypeResolver|null
     */
    private $rootMenusFilterInputObjectTypeResolver;
    /**
     * @var \PoPCMSSchema\Menus\TypeResolvers\InputObjectType\RootMenuPaginationInputObjectTypeResolver|null
     */
    private $rootMenuPaginationInputObjectTypeResolver;
    /**
     * @var \PoPCMSSchema\Menus\TypeResolvers\InputObjectType\MenuSortInputObjectTypeResolver|null
     */
    private $menuSortInputObjectTypeResolver;
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
     * @param \PoPCMSSchema\Menus\TypeResolvers\ObjectType\MenuObjectTypeResolver $menuObjectTypeResolver
     */
    public final function setMenuObjectTypeResolver($menuObjectTypeResolver) : void
    {
        $this->menuObjectTypeResolver = $menuObjectTypeResolver;
    }
    protected final function getMenuObjectTypeResolver() : MenuObjectTypeResolver
    {
        /** @var MenuObjectTypeResolver */
        return $this->menuObjectTypeResolver = $this->menuObjectTypeResolver ?? $this->instanceManager->getInstance(MenuObjectTypeResolver::class);
    }
    /**
     * @param \PoPCMSSchema\Menus\TypeAPIs\MenuTypeAPIInterface $menuTypeAPI
     */
    public final function setMenuTypeAPI($menuTypeAPI) : void
    {
        $this->menuTypeAPI = $menuTypeAPI;
    }
    protected final function getMenuTypeAPI() : MenuTypeAPIInterface
    {
        /** @var MenuTypeAPIInterface */
        return $this->menuTypeAPI = $this->menuTypeAPI ?? $this->instanceManager->getInstance(MenuTypeAPIInterface::class);
    }
    /**
     * @param \PoPCMSSchema\Menus\TypeResolvers\InputObjectType\MenuByInputObjectTypeResolver $menuByInputObjectTypeResolver
     */
    public final function setMenuByInputObjectTypeResolver($menuByInputObjectTypeResolver) : void
    {
        $this->menuByInputObjectTypeResolver = $menuByInputObjectTypeResolver;
    }
    protected final function getMenuByInputObjectTypeResolver() : MenuByInputObjectTypeResolver
    {
        /** @var MenuByInputObjectTypeResolver */
        return $this->menuByInputObjectTypeResolver = $this->menuByInputObjectTypeResolver ?? $this->instanceManager->getInstance(MenuByInputObjectTypeResolver::class);
    }
    /**
     * @param \PoPCMSSchema\Menus\TypeResolvers\InputObjectType\RootMenusFilterInputObjectTypeResolver $rootMenusFilterInputObjectTypeResolver
     */
    public final function setRootMenusFilterInputObjectTypeResolver($rootMenusFilterInputObjectTypeResolver) : void
    {
        $this->rootMenusFilterInputObjectTypeResolver = $rootMenusFilterInputObjectTypeResolver;
    }
    protected final function getRootMenusFilterInputObjectTypeResolver() : RootMenusFilterInputObjectTypeResolver
    {
        /** @var RootMenusFilterInputObjectTypeResolver */
        return $this->rootMenusFilterInputObjectTypeResolver = $this->rootMenusFilterInputObjectTypeResolver ?? $this->instanceManager->getInstance(RootMenusFilterInputObjectTypeResolver::class);
    }
    /**
     * @param \PoPCMSSchema\Menus\TypeResolvers\InputObjectType\RootMenuPaginationInputObjectTypeResolver $rootMenuPaginationInputObjectTypeResolver
     */
    public final function setRootMenuPaginationInputObjectTypeResolver($rootMenuPaginationInputObjectTypeResolver) : void
    {
        $this->rootMenuPaginationInputObjectTypeResolver = $rootMenuPaginationInputObjectTypeResolver;
    }
    protected final function getRootMenuPaginationInputObjectTypeResolver() : RootMenuPaginationInputObjectTypeResolver
    {
        /** @var RootMenuPaginationInputObjectTypeResolver */
        return $this->rootMenuPaginationInputObjectTypeResolver = $this->rootMenuPaginationInputObjectTypeResolver ?? $this->instanceManager->getInstance(RootMenuPaginationInputObjectTypeResolver::class);
    }
    /**
     * @param \PoPCMSSchema\Menus\TypeResolvers\InputObjectType\MenuSortInputObjectTypeResolver $menuSortInputObjectTypeResolver
     */
    public final function setMenuSortInputObjectTypeResolver($menuSortInputObjectTypeResolver) : void
    {
        $this->menuSortInputObjectTypeResolver = $menuSortInputObjectTypeResolver;
    }
    protected final function getMenuSortInputObjectTypeResolver() : MenuSortInputObjectTypeResolver
    {
        /** @var MenuSortInputObjectTypeResolver */
        return $this->menuSortInputObjectTypeResolver = $this->menuSortInputObjectTypeResolver ?? $this->instanceManager->getInstance(MenuSortInputObjectTypeResolver::class);
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
        return ['menu', 'menus', 'menuCount'];
    }
    /**
     * @param \PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface $objectTypeResolver
     * @param string $fieldName
     */
    public function getFieldDescription($objectTypeResolver, $fieldName) : ?string
    {
        switch ($fieldName) {
            case 'menu':
                return $this->__('Get a menu', 'menus');
            case 'menus':
                return $this->__('Get all menus', 'menus');
            case 'menuCount':
                return $this->__('Count the number of menus', 'menus');
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
            case 'menu':
                return $this->getMenuObjectTypeResolver();
            case 'menus':
                return $this->getMenuObjectTypeResolver();
            case 'menuCount':
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
            case 'menus':
                return SchemaTypeModifiers::NON_NULLABLE | SchemaTypeModifiers::IS_ARRAY | SchemaTypeModifiers::IS_NON_NULLABLE_ITEMS_IN_ARRAY;
            case 'menuCount':
                return SchemaTypeModifiers::NON_NULLABLE;
            default:
                return parent::getFieldTypeModifiers($objectTypeResolver, $fieldName);
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
            case 'menu':
                return \array_merge($fieldArgNameTypeResolvers, ['by' => $this->getMenuByInputObjectTypeResolver()]);
            case 'menus':
                return \array_merge($fieldArgNameTypeResolvers, ['filter' => $this->getRootMenusFilterInputObjectTypeResolver(), 'pagination' => $this->getRootMenuPaginationInputObjectTypeResolver(), 'sort' => $this->getMenuSortInputObjectTypeResolver()]);
            case 'menuCount':
                return \array_merge($fieldArgNameTypeResolvers, ['filter' => $this->getRootMenusFilterInputObjectTypeResolver()]);
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
            case ['menu' => 'by']:
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
        switch ($fieldDataAccessor->getFieldName()) {
            case 'menu':
                $by = $fieldDataAccessor->getValue('by');
                if (isset($by->id)) {
                    // Validate the ID exists
                    $menuID = $by->id;
                    if ($this->getMenuTypeAPI()->getMenu($menuID) !== null) {
                        return $menuID;
                    }
                }
                return null;
        }
        $query = $this->convertFieldArgsToFilteringQueryArgs($objectTypeResolver, $fieldDataAccessor);
        switch ($fieldDataAccessor->getFieldName()) {
            case 'menus':
                return $this->getMenuTypeAPI()->getMenus($query, [QueryOptions::RETURN_TYPE => ReturnTypes::IDS]);
            case 'menuCount':
                return $this->getMenuTypeAPI()->getMenuCount($query);
        }
        return parent::resolveValue($objectTypeResolver, $object, $fieldDataAccessor, $objectTypeFieldResolutionFeedbackStore);
    }
}
