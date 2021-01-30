<?php

declare (strict_types=1);
namespace GraphQLByPoP\GraphQLQuery\Facades;

use GraphQLByPoP\GraphQLQuery\Schema\GraphQLQueryConvertorInterface;
use PoP\Root\Container\ContainerBuilderFactory;
class GraphQLQueryConvertorFacade
{
    public static function getInstance() : \GraphQLByPoP\GraphQLQuery\Schema\GraphQLQueryConvertorInterface
    {
        /**
         * @var GraphQLQueryConvertorInterface
         */
        $service = \PoP\Root\Container\ContainerBuilderFactory::getInstance()->get(\GraphQLByPoP\GraphQLQuery\Schema\GraphQLQueryConvertorInterface::class);
        return $service;
    }
}
