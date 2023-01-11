<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI;

use GraphQLAPI\GraphQLAPI\PluginManagement\ExtensionManager;
use GraphQLAPI\GraphQLAPI\PluginManagement\MainPluginManager;
use GraphQLAPI\GraphQLAPI\PluginSkeleton\ExtensionInterface;
use GraphQLAPI\GraphQLAPI\PluginSkeleton\MainPluginInterface;
use PoP\ComponentModel\AppInterface as UpstreamAppInterface;

interface AppInterface extends UpstreamAppInterface
{
    /**
     * @param \GraphQLAPI\GraphQLAPI\PluginManagement\MainPluginManager|null $mainPluginManager
     */
    public static function initializePlugin($mainPluginManager = null): void;

    public static function getMainPluginManager(): MainPluginManager;
    public static function getExtensionManager(): ExtensionManager;

    /**
     * Shortcut function.
     */
    public static function getMainPlugin(): MainPluginInterface;

    /**
     * Shortcut function.
     * @param string $extensionClass
     */
    public static function getExtension($extensionClass): ExtensionInterface;
}
