<?php

declare (strict_types=1);
namespace PoPSchema\PostTags;

use PoPSchema\PostTags\Conditional\RESTAPI\ConditionalComponent;
use PoP\Root\Component\AbstractComponent;
use PoP\Root\Component\YAMLServicesTrait;
use PoPSchema\PostTags\Config\ServiceConfiguration;
use PoP\ComponentModel\Container\ContainerBuilderUtils;
use PoP\Routing\DefinitionGroups;
use PoP\Definitions\Facades\DefinitionManagerFacade;
/**
 * Initialize component
 */
class Component extends \PoP\Root\Component\AbstractComponent
{
    use YAMLServicesTrait;
    public static $COMPONENT_DIR;
    // const VERSION = '0.1.0';
    /**
     * Classes from PoP components that must be initialized before this component
     *
     * @return string[]
     */
    public static function getDependedComponentClasses() : array
    {
        return [\PoPSchema\Posts\Component::class, \PoPSchema\Tags\Component::class];
    }
    /**
     * All conditional component classes that this component depends upon, to initialize them
     *
     * @return array
     */
    public static function getDependedConditionalComponentClasses() : array
    {
        return [\PoP\API\Component::class, \PoP\RESTAPI\Component::class];
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
        \PoPSchema\PostTags\ComponentConfiguration::setConfiguration($configuration);
        self::$COMPONENT_DIR = \dirname(__DIR__);
        self::initYAMLServices(self::$COMPONENT_DIR);
        self::maybeInitYAMLSchemaServices(self::$COMPONENT_DIR, $skipSchema);
        \PoPSchema\PostTags\Config\ServiceConfiguration::initialize();
        if (!\in_array(\PoP\RESTAPI\Component::class, $skipSchemaComponentClasses)) {
            \PoPSchema\PostTags\Conditional\RESTAPI\ConditionalComponent::initialize($configuration, $skipSchema);
        }
    }
    /**
     * Boot component
     *
     * @return void
     */
    public static function beforeBoot() : void
    {
        parent::beforeBoot();
        // Initialize all hooks
        \PoP\ComponentModel\Container\ContainerBuilderUtils::registerTypeResolversFromNamespace(__NAMESPACE__ . '\\TypeResolvers');
        \PoP\ComponentModel\Container\ContainerBuilderUtils::attachFieldResolversFromNamespace(__NAMESPACE__ . '\\FieldResolvers');
        // If $skipSchema for `Condition` is `true`, then services are not registered
        if (!empty(\PoP\ComponentModel\Container\ContainerBuilderUtils::getServiceClassesUnderNamespace(__NAMESPACE__ . '\\Conditional\\RESTAPI\\Hooks'))) {
            \PoPSchema\PostTags\Conditional\RESTAPI\ConditionalComponent::beforeBoot();
        }
    }
    /**
     * Define runtime constants
     */
    protected static function defineRuntimeConstants(array $configuration = [], bool $skipSchema = \false, array $skipSchemaComponentClasses = []) : void
    {
        if (!\defined('POP_POSTTAGS_ROUTE_POSTTAGS')) {
            $definitionManager = \PoP\Definitions\Facades\DefinitionManagerFacade::getInstance();
            \define('POP_POSTTAGS_ROUTE_POSTTAGS', $definitionManager->getUniqueDefinition('post-tags', \PoP\Routing\DefinitionGroups::ROUTES));
        }
    }
}
