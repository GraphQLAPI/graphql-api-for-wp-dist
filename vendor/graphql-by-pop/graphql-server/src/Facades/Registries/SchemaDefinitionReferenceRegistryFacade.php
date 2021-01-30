<?php

declare (strict_types=1);
namespace GraphQLByPoP\GraphQLServer\Facades\Registries;

use GraphQLByPoP\GraphQLServer\Registries\SchemaDefinitionReferenceRegistryInterface;
use PoP\Root\Container\ContainerBuilderFactory;
class SchemaDefinitionReferenceRegistryFacade
{
    public static function getInstance() : \GraphQLByPoP\GraphQLServer\Registries\SchemaDefinitionReferenceRegistryInterface
    {
        /**
         * @var SchemaDefinitionReferenceRegistryInterface
         */
        $service = \PoP\Root\Container\ContainerBuilderFactory::getInstance()->get(\GraphQLByPoP\GraphQLServer\Registries\SchemaDefinitionReferenceRegistryInterface::class);
        return $service;
    }
}
