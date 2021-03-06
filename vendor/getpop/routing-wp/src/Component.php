<?php

declare (strict_types=1);
namespace PoP\RoutingWP;

use PrefixedByPoP\Brain\Cortex;
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
        return [\PoP\Routing\Component::class];
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
        self::initYAMLServices(\dirname(__DIR__));
    }
    /**
     * Boot component
     *
     * @return void
     */
    public static function beforeBoot() : void
    {
        parent::beforeBoot();
        // Boot Cortex
        \PrefixedByPoP\Brain\Cortex::boot();
    }
}
