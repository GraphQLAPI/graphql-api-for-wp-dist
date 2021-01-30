<?php

declare (strict_types=1);
namespace PoP\AccessControl\Facades;

use PoP\AccessControl\Services\AccessControlManagerInterface;
use PoP\Root\Container\ContainerBuilderFactory;
class AccessControlManagerFacade
{
    public static function getInstance() : \PoP\AccessControl\Services\AccessControlManagerInterface
    {
        /**
         * @var AccessControlManagerInterface
         */
        $service = \PoP\Root\Container\ContainerBuilderFactory::getInstance()->get(\PoP\AccessControl\Services\AccessControlManagerInterface::class);
        return $service;
    }
}
