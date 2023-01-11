<?php

declare (strict_types=1);
namespace PoPCMSSchema\Menus\TypeAPIs;

use PoPCMSSchema\Menus\ObjectModels\MenuItem;
/**
 * Methods to interact with the Type, to be implemented by the underlying CMS
 */
interface MenuTypeAPIInterface
{
    /**
     * @param string|int $menuID
     */
    public function getMenu($menuID);
    /**
     * @return MenuItem[]
     * @param string|int|object $menuObjectOrID
     */
    public function getMenuItems($menuObjectOrID) : array;
    /**
     * @return string|int
     * @param object $menu
     */
    public function getMenuID($menu);
    /**
     * @return string|int|null
     * @param string $menuName
     */
    public function getMenuIDFromMenuName($menuName);
    /**
     * @param array<string,mixed> $query
     * @param array<string,mixed> $options
     * @return array<string|int|object>
     */
    public function getMenus($query, $options = []) : array;
    /**
     * @param array<string,mixed> $query
     * @param array<string,mixed> $options
     */
    public function getMenuCount($query, $options = []) : int;
}
