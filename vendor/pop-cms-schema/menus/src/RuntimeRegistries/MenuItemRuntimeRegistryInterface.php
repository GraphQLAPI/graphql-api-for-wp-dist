<?php

declare (strict_types=1);
namespace PoPCMSSchema\Menus\RuntimeRegistries;

use PoPCMSSchema\Menus\ObjectModels\MenuItem;
interface MenuItemRuntimeRegistryInterface
{
    /**
     * @param \PoPCMSSchema\Menus\ObjectModels\MenuItem $menuItem
     */
    public function storeMenuItem($menuItem) : void;
    /**
     * @param string|int $id
     */
    public function getMenuItem($id) : ?MenuItem;
    /** @return array<string|int,MenuItem>
     * @param string|int|\PoPCMSSchema\Menus\ObjectModels\MenuItem $menuItemObjectOrID */
    public function getMenuItemChildren($menuItemObjectOrID) : array;
}
