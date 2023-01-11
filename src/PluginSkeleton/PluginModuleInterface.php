<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\PluginSkeleton;

use PoP\Root\Module\ModuleInterface;

interface PluginModuleInterface extends ModuleInterface
{
    /**
     * @param string $pluginFolder
     */
    public function setPluginFolder($pluginFolder): void;
    public function getPluginFolder(): ?string;
}
