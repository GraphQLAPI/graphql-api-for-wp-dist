<?php

declare (strict_types=1);
namespace PoP\ComponentModel\Facades\Registries;

use PoP\ComponentModel\Registries\FieldInterfaceRegistryInterface;
use PoP\Root\Container\ContainerBuilderFactory;
class FieldInterfaceRegistryFacade
{
    public static function getInstance() : \PoP\ComponentModel\Registries\FieldInterfaceRegistryInterface
    {
        /**
         * @var FieldInterfaceRegistryInterface
         */
        $service = \PoP\Root\Container\ContainerBuilderFactory::getInstance()->get(\PoP\ComponentModel\Registries\FieldInterfaceRegistryInterface::class);
        return $service;
    }
}
