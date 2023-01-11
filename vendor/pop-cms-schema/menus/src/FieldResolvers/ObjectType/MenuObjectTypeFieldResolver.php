<?php

declare (strict_types=1);
namespace PoPCMSSchema\Menus\FieldResolvers\ObjectType;

use PoP\ComponentModel\TypeResolvers\InputTypeResolverInterface;
use PoP\ComponentModel\QueryResolution\FieldDataAccessorInterface;
use PoP\ComponentModel\Feedback\ObjectTypeFieldResolutionFeedbackStore;
use PoP\ComponentModel\FieldResolvers\ObjectType\AbstractObjectTypeFieldResolver;
use PoP\ComponentModel\Schema\SchemaTypeModifiers;
use PoP\ComponentModel\TypeResolvers\ConcreteTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\ScalarType\BooleanScalarTypeResolver;
use PoPCMSSchema\Menus\ObjectModels\MenuItem;
use PoPCMSSchema\Menus\RuntimeRegistries\MenuItemRuntimeRegistryInterface;
use PoPCMSSchema\Menus\TypeAPIs\MenuTypeAPIInterface;
use PoPCMSSchema\Menus\TypeResolvers\ObjectType\MenuItemObjectTypeResolver;
use PoPCMSSchema\Menus\TypeResolvers\ObjectType\MenuObjectTypeResolver;
use PoP\Engine\TypeResolvers\ScalarType\JSONObjectScalarTypeResolver;
class MenuObjectTypeFieldResolver extends AbstractObjectTypeFieldResolver
{
    /**
     * @var \PoPCMSSchema\Menus\RuntimeRegistries\MenuItemRuntimeRegistryInterface|null
     */
    private $menuItemRuntimeRegistry;
    /**
     * @var \PoP\Engine\TypeResolvers\ScalarType\JSONObjectScalarTypeResolver|null
     */
    private $jsonObjectScalarTypeResolver;
    /**
     * @var \PoPCMSSchema\Menus\TypeResolvers\ObjectType\MenuItemObjectTypeResolver|null
     */
    private $menuItemObjectTypeResolver;
    /**
     * @var \PoPCMSSchema\Menus\TypeAPIs\MenuTypeAPIInterface|null
     */
    private $menuTypeAPI;
    /**
     * @var \PoP\ComponentModel\TypeResolvers\ScalarType\BooleanScalarTypeResolver|null
     */
    private $booleanScalarTypeResolver;
    /**
     * @param \PoPCMSSchema\Menus\RuntimeRegistries\MenuItemRuntimeRegistryInterface $menuItemRuntimeRegistry
     */
    public final function setMenuItemRuntimeRegistry($menuItemRuntimeRegistry) : void
    {
        $this->menuItemRuntimeRegistry = $menuItemRuntimeRegistry;
    }
    protected final function getMenuItemRuntimeRegistry() : MenuItemRuntimeRegistryInterface
    {
        /** @var MenuItemRuntimeRegistryInterface */
        return $this->menuItemRuntimeRegistry = $this->menuItemRuntimeRegistry ?? $this->instanceManager->getInstance(MenuItemRuntimeRegistryInterface::class);
    }
    /**
     * @param \PoP\Engine\TypeResolvers\ScalarType\JSONObjectScalarTypeResolver $jsonObjectScalarTypeResolver
     */
    public final function setJSONObjectScalarTypeResolver($jsonObjectScalarTypeResolver) : void
    {
        $this->jsonObjectScalarTypeResolver = $jsonObjectScalarTypeResolver;
    }
    protected final function getJSONObjectScalarTypeResolver() : JSONObjectScalarTypeResolver
    {
        /** @var JSONObjectScalarTypeResolver */
        return $this->jsonObjectScalarTypeResolver = $this->jsonObjectScalarTypeResolver ?? $this->instanceManager->getInstance(JSONObjectScalarTypeResolver::class);
    }
    /**
     * @param \PoPCMSSchema\Menus\TypeResolvers\ObjectType\MenuItemObjectTypeResolver $menuItemObjectTypeResolver
     */
    public final function setMenuItemObjectTypeResolver($menuItemObjectTypeResolver) : void
    {
        $this->menuItemObjectTypeResolver = $menuItemObjectTypeResolver;
    }
    protected final function getMenuItemObjectTypeResolver() : MenuItemObjectTypeResolver
    {
        /** @var MenuItemObjectTypeResolver */
        return $this->menuItemObjectTypeResolver = $this->menuItemObjectTypeResolver ?? $this->instanceManager->getInstance(MenuItemObjectTypeResolver::class);
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
     * @param \PoP\ComponentModel\TypeResolvers\ScalarType\BooleanScalarTypeResolver $booleanScalarTypeResolver
     */
    public final function setBooleanScalarTypeResolver($booleanScalarTypeResolver) : void
    {
        $this->booleanScalarTypeResolver = $booleanScalarTypeResolver;
    }
    protected final function getBooleanScalarTypeResolver() : BooleanScalarTypeResolver
    {
        /** @var BooleanScalarTypeResolver */
        return $this->booleanScalarTypeResolver = $this->booleanScalarTypeResolver ?? $this->instanceManager->getInstance(BooleanScalarTypeResolver::class);
    }
    /**
     * @return array<class-string<ObjectTypeResolverInterface>>
     */
    public function getObjectTypeResolverClassesToAttachTo() : array
    {
        return [MenuObjectTypeResolver::class];
    }
    /**
     * @return string[]
     */
    public function getFieldNamesToResolve() : array
    {
        return ['items', 'itemDataEntries'];
    }
    /**
     * @param \PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface $objectTypeResolver
     * @param string $fieldName
     */
    public function getFieldTypeResolver($objectTypeResolver, $fieldName) : ConcreteTypeResolverInterface
    {
        switch ($fieldName) {
            case 'items':
                return $this->getMenuItemObjectTypeResolver();
            case 'itemDataEntries':
                return $this->getJSONObjectScalarTypeResolver();
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
            case 'items':
            case 'itemDataEntries':
                return SchemaTypeModifiers::NON_NULLABLE | SchemaTypeModifiers::IS_ARRAY | SchemaTypeModifiers::IS_NON_NULLABLE_ITEMS_IN_ARRAY;
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
        switch ($fieldName) {
            case 'itemDataEntries':
                return ['flat' => $this->getBooleanScalarTypeResolver()];
            default:
                return parent::getFieldArgNameTypeResolvers($objectTypeResolver, $fieldName);
        }
    }
    /**
     * @param \PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface $objectTypeResolver
     * @param string $fieldName
     * @param string $fieldArgName
     */
    public function getFieldArgDescription($objectTypeResolver, $fieldName, $fieldArgName) : ?string
    {
        switch ([$fieldName => $fieldArgName]) {
            case ['itemDataEntries' => 'flat']:
                return $this->__('Flatten the items', 'menus');
            default:
                return parent::getFieldArgDescription($objectTypeResolver, $fieldName, $fieldArgName);
        }
    }
    /**
     * @param \PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface $objectTypeResolver
     * @param string $fieldName
     */
    public function getFieldDescription($objectTypeResolver, $fieldName) : ?string
    {
        switch ($fieldName) {
            case 'items':
                return $this->__('The menu items', 'menus');
            case 'itemDataEntries':
                return $this->__('The data for the menu items', 'menus');
            default:
                return parent::getFieldDescription($objectTypeResolver, $fieldName);
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
            case ['itemDataEntries' => 'flat']:
                return SchemaTypeModifiers::NON_NULLABLE;
            default:
                return parent::getFieldArgTypeModifiers($objectTypeResolver, $fieldName, $fieldArgName);
        }
    }
    /**
     * @return mixed
     * @param \PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface $objectTypeResolver
     * @param string $fieldName
     * @param string $fieldArgName
     */
    public function getFieldArgDefaultValue($objectTypeResolver, $fieldName, $fieldArgName)
    {
        switch ([$fieldName => $fieldArgName]) {
            case ['itemDataEntries' => 'flat']:
                return \false;
            default:
                return parent::getFieldArgDefaultValue($objectTypeResolver, $fieldName, $fieldArgName);
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
        $menu = $object;
        switch ($fieldDataAccessor->getFieldName()) {
            case 'itemDataEntries':
                $isFlat = $fieldDataAccessor->getValue('flat');
                $menuItems = $this->getMenuTypeAPI()->getMenuItems($menu);
                $entries = array();
                if ($menuItems) {
                    foreach ($menuItems as $menuItem) {
                        // Convert object to array
                        // @see https://stackoverflow.com/a/18576902
                        $item_value = \json_decode((string) \json_encode($menuItem), \true);
                        // Prepare array where to append the children items
                        if (!$isFlat) {
                            $item_value['children'] = [];
                        }
                        $entries[] = $item_value;
                    }
                }
                if ($isFlat) {
                    return \array_map(
                        /** @param mixed[] $entry */
                        function (array $entry) {
                            return (object) $entry;
                        },
                        $entries
                    );
                }
                /**
                 * Reproduce the menu layout in the array
                 */
                $arrangedEntries = [];
                foreach ($entries as $menuItemData) {
                    $arrangedEntriesPointer =& $arrangedEntries;
                    // Reproduce the list of parents
                    if ($menuItemParentID = $menuItemData['parentID']) {
                        $menuItemAncestorIDs = [];
                        while ($menuItemParentID !== null) {
                            $menuItemAncestorIDs[] = $menuItemParentID;
                            $menuItemParentPos = $this->findEntryPosition($menuItemParentID, $entries);
                            $menuItemParentID = $entries[$menuItemParentPos]['parentID'];
                        }
                        // Navigate to that position, and attach the menuItem
                        foreach (\array_reverse($menuItemAncestorIDs) as $menuItemAncestorID) {
                            $menuItemAncestorPos = $this->findEntryPosition($menuItemAncestorID, $arrangedEntriesPointer);
                            $arrangedEntriesPointer =& $arrangedEntriesPointer[$menuItemAncestorPos]['children'];
                        }
                    }
                    $arrangedEntriesPointer[] = $menuItemData;
                }
                return \array_map(
                    /** @param mixed[] $entry */
                    function (array $entry) {
                        return (object) $entry;
                    },
                    $arrangedEntries
                );
            case 'items':
                $menuItems = $this->getMenuTypeAPI()->getMenuItems($menu);
                $menuItemRuntimeRegistry = $this->getMenuItemRuntimeRegistry();
                // Save the MenuItems on the dynamic registry
                foreach ($menuItems as $menuItem) {
                    $menuItemRuntimeRegistry->storeMenuItem($menuItem);
                }
                // Return the IDs for the top-level items (those with no parent)
                return \array_map(function (MenuItem $menuItem) {
                    return $menuItem->id;
                }, \array_filter($menuItems, function (MenuItem $menuItem) {
                    return $menuItem->parentID === null;
                }));
        }
        return parent::resolveValue($objectTypeResolver, $object, $fieldDataAccessor, $objectTypeFieldResolutionFeedbackStore);
    }
    /**
     * @param array<int,array<string,mixed>> $entries
     * @param string|int $menuItemID
     */
    protected function findEntryPosition($menuItemID, $entries) : int
    {
        $entriesCount = \count($entries);
        for ($pos = 0; $pos < $entriesCount; $pos++) {
            /**
             * Watch out! Can't use `===` because (for some reason) the same value
             * could be passed as int or string!
             */
            if ($entries[$pos]['id'] === $menuItemID) {
                return $pos;
            }
        }
        // It will never reach here, so return anything
        return 0;
    }
}
