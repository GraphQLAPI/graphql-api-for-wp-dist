<?php

declare (strict_types=1);
namespace PoPSchema\UserRolesAccessControl;

use PoPSchema\UserRolesAccessControl\Conditional\CacheControl\ConditionalComponent;
use PoP\AccessControl\Component as AccessControlComponent;
use PoP\Root\Component\AbstractComponent;
use PoP\Root\Component\YAMLServicesTrait;
use PoP\Root\Component\CanDisableComponentTrait;
use PoP\ComponentModel\Container\ContainerBuilderUtils;
/**
 * Initialize component
 */
class Component extends \PoP\Root\Component\AbstractComponent
{
    use YAMLServicesTrait;
    use CanDisableComponentTrait;
    public static $COMPONENT_DIR;
    // const VERSION = '0.1.0';
    /**
     * Classes from PoP components that must be initialized before this component
     *
     * @return string[]
     */
    public static function getDependedComponentClasses() : array
    {
        return [\PoPSchema\UserRoles\Component::class, \PoPSchema\UserStateAccessControl\Component::class];
    }
    /**
     * All conditional component classes that this component depends upon, to initialize them
     *
     * @return array
     */
    public static function getDependedConditionalComponentClasses() : array
    {
        return [\PoP\CacheControl\Component::class];
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
            self::$COMPONENT_DIR = \dirname(__DIR__);
            self::initYAMLServices(self::$COMPONENT_DIR);
            self::maybeInitYAMLSchemaServices(self::$COMPONENT_DIR, $skipSchema);
            if (\class_exists('\\PoP\\CacheControl\\Component') && !\in_array(\PoP\CacheControl\Component::class, $skipSchemaComponentClasses)) {
                \PoPSchema\UserRolesAccessControl\Conditional\CacheControl\ConditionalComponent::initialize($configuration, $skipSchema);
            }
        }
    }
    protected static function resolveEnabled()
    {
        return \PoP\AccessControl\Component::isEnabled();
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
        \PoP\ComponentModel\Container\ContainerBuilderUtils::instantiateNamespaceServices(__NAMESPACE__ . '\\Hooks');
        \PoP\ComponentModel\Container\ContainerBuilderUtils::attachAndRegisterDirectiveResolversFromNamespace(__NAMESPACE__ . '\\DirectiveResolvers');
        // Boot conditional on API package being installed
    }
    /**
     * Boot component
     *
     * @return void
     */
    public static function afterBoot() : void
    {
        parent::afterBoot();
        // Initialize classes
        \PoP\ComponentModel\Container\ContainerBuilderUtils::attachTypeResolverDecoratorsFromNamespace(__NAMESPACE__ . '\\TypeResolverDecorators');
        if (\class_exists('\\PoP\\CacheControl\\Component')) {
            \PoPSchema\UserRolesAccessControl\Conditional\CacheControl\ConditionalComponent::afterBoot();
        }
    }
}
