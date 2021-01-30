<?php

declare (strict_types=1);
namespace PoP\API\Facades;

use PoP\API\Registries\SchemaDefinitionRegistryInterface;
use PoP\Root\Container\ContainerBuilderFactory;
class SchemaDefinitionRegistryFacade
{
    public static function getInstance() : \PoP\API\Registries\SchemaDefinitionRegistryInterface
    {
        /**
         * @var SchemaDefinitionRegistryInterface
         */
        $service = \PoP\Root\Container\ContainerBuilderFactory::getInstance()->get(\PoP\API\Registries\SchemaDefinitionRegistryInterface::class);
        return $service;
    }
}
