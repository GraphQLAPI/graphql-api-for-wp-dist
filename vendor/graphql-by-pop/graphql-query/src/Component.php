<?php

declare (strict_types=1);
namespace GraphQLByPoP\GraphQLQuery;

use PoP\GraphQLAPI\Component as GraphQLAPIComponent;
use PoP\Root\Component\AbstractComponent;
use PoP\Root\Component\CanDisableComponentTrait;
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
        return [\PoP\Engine\Component::class, \PoP\GraphQLAPI\Component::class];
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
            \GraphQLByPoP\GraphQLQuery\ComponentConfiguration::setConfiguration($configuration);
            self::initYAMLServices(\dirname(__DIR__));
        }
    }
    protected static function resolveEnabled()
    {
        return \PoP\GraphQLAPI\Component::isEnabled();
    }
}
