<?php

declare (strict_types=1);
namespace PoP\Root\Facades;

use PoP\Root\Container\SystemContainerBuilderFactory;
use PoP\Root\Registries\CompilerPassRegistryInterface;
class SystemCompilerPassRegistryFacade
{
    public static function getInstance() : \PoP\Root\Registries\CompilerPassRegistryInterface
    {
        $systemContainerBuilder = \PoP\Root\Container\SystemContainerBuilderFactory::getInstance();
        /**
         * @var CompilerPassRegistryInterface
         */
        $service = $systemContainerBuilder->get(\PoP\Root\Registries\CompilerPassRegistryInterface::class);
        return $service;
    }
}
