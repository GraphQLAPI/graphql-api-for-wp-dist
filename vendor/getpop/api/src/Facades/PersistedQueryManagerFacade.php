<?php

declare (strict_types=1);
namespace PoP\API\Facades;

use PoP\API\PersistedQueries\PersistedQueryManagerInterface;
use PoP\Root\Container\ContainerBuilderFactory;
class PersistedQueryManagerFacade
{
    public static function getInstance() : \PoP\API\PersistedQueries\PersistedQueryManagerInterface
    {
        /**
         * @var PersistedQueryManagerInterface
         */
        $service = \PoP\Root\Container\ContainerBuilderFactory::getInstance()->get(\PoP\API\PersistedQueries\PersistedQueryManagerInterface::class);
        return $service;
    }
}
