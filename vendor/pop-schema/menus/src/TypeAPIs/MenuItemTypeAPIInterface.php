<?php

declare (strict_types=1);
namespace PoPSchema\Menus\TypeAPIs;

interface MenuItemTypeAPIInterface
{
    /**
     * @param string|int $id
     * @return object|null
     */
    public function getMenuItem($id);
    /**
     * @return string|int
     * @param object $menuItem
     */
    public function getMenuItemID($menuItem);
    /**
     * @param object $menuItem
     */
    public function getMenuItemTitle($menuItem) : string;
    /**
     * @return string|int
     * @param object $menuItem
     */
    public function getMenuItemObjectID($menuItem);
    /**
     * @param object $menuItem
     */
    public function getMenuItemURL($menuItem) : string;
    /**
     * @return string[]
     * @param object $menuItem
     */
    public function getMenuItemClasses($menuItem) : array;
    /**
     * @return string|int|null
     * @param object $menuItem
     */
    public function getMenuItemParentID($menuItem);
    /**
     * @param object $menuItem
     */
    public function getMenuItemTarget($menuItem) : string;
    /**
     * @param object $menuItem
     */
    public function getMenuItemDescription($menuItem) : string;
}
