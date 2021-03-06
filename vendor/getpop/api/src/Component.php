<?php

declare (strict_types=1);
namespace PoP\API;

use PoP\API\Configuration\Request;
use PoP\Root\Component\AbstractComponent;
use PoP\Root\Component\CanDisableComponentTrait;
use PoP\AccessControl\ComponentConfiguration as AccessControlComponentConfiguration;
/**
 * Initialize component
 */
class Component extends \PoP\Root\Component\AbstractComponent
{
    use CanDisableComponentTrait;
    /**
     * Classes from PoP components that must be initialized before this component
     *
     * @return string[]
     */
    public static function getDependedComponentClasses() : array
    {
        return [\PoP\Engine\Component::class];
    }
    /**
     * All conditional component classes that this component depends upon, to initialize them
     *
     * @return array
     */
    public static function getDependedConditionalComponentClasses() : array
    {
        return [\PoP\AccessControl\Component::class, \PoP\CacheControl\Component::class];
    }
    public static function getDependedMigrationPlugins() : array
    {
        $packageName = \basename(\dirname(__DIR__));
        $folder = \dirname(__DIR__, 2);
        return [$folder . '/migrate-' . $packageName . '/initialize.php'];
    }
    /**
     * Set the default component configuration
     *
     * @param array<string, mixed> $componentClassConfiguration
     */
    public static function customizeComponentClassConfiguration(array &$componentClassConfiguration) : void
    {
        // If passing ?use_namespace=1, set it on the configuration
        if (\PoP\API\Environment::enableSettingNamespacingByURLParam()) {
            $useNamespacing = \PoP\API\Configuration\Request::namespaceTypesAndInterfaces();
            if ($useNamespacing !== null) {
                $componentClassConfiguration[\PoP\ComponentModel\Component::class][\PoP\ComponentModel\Environment::NAMESPACE_TYPES_AND_INTERFACES] = $useNamespacing;
            }
        }
    }
    /**
     * Initialize services
     *
     * @param array<string, mixed> $configuration
     * @param string[] $skipSchemaComponentClasses
     */
    protected static function initializeContainerServices(array $configuration = [], bool $skipSchema = \false, array $skipSchemaComponentClasses = []) : void
    {
        if (self::isEnabled()) {
            parent::initializeContainerServices($configuration, $skipSchema, $skipSchemaComponentClasses);
            \PoP\API\ComponentConfiguration::setConfiguration($configuration);
            self::initYAMLServices(\dirname(__DIR__));
            self::maybeInitYAMLSchemaServices(\dirname(__DIR__), $skipSchema);
            // Conditional packages
            if (\class_exists('\\PoP\\AccessControl\\Component')) {
                self::initYAMLServices(\dirname(__DIR__), '/Conditional/AccessControl');
            }
            if (\class_exists('\\PoP\\CacheControl\\Component') && !\in_array(\PoP\CacheControl\Component::class, $skipSchemaComponentClasses) && \class_exists('\\PoP\\AccessControl\\Component') && !\in_array(\PoP\AccessControl\Component::class, $skipSchemaComponentClasses) && \PoP\AccessControl\ComponentConfiguration::canSchemaBePrivate()) {
                self::maybeInitPHPSchemaServices(\dirname(__DIR__), $skipSchema, '/Conditional/CacheControl/Conditional/AccessControl/ConditionalOnEnvironment/PrivateSchema');
            }
        }
    }
    protected static function resolveEnabled()
    {
        return !\PoP\API\Environment::disableAPI();
    }
}
