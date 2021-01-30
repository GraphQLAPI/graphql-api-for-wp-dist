<?php

declare (strict_types=1);
namespace PoP\API\Facades;

use PoP\API\Schema\FieldQueryConvertorInterface;
use PoP\Root\Container\ContainerBuilderFactory;
class FieldQueryConvertorFacade
{
    public static function getInstance() : \PoP\API\Schema\FieldQueryConvertorInterface
    {
        /**
         * @var FieldQueryConvertorInterface
         */
        $service = \PoP\Root\Container\ContainerBuilderFactory::getInstance()->get(\PoP\API\Schema\FieldQueryConvertorInterface::class);
        return $service;
    }
}
