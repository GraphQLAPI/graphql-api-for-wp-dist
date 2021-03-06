<?php

declare (strict_types=1);
namespace PoPSchema\GenericCustomPosts;

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
        return [\PoPSchema\CustomPosts\Component::class];
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
        \PoPSchema\GenericCustomPosts\ComponentConfiguration::setConfiguration($configuration);
        self::maybeInitYAMLSchemaServices(\dirname(__DIR__), $skipSchema);
    }
}
