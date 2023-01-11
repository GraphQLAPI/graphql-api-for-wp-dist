<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\Services\MenuPageAttachers;

use GraphQLAPI\GraphQLAPI\Services\Menus\MenuInterface;
use GraphQLAPI\GraphQLAPI\Services\Menus\PluginMenu;

/**
 * Admin menu class
 */
abstract class AbstractPluginMenuPageAttacher extends AbstractMenuPageAttacher
{
    /**
     * @var \GraphQLAPI\GraphQLAPI\Services\Menus\PluginMenu|null
     */
    private $pluginMenu;

    /**
     * @param \GraphQLAPI\GraphQLAPI\Services\Menus\PluginMenu $pluginMenu
     */
    final public function setPluginMenu($pluginMenu): void
    {
        $this->pluginMenu = $pluginMenu;
    }
    final protected function getPluginMenu(): PluginMenu
    {
        /** @var PluginMenu */
        return $this->pluginMenu = $this->pluginMenu ?? $this->instanceManager->getInstance(PluginMenu::class);
    }

    public function getMenu(): MenuInterface
    {
        return $this->getPluginMenu();
    }
}
