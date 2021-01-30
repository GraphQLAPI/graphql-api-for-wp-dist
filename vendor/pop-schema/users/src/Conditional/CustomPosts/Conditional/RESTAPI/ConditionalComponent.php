<?php

declare (strict_types=1);
namespace PoPSchema\Users\Conditional\CustomPosts\Conditional\RESTAPI;

use PoPSchema\Users\Component;
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
        self::initYAMLServices(\PoPSchema\Users\Component::$COMPONENT_DIR, '/Conditional/CustomPosts/Conditional/RESTAPI');
    }
    /**
     * Boot component
     *
     * @return void
     */
    public static function beforeBoot() : void
    {
        \PoP\ComponentModel\Container\ContainerBuilderUtils::instantiateNamespaceServices(__NAMESPACE__ . '\\Hooks');
    }
}
