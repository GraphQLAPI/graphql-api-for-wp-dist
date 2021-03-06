<?php

declare (strict_types=1);
namespace PoPSchema\Media;

use PoP\Root\Component\AbstractComponent;
/**
 * Initialize component
 */
class Component extends \PoP\Root\Component\AbstractComponent
{
    /**
     * Classes from PoP components that must be initialized before this component
     *
     * @return string[]
     */
    public static function getDependedComponentClasses() : array
    {
        return [\PoP\Engine\Component::class, \PoPSchema\SchemaCommons\Component::class];
    }
    /**
     * All conditional component classes that this component depends upon, to initialize them
     *
     * @return array
     */
    public static function getDependedConditionalComponentClasses() : array
    {
        return [\PoPSchema\Users\Component::class];
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
    protected static function initializeContainerServices(array $configuration = [], bool $skipSchema = \false, array $skipSchemaComponentClasses = []) : void
    {
        parent::initializeContainerServices($configuration, $skipSchema, $skipSchemaComponentClasses);
        self::maybeInitYAMLSchemaServices(\dirname(__DIR__), $skipSchema);
        if (\class_exists('\\PoPSchema\\Users\\Component') && !\in_array(\PoPSchema\Users\Component::class, $skipSchemaComponentClasses)) {
            self::maybeInitYAMLSchemaServices(\dirname(__DIR__), $skipSchema, '/Conditional/Users');
        }
    }
}
