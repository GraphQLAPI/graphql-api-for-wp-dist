<?php

declare (strict_types=1);
namespace GraphQLByPoP\GraphQLQuery;

use PoP\GraphQLAPI\Component as GraphQLAPIComponent;
use PoP\Root\Component\AbstractComponent;
use PoP\Root\Component\CanDisableComponentTrait;
use PoP\Root\Component\YAMLServicesTrait;
/**
 * Initialize component
 */
class Component extends \PoP\Root\Component\AbstractComponent
{
    use YAMLServicesTrait;
    use CanDisableComponentTrait;
    // const VERSION = '0.1.0';
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
    protected static function doInitialize(array $configuration = [], bool $skipSchema = \false, array $skipSchemaComponentClasses = []) : void
    {
        if (self::isEnabled()) {
            parent::doInitialize($configuration, $skipSchema, $skipSchemaComponentClasses);
            \GraphQLByPoP\GraphQLQuery\ComponentConfiguration::setConfiguration($configuration);
            self::initYAMLServices(\dirname(__DIR__));
        }
    }
    protected static function resolveEnabled()
    {
        return \PoP\GraphQLAPI\Component::isEnabled();
    }
}
