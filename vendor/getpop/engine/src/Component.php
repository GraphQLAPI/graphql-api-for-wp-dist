<?php

declare (strict_types=1);
namespace PoP\Engine;

use PoP\Root\Component\AbstractComponent;
use PoP\Root\Component\YAMLServicesTrait;
use PoP\Engine\Config\ServiceConfiguration;
use PoP\ComponentModel\Container\ContainerBuilderUtils;
use PoP\ComponentModel\ComponentConfiguration as ComponentModelComponentConfiguration;
/**
 * Initialize component
 */
class Component extends \PoP\Root\Component\AbstractComponent
{
    use YAMLServicesTrait;
    // const VERSION = '0.1.0';
    /**
     * Classes from PoP components that must be initialized before this component
     *
     * @return string[]
     */
    public static function getDependedComponentClasses() : array
    {
        return [\PoP\Hooks\Component::class, \PoP\Translation\Component::class, \PoP\LooseContracts\Component::class, \PoP\Routing\Component::class, \PoP\ModuleRouting\Component::class, \PoP\ComponentModel\Component::class, \PoP\CacheControl\Component::class, \PoP\GuzzleHelpers\Component::class];
    }
    public static function getDependedMigrationPlugins() : array
    {
        $packageName = \basename(\dirname(__DIR__));
        $folder = \dirname(__DIR__, 2);
        return [$folder . '/migrate-' . $packageName . '/initialize.php'];
    }
    /**
     * Initialize services
     *
     * @param array<string, mixed> $configuration
     * @param string[] $skipSchemaComponentClasses
     */
    protected static function doInitialize(array $configuration = [], bool $skipSchema = \false, array $skipSchemaComponentClasses = []) : void
    {
        parent::doInitialize($configuration, $skipSchema, $skipSchemaComponentClasses);
        \PoP\Engine\ComponentConfiguration::setConfiguration($configuration);
        self::initYAMLServices(\dirname(__DIR__));
        self::maybeInitYAMLSchemaServices(\dirname(__DIR__), $skipSchema);
        \PoP\Engine\Config\ServiceConfiguration::initialize();
    }
    /**
     * Boot component
     *
     * @return void
     */
    public static function beforeBoot() : void
    {
        parent::beforeBoot();
        // Initialize classes
        \PoP\ComponentModel\Container\ContainerBuilderUtils::registerTypeResolversFromNamespace(__NAMESPACE__ . '\\TypeResolvers');
        \PoP\ComponentModel\Container\ContainerBuilderUtils::instantiateNamespaceServices(__NAMESPACE__ . '\\Hooks');
        \PoP\ComponentModel\Container\ContainerBuilderUtils::attachFieldResolversFromNamespace(__NAMESPACE__ . '\\FieldResolvers', \false);
        \PoP\ComponentModel\Container\ContainerBuilderUtils::attachAndRegisterDirectiveResolversFromNamespace(__NAMESPACE__ . '\\DirectiveResolvers', \false);
        if (!\PoP\Engine\Environment::disableGuzzleOperators()) {
            \PoP\ComponentModel\Container\ContainerBuilderUtils::attachFieldResolversFromNamespace(__NAMESPACE__ . '\\FieldResolvers\\Guzzle', \false);
            \PoP\ComponentModel\Container\ContainerBuilderUtils::attachAndRegisterDirectiveResolversFromNamespace(__NAMESPACE__ . '\\DirectiveResolvers\\Guzzle');
        }
        if (\PoP\ComponentModel\ComponentConfiguration::useComponentModelCache()) {
            \PoP\ComponentModel\Container\ContainerBuilderUtils::attachAndRegisterDirectiveResolversFromNamespace(__NAMESPACE__ . '\\DirectiveResolvers\\Cache');
            \PoP\ComponentModel\Container\ContainerBuilderUtils::attachTypeResolverDecoratorsFromNamespace(__NAMESPACE__ . '\\TypeResolverDecorators\\Cache');
        }
    }
}
