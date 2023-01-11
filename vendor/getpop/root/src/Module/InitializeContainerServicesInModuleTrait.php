<?php

declare (strict_types=1);
namespace PoP\Root\Module;

use PoP\Root\App;
use PoP\Root\Container\Loader\SchemaServiceYamlFileLoader;
use PoP\Root\Container\Loader\ServiceYamlFileLoader;
use PrefixedByPoP\Symfony\Component\Config\FileLocator;
use PrefixedByPoP\Symfony\Component\DependencyInjection\ContainerBuilder;
trait InitializeContainerServicesInModuleTrait
{
    /**
     * Initialize the services defined in the YAML configuration file.
     * If not provided, use "services.yaml"
     * @param string $moduleDir
     * @param string $configPath
     * @param string $fileName
     */
    public function initServices($moduleDir, $configPath = '', $fileName = 'services.yaml') : void
    {
        // First check if the container has been cached. If so, do nothing
        if (!App::getContainerBuilderFactory()->isCached()) {
            // Initialize the ContainerBuilder with this module's service implementations
            /** @var ContainerBuilder */
            $containerBuilder = App::getContainer();
            $this->loadServicesFromYAMLConfigIntoContainer($containerBuilder, $moduleDir, $configPath, $fileName);
        }
    }
    /**
     * Initialize the services defined in the YAML configuration file.
     * If not provided, use "services.yaml"
     * @param \Symfony\Component\DependencyInjection\ContainerBuilder $containerBuilder
     * @param string $moduleDir
     * @param string $configPath
     * @param string $fileName
     */
    protected function loadServicesFromYAMLConfigIntoContainer($containerBuilder, $moduleDir, $configPath, $fileName) : void
    {
        $modulePath = $this->getModulePath($moduleDir, $configPath);
        $loader = new ServiceYamlFileLoader($containerBuilder, new FileLocator($modulePath));
        $loader->load($fileName);
    }
    /**
     * Initialize the services defined in the YAML configuration file.
     * If not provided, use "services.yaml"
     * @param string $moduleDir
     * @param string $configPath
     */
    protected function getModulePath($moduleDir, $configPath = '') : string
    {
        return $moduleDir . '/config' . ($configPath ? '/' . \trim($configPath, '/') : '');
    }
    /**
     * If param `$skipSchema` is `true`, define the schema services
     * in the container, but do not initialize them.
     * If file name provided, use "schema-services.yaml"
     * @param string $moduleDir
     * @param bool $skipSchema
     * @param string $configPath
     * @param string $fileName
     */
    public function initSchemaServices($moduleDir, $skipSchema, $configPath = '', $fileName = 'schema-services.yaml') : void
    {
        if (!App::getContainerBuilderFactory()->isCached()) {
            /** @var ContainerBuilder */
            $containerBuilder = App::getContainer();
            $modulePath = $this->getModulePath($moduleDir, $configPath);
            $autoconfigure = !$skipSchema;
            $loader = new SchemaServiceYamlFileLoader($containerBuilder, new FileLocator($modulePath), $autoconfigure);
            $loader->load($fileName);
        }
    }
    /**
     * Load services into the System Container
     * @param string $moduleDir
     * @param string $configPath
     * @param string $fileName
     */
    public function initSystemServices($moduleDir, $configPath = '', $fileName = 'system-services.yaml') : void
    {
        // First check if the container has been cached. If so, do nothing
        if (!App::getSystemContainerBuilderFactory()->isCached()) {
            // Initialize the ContainerBuilder with this module's service implementations
            /** @var ContainerBuilder */
            $containerBuilder = App::getSystemContainer();
            $this->loadServicesFromYAMLConfigIntoContainer($containerBuilder, $moduleDir, $configPath, $fileName);
        }
    }
}
