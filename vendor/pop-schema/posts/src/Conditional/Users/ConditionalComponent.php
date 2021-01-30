<?php

declare (strict_types=1);
namespace PoPSchema\Posts\Conditional\Users;

use PoPSchema\Posts\Component;
use PoP\Root\Component\YAMLServicesTrait;
use PoP\ComponentModel\Container\ContainerBuilderUtils;
/**
 * Initialize component
 */
class ConditionalComponent
{
    use YAMLServicesTrait;
    public static function initialize(array $configuration = [], bool $skipSchema = \false, array $skipSchemaComponentClasses = []) : void
    {
        self::initYAMLServices(\PoPSchema\Posts\Component::$COMPONENT_DIR, '/Conditional/Users');
        self::maybeInitYAMLSchemaServices(\PoPSchema\Posts\Component::$COMPONENT_DIR, $skipSchema, '/Conditional/Users');
    }
    /**
     * Boot component
     *
     * @return void
     */
    public static function beforeBoot() : void
    {
        \PoP\ComponentModel\Container\ContainerBuilderUtils::instantiateNamespaceServices(__NAMESPACE__ . '\\Hooks');
        \PoP\ComponentModel\Container\ContainerBuilderUtils::attachFieldResolversFromNamespace(__NAMESPACE__ . '\\FieldResolvers');
    }
}
