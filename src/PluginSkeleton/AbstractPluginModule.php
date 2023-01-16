<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\PluginSkeleton;

use PoP\Root\Module\ModuleInterface;
use PoP\Root\Module\AbstractModule;

abstract class AbstractPluginModule extends AbstractModule implements PluginModuleInterface
{
    /**
     * @var string|null
     */
    private $pluginFolder;

    /**
     * @param string $pluginFolder
     */
    public function setPluginFolder($pluginFolder): void
    {
        $this->pluginFolder = $pluginFolder;
    }

    public function getPluginFolder(): ?string
    {
        return $this->pluginFolder;
    }

    /**
     * Initialize services for the system container.
     * It uses Convention over Configuration: if the requested files exist,
     * load them.
     */
    protected function initializeSystemContainerServices(): void
    {
        $pluginFolder = $this->getPluginFolder();
        if ($pluginFolder === null) {
            return;
        }
        if (file_exists($pluginFolder . \DIRECTORY_SEPARATOR . 'config' . \DIRECTORY_SEPARATOR . 'hybrid-services.yaml')) {
            $this->initSystemServices($pluginFolder, '', 'hybrid-services.yaml');
        }
        /**
         * ModuleResolvers are also hybrid, but they are defined on a different config
         * to make it easier to understand (for documentation)
         */
        if (file_exists($pluginFolder . \DIRECTORY_SEPARATOR . 'config' . \DIRECTORY_SEPARATOR . 'module-services.yaml')) {
            $this->initSystemServices($pluginFolder, '', 'module-services.yaml');
        }
        if (file_exists($pluginFolder . \DIRECTORY_SEPARATOR . 'config' . \DIRECTORY_SEPARATOR . 'system-services.yaml')) {
            $this->initSystemServices($pluginFolder);
        }
    }

    /**
     * Initialize services
     * It uses Convention over Configuration: if the requested files exist,
     * load them.
     *
     * @param array<class-string<ModuleInterface>> $skipSchemaModuleClasses
     * @param bool $skipSchema
     */
    protected function initializeContainerServices($skipSchema, $skipSchemaModuleClasses): void
    {
        $pluginFolder = $this->getPluginFolder();
        if ($pluginFolder === null) {
            return;
        }
        if (file_exists($pluginFolder . \DIRECTORY_SEPARATOR . 'config' . \DIRECTORY_SEPARATOR . 'hybrid-services.yaml')) {
            $this->initServices($pluginFolder, '', 'hybrid-services.yaml');
        }
        /**
         * ModuleResolvers are also hybrid, but they are defined on a different config
         * to make it easier to understand (for documentation)
         */
        if (file_exists($pluginFolder . \DIRECTORY_SEPARATOR . 'config' . \DIRECTORY_SEPARATOR . 'module-services.yaml')) {
            $this->initServices($pluginFolder, '', 'module-services.yaml');
        }
        if (file_exists($pluginFolder . \DIRECTORY_SEPARATOR . 'config' . \DIRECTORY_SEPARATOR . 'services.yaml')) {
            $this->initServices($pluginFolder);
        }
        if (file_exists($pluginFolder . \DIRECTORY_SEPARATOR . 'config' . \DIRECTORY_SEPARATOR . 'schema-services.yaml')) {
            $this->initSchemaServices($pluginFolder, $skipSchema);
        }
    }
}