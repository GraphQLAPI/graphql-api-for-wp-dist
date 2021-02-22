<?php

declare (strict_types=1);
namespace PoP\Engine;

use PoP\Hooks\Facades\HooksAPIFacade;
use PoP\Root\Managers\ComponentManager;
use PoP\Root\AppLoader as RootAppLoader;
class AppLoader extends \PoP\Root\AppLoader
{
    /**
     * Trigger "beforeBoot", "boot" and "afterBoot" events on all the Components,
     * for them to execute any custom extra logic.
     * Override to execute functions on CMS events.
     */
    protected static function bootComponents() : void
    {
        // Boot all the components
        \PoP\Root\Managers\ComponentManager::beforeBoot();
        $hooksAPI = \PoP\Hooks\Facades\HooksAPIFacade::getInstance();
        $hooksAPI->addAction('popcms:boot', function () {
            // Boot all the components
            \PoP\Root\Managers\ComponentManager::boot();
        }, 5);
        $hooksAPI->addAction('popcms:boot', function () {
            // Boot all the components
            \PoP\Root\Managers\ComponentManager::afterBoot();
        }, 15);
    }
}
