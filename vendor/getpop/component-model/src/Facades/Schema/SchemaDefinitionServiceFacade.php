<?php

declare (strict_types=1);
namespace PoP\ComponentModel\Facades\Schema;

use PoP\ComponentModel\Schema\SchemaDefinitionServiceInterface;
use PoP\Root\Container\ContainerBuilderFactory;
class SchemaDefinitionServiceFacade
{
    public static function getInstance() : \PoP\ComponentModel\Schema\SchemaDefinitionServiceInterface
    {
        /**
         * @var SchemaDefinitionServiceInterface
         */
        $service = \PoP\Root\Container\ContainerBuilderFactory::getInstance()->get(\PoP\ComponentModel\Schema\SchemaDefinitionServiceInterface::class);
        return $service;
    }
}
