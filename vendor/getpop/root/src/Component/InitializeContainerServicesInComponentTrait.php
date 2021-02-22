<?php

declare (strict_types=1);
namespace PoP\Root\Component;

use PoP\Root\Container\ContainerBuilderFactory;
use PoP\Root\Container\SystemContainerBuilderFactory;
use PrefixedByPoP\Symfony\Component\Config\FileLocator;
use PrefixedByPoP\Symfony\Component\DependencyInjection\ContainerBuilder;
use PrefixedByPoP\Symfony\Component\DependencyInjection\Loader\PhpFileLoader;
use PrefixedByPoP\Symfony\Component\DependencyInjection\Loader\YamlFileLoader;
trait InitializeContainerServicesInComponentTrait
{
    /**
     * Initialize the services defiend in the YAML configuration file.
     * If not provided, use "services.yaml"
     *
     * @param string $componentDir
     * @param string $configPath
     * @param string $fileName
     * @return void
     */
    public static function initYAMLServices(string $componentDir, string $configPath = '', string $fileName = 'services.yaml') : void
    {
        // First check if the container has been cached. If so, do nothing
        if (!\PoP\Root\Container\ContainerBuilderFactory::isCached()) {
            // Initialize the ContainerBuilder with this component's service implementations
            /** @var ContainerBuilder */
            $containerBuilder = \PoP\Root\Container\ContainerBuilderFactory::getInstance();
            self::loadServicesFromYAMLConfigIntoContainer($containerBuilder, $componentDir, $configPath, $fileName);
        }
    }
    /**
     * Initialize the services defiend in the YAML configuration file.
     * If not provided, use "services.yaml"
     *
     * @param string $componentDir
     * @param string $configPath
     * @param string $fileName
     * @return void
     */
    protected static function loadServicesFromYAMLConfigIntoContainer(\PrefixedByPoP\Symfony\Component\DependencyInjection\ContainerBuilder $containerBuilder, string $componentDir, string $configPath = '', string $fileName = 'services.yaml') : void
    {
        $componentPath = self::getComponentPath($componentDir, $configPath);
        $loader = new \PrefixedByPoP\Symfony\Component\DependencyInjection\Loader\YamlFileLoader($containerBuilder, new \PrefixedByPoP\Symfony\Component\Config\FileLocator($componentPath));
        $loader->load($fileName);
    }
    /**
     * Initialize the services defiend in the YAML configuration file.
     * If not provided, use "services.yaml"
     *
     * @param string $componentDir
     * @param string $configPath
     * @param string $fileName
     * @return void
     */
    protected static function getComponentPath(string $componentDir, string $configPath = '') : string
    {
        return $componentDir . '/config' . ($configPath ? '/' . \trim($configPath, '/') : '');
    }
    /**
     * Initialize the services defiend in the PHP configuration file.
     * If not provided, use "services.yaml"
     *
     * @param string $componentDir
     * @param string $configPath
     * @param string $fileName
     * @return void
     */
    public static function initPHPServices(string $componentDir, string $configPath = '', string $fileName = 'services.php') : void
    {
        // First check if the container has been cached. If so, do nothing
        if (!\PoP\Root\Container\ContainerBuilderFactory::isCached()) {
            // Initialize the ContainerBuilder with this component's service implementations
            /** @var ContainerBuilder */
            $containerBuilder = \PoP\Root\Container\ContainerBuilderFactory::getInstance();
            $componentPath = self::getComponentPath($componentDir, $configPath);
            $loader = new \PrefixedByPoP\Symfony\Component\DependencyInjection\Loader\PhpFileLoader($containerBuilder, new \PrefixedByPoP\Symfony\Component\Config\FileLocator($componentPath));
            $loader->load($fileName);
        }
    }
    /**
     * If param `$skipSchema` is `true`, initialize the schema services defiend in the YAML configuration file.
     * If not provided, use "schema-services.yaml"
     *
     * @param string $componentDir
     * @param boolean $skipSchema
     * @param string $configPath
     * @param string $fileName
     * @return void
     */
    public static function maybeInitYAMLSchemaServices(string $componentDir, bool $skipSchema, string $configPath = '', string $fileName = 'schema-services.yaml') : void
    {
        if ($skipSchema) {
            return;
        }
        self::initYAMLServices($componentDir, $configPath, $fileName);
    }
    /**
     * Load services into the System Container
     *
     * @param string $componentDir
     * @param string $configPath
     * @param string $fileName
     * @return void
     */
    public static function initYAMLSystemContainerServices(string $componentDir, string $configPath = '', string $fileName = 'system-services.yaml') : void
    {
        // First check if the container has been cached. If so, do nothing
        if (!\PoP\Root\Container\SystemContainerBuilderFactory::isCached()) {
            // Initialize the ContainerBuilder with this component's service implementations
            /** @var ContainerBuilder */
            $containerBuilder = \PoP\Root\Container\SystemContainerBuilderFactory::getInstance();
            self::loadServicesFromYAMLConfigIntoContainer($containerBuilder, $componentDir, $configPath, $fileName);
        }
    }
    /**
     * If param `$skipSchema` is `true`, initialize the schema services defiend in the YAML configuration file.
     * If not provided, use "schema-services.yaml"
     *
     * @param string $componentDir
     * @param boolean $skipSchema
     * @param string $configPath
     * @param string $fileName
     * @return void
     */
    public static function maybeInitPHPSchemaServices(string $componentDir, bool $skipSchema, string $configPath = '', string $fileName = 'schema-services.php') : void
    {
        if ($skipSchema) {
            return;
        }
        self::initPHPServices($componentDir, $configPath, $fileName);
    }
}
