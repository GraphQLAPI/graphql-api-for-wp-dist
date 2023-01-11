<?php

declare (strict_types=1);
namespace PoP\RootWP\Hooks;

use PrefixedByPoP\Brain\Cortex\Route\QueryRoute;
use PrefixedByPoP\Brain\Cortex\Route\RouteCollectionInterface;
use PrefixedByPoP\Brain\Cortex\Route\RouteInterface;
use PoP\Root\App;
use PoP\Root\Hooks\AbstractHookSet;
use PoP\Root\Routing\RoutingManagerInterface;
use PoP\RootWP\Routing\WPQueries;
use PoP\RootWP\Routing\WPQueryRoutingManagerInterface;
class SetupCortexRoutingHookSet extends AbstractHookSet
{
    /**
     * @var \PoP\Root\Routing\RoutingManagerInterface|null
     */
    private $routingManager;
    /**
     * @param \PoP\Root\Routing\RoutingManagerInterface $routingManager
     */
    public final function setRoutingManager($routingManager) : void
    {
        $this->routingManager = $routingManager;
    }
    protected final function getRoutingManager() : RoutingManagerInterface
    {
        /** @var RoutingManagerInterface */
        return $this->routingManager = $this->routingManager ?? $this->instanceManager->getInstance(RoutingManagerInterface::class);
    }
    protected function init() : void
    {
        App::addAction('cortex.routes', \Closure::fromCallable([$this, 'setupCortex']), 1);
    }
    /**
     * @param RouteCollectionInterface<RouteInterface> $routes
     */
    public function setupCortex($routes) : void
    {
        /** @var WPQueryRoutingManagerInterface */
        $routingManager = $this->getRoutingManager();
        foreach ($routingManager->getRoutes() as $route) {
            $routes->addRoute(new QueryRoute($route, function (array $matches) {
                return WPQueries::GENERIC_NATURE;
            }));
        }
    }
}
