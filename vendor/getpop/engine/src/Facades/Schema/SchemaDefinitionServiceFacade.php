<?php

declare (strict_types=1);
namespace PoP\Engine\Facades\Schema;

use PoP\Engine\Schema\SchemaDefinitionServiceInterface;
use PoP\Root\Container\ContainerBuilderFactory;
class SchemaDefinitionServiceFacade
{
    public static function getInstance() : \PoP\Engine\Schema\SchemaDefinitionServiceInterface
    {
        /**
         * @var SchemaDefinitionServiceInterface
         */
        $service = \PoP\Root\Container\ContainerBuilderFactory::getInstance()->get(\PoP\Engine\Schema\SchemaDefinitionServiceInterface::class);
        return $service;
    }
}
