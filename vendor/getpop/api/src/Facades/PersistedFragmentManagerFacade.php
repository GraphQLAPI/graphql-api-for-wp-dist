<?php

declare (strict_types=1);
namespace PoP\API\Facades;

use PoP\API\PersistedQueries\PersistedFragmentManagerInterface;
use PoP\Root\Container\ContainerBuilderFactory;
class PersistedFragmentManagerFacade
{
    public static function getInstance() : \PoP\API\PersistedQueries\PersistedFragmentManagerInterface
    {
        /**
         * @var PersistedFragmentManagerInterface
         */
        $service = \PoP\Root\Container\ContainerBuilderFactory::getInstance()->get(\PoP\API\PersistedQueries\PersistedFragmentManagerInterface::class);
        return $service;
    }
}
