<?php

declare(strict_types=1);

namespace PoPSchema\MenusWP\TypeAPIs;

use PoPSchema\Menus\TypeAPIs\MenuItemTypeAPIInterface;
use WP_Post;

class MenuItemTypeAPI implements MenuItemTypeAPIInterface
{
    /**
     * MenuItem is the CPT 'nav_menu_item'
     * @see https://developer.wordpress.org/reference/functions/wp_get_nav_menu_items/#source
     * @param string|int $id
     * @return object|null
     */
    public function getMenuItem($id)
    {
        /** @var WP_Post|null */
        return get_post($id, ARRAY_A);
    }
    /**
     * @return string|int
     * @param object $menuItem
     */
    public function getMenuItemID($menuItem)
    {
        return $menuItem->ID;
    }
    /**
     * @param object $menuItem
     */
    public function getMenuItemTitle($menuItem): string
    {
        return apply_filters('the_title', $menuItem->title, $menuItem->object_id);
    }
    /**
     * @return string|int
     * @param object $menuItem
     */
    public function getMenuItemObjectID($menuItem)
    {
        return $menuItem->object_id;
    }
    /**
     * @param object $menuItem
     */
    public function getMenuItemURL($menuItem): string
    {
        return $menuItem->url;
    }
    /**
     * @return string[]
     * @param object $menuItem
     */
    public function getMenuItemClasses($menuItem): array
    {
        return $menuItem->classes;
    }
    /**
     * @return string|int|null
     * @param object $menuItem
     */
    public function getMenuItemParentID($menuItem)
    {
        /**
         * If it has no parent, it has ID "0"
         */
        if ($menuItem->menu_item_parent === "0") {
            return null;
        }
        return $menuItem->menu_item_parent;
    }
    /**
     * @param object $menuItem
     */
    public function getMenuItemTarget($menuItem): string
    {
        return $menuItem->target;
    }
    /**
     * @param object $menuItem
     */
    public function getMenuItemDescription($menuItem): string
    {
        return $menuItem->description;
    }
}
