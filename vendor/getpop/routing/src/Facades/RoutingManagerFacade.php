<?php

declare (strict_types=1);
namespace PoP\Routing\Facades;

use PoP\Routing\RoutingManagerInterface;
use PoP\Root\Container\ContainerBuilderFactory;
class RoutingManagerFacade
{
    public static function getInstance() : \PoP\Routing\RoutingManagerInterface
    {
        /**
         * @var RoutingManagerInterface
         */
        $service = \PoP\Root\Container\ContainerBuilderFactory::getInstance()->get(\PoP\Routing\RoutingManagerInterface::class);
        return $service;
    }
}
