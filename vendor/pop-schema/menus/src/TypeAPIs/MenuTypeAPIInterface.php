<?php

declare (strict_types=1);
namespace PoPSchema\Menus\TypeAPIs;

/**
 * Methods to interact with the Type, to be implemented by the underlying CMS
 */
interface MenuTypeAPIInterface
{
    /**
     * @param string|int $menuID
     * @return object|null
     */
    public function getMenu($menuID);
    /**
     * @param string|int|object $menuObjectOrID
     */
    public function getMenuItemsData($menuObjectOrID) : array;
    /**
     * @return string|int
     * @param object $menu
     */
    public function getMenuID($menu);
    /**
     * @return string|int|null
     */
    public function getMenuIDFromMenuName(string $menuName);
}
