<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\Services\MenuPages;

use GraphQLAPI\GraphQLAPI\Services\Menus\MenuInterface;
use GraphQLAPI\GraphQLAPI\Services\Menus\PluginMenu;

/**
 * Main plugin menu page
 */
abstract class AbstractPluginMenuPage extends AbstractMenuPage
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
