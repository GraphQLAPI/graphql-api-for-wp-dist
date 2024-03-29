<?php

declare(strict_types=1);

namespace PoPCMSSchema\PostsWP\Hooks;

use PoP\Root\App;
use PoP\Root\Hooks\AbstractHookSet;
use PoP\RootWP\Routing\HookNames;
use PoPCMSSchema\Posts\Module;
use PoPCMSSchema\Posts\ModuleConfiguration;

class RoutingHookSet extends AbstractHookSet
{
    protected function init(): void
    {
        App::addAction(
            HookNames::ROUTES,
            \Closure::fromCallable([$this, 'registerRoutes'])
        );
    }

    /**
     * @return string[]
     * @param string[] $routes
     */
    public function registerRoutes($routes): array
    {
        /** @var ModuleConfiguration */
        $moduleConfiguration = App::getModule(Module::class)->getConfiguration();
        return array_merge($routes, [$moduleConfiguration->getPostsRoute()]);
    }
}
