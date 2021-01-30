<?php

declare (strict_types=1);
namespace PoP\ComponentModel\Facades\Registries;

use PoP\ComponentModel\Registries\DirectiveRegistryInterface;
use PoP\Root\Container\ContainerBuilderFactory;
class DirectiveRegistryFacade
{
    public static function getInstance() : \PoP\ComponentModel\Registries\DirectiveRegistryInterface
    {
        /**
         * @var DirectiveRegistryInterface
         */
        $service = \PoP\Root\Container\ContainerBuilderFactory::getInstance()->get(\PoP\ComponentModel\Registries\DirectiveRegistryInterface::class);
        return $service;
    }
}
