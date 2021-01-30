<?php

declare (strict_types=1);
namespace PoPSchema\UserMeta;

use PoP\Root\Component\AbstractComponent;
/**
 * Initialize component
 */
class Component extends \PoP\Root\Component\AbstractComponent
{
    // const VERSION = '0.1.0';
    /**
     * Classes from PoP components that must be initialized before this component
     *
     * @return string[]
     */
    public static function getDependedComponentClasses() : array
    {
        return [\PoPSchema\Meta\Component::class, \PoPSchema\Users\Component::class];
    }
    public static function getDependedMigrationPlugins() : array
    {
        $packageName = \basename(\dirname(__DIR__));
        $folder = \dirname(__DIR__, 2);
        return [$folder . '/migrate-' . $packageName . '/initialize.php'];
    }
}
