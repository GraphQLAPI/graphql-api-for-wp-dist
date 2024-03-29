<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\PluginManagement;

use GraphQLAPI\GraphQLAPI\Exception\MainPluginNotRegisteredException;
use GraphQLAPI\GraphQLAPI\PluginSkeleton\MainPluginInterface;

class MainPluginManager extends AbstractPluginManager
{
    /**
     * @var \GraphQLAPI\GraphQLAPI\PluginSkeleton\MainPluginInterface|null
     */
    private $mainPlugin;

    /**
     * @param \GraphQLAPI\GraphQLAPI\PluginSkeleton\MainPluginInterface $mainPlugin
     */
    public function register($mainPlugin): MainPluginInterface
    {
        $this->mainPlugin = $mainPlugin;
        return $mainPlugin;
    }

    /**
     * Validate that the plugin is not registered yet.
     * If it is, print an error and return false
     * @param string $pluginVersion
     */
    public function assertIsValid(
        $pluginVersion
    ): bool {
        if ($this->mainPlugin !== null) {
            $this->printAdminNoticeErrorMessage(
                sprintf(__('Plugin <strong>%s</strong> is already installed with version <code>%s</code>, so version <code>%s</code> has not been loaded. Please deactivate all versions, remove the older version, and activate again the latest version of the plugin.', 'graphql-api'), $this->mainPlugin->getPluginName(), $this->mainPlugin->getPluginVersion(), $pluginVersion)
            );
            return false;
        }

        return true;
    }

    public function getPlugin(): MainPluginInterface
    {
        if ($this->mainPlugin === null) {
            throw new MainPluginNotRegisteredException(
                __('The main plugin has not been registered yet', 'graphql-api')
            );
        }
        return $this->mainPlugin;
    }
}
