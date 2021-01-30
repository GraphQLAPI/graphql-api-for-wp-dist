<?php

declare (strict_types=1);
namespace PoP\APIClients;

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
        return [\PoP\APIEndpoints\Component::class];
    }
}
