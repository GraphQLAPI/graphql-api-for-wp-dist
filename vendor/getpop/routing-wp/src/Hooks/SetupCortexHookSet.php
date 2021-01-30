<?php

declare (strict_types=1);
namespace PoP\RoutingWP\Hooks;

use PoP\Hooks\AbstractHookSet;
use PrefixedByPoP\Brain\Cortex\Route\RouteCollectionInterface;
use PrefixedByPoP\Brain\Cortex\Route\RouteInterface;
use PrefixedByPoP\Brain\Cortex\Route\QueryRoute;
use PoP\RoutingWP\WPQueries;
use PoP\Routing\Facades\RoutingManagerFacade;
class SetupCortexHookSet extends \PoP\Hooks\AbstractHookSet
{
    protected function init()
    {
        $this->hooksAPI->addAction('cortex.routes', [$this, 'setupCortex'], 1);
    }
    /**
     * @param RouteCollectionInterface<RouteInterface> $routes
     */
    public function setupCortex(\PrefixedByPoP\Brain\Cortex\Route\RouteCollectionInterface $routes) : void
    {
        $routingManager = \PoP\Routing\Facades\RoutingManagerFacade::getInstance();
        foreach ($routingManager->getRoutes() as $route) {
            $routes->addRoute(new \PrefixedByPoP\Brain\Cortex\Route\QueryRoute($route, function (array $matches) {
                return \PoP\RoutingWP\WPQueries::STANDARD_NATURE;
            }));
        }
    }
}
