<?php

declare (strict_types=1);
namespace PoPSchema\Comments\Conditional\Users;

use PoPSchema\Comments\Component;
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
        self::maybeInitYAMLSchemaServices(\PoPSchema\Comments\Component::$COMPONENT_DIR, $skipSchema, '/Conditional/Users');
    }
    /**
     * Boot component
     *
     * @return void
     */
    public static function beforeBoot() : void
    {
        \PoP\ComponentModel\Container\ContainerBuilderUtils::attachFieldResolversFromNamespace(__NAMESPACE__ . '\\FieldResolvers');
    }
}
