<?php

declare (strict_types=1);
namespace PoPCMSSchema\Menus\RuntimeRegistries;

use PoPCMSSchema\Menus\ObjectModels\MenuItem;
class MenuItemRuntimeRegistry implements \PoPCMSSchema\Menus\RuntimeRegistries\MenuItemRuntimeRegistryInterface
{
    /** @var array<string|int,MenuItem> */
    protected $menuItems = [];
    /** @var array<string|int,array<string|int,MenuItem>> */
    protected $menuItemsByParent = [];
    /**
     * @param \PoPCMSSchema\Menus\ObjectModels\MenuItem $menuItem
     */
    public function storeMenuItem($menuItem) : void
    {
        $this->menuItems[$menuItem->id] = $menuItem;
        // Only store MenuItems which have a parent
        // Those who do not have already been accessed in the Menu's "items" field
        if ($menuItem->parentID !== null) {
            $this->menuItemsByParent[$menuItem->parentID][$menuItem->id] = $menuItem;
        }
    }
    /**
     * @param string|int $id
     */
    public function getMenuItem($id) : ?MenuItem
    {
        return $this->menuItems[$id] ?? null;
    }
    /** @return array<string|int,MenuItem>
     * @param string|int|\PoPCMSSchema\Menus\ObjectModels\MenuItem $menuItemObjectOrID */
    public function getMenuItemChildren($menuItemObjectOrID) : array
    {
        if ($menuItemObjectOrID instanceof MenuItem) {
            $menuItemID = $menuItemObjectOrID->id;
        } else {
            $menuItemID = $menuItemObjectOrID;
        }
        return $this->menuItemsByParent[$menuItemID] ?? [];
    }
}
