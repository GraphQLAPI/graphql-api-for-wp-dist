<?php

declare (strict_types=1);
namespace PoP\Engine;

use PoP\Hooks\Facades\HooksAPIFacade;
use PoP\Root\Managers\ComponentManager;
class ComponentLoader extends \PoP\Root\ComponentLoader
{
    public static function bootComponents()
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
