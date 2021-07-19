<?php

declare(strict_types=1);

namespace PoPSchema\MenusWP\TypeAPIs;

use PoPSchema\Menus\TypeAPIs\MenuTypeAPIInterface;
use WP_Term;

class MenuTypeAPI implements MenuTypeAPIInterface
{
    /**
     * @param string|int $menuID
     * @return object|null
     */
    public function getMenu($menuID)
    {
        $object = wp_get_nav_menu_object($menuID);
        // If the object is not found, it returns `false`. Return `null` instead
        if ($object === false) {
            return null;
        }
        return $object;
    }
    /**
     * @param string|int|object $menuObjectOrID
     */
    public function getMenuItemsData($menuObjectOrID): array
    {
        return wp_get_nav_menu_items($menuObjectOrID);
    }

    /**
     * @return string|int
     * @param object $menu
     */
    public function getMenuID($menu)
    {
        return $menu->term_id;
    }

    /**
     * @return string|int|null
     */
    public function getMenuIDFromMenuName(string $menuName)
    {
        if ($menuObject = $this->getMenuObject($menuName)) {
            return $menuObject->term_id;
        }
        return null;
    }

    protected function getMenuObject(string $menuName): ?WP_Term
    {
        $locations = get_nav_menu_locations();
        $menuID = $locations[$menuName];
        return $this->getMenu($menuID);
    }
}
