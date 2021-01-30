<?php

declare (strict_types=1);
namespace PoP\ComponentModel\Facades\Registries;

use PoP\ComponentModel\Registries\TypeRegistryInterface;
use PoP\Root\Container\ContainerBuilderFactory;
class TypeRegistryFacade
{
    public static function getInstance() : \PoP\ComponentModel\Registries\TypeRegistryInterface
    {
        /**
         * @var TypeRegistryInterface
         */
        $service = \PoP\Root\Container\ContainerBuilderFactory::getInstance()->get(\PoP\ComponentModel\Registries\TypeRegistryInterface::class);
        return $service;
    }
}
