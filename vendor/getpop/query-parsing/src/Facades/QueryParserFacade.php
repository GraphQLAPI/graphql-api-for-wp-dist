<?php

declare (strict_types=1);
namespace PoP\QueryParsing\Facades;

use PoP\QueryParsing\QueryParserInterface;
use PoP\Root\Container\ContainerBuilderFactory;
class QueryParserFacade
{
    public static function getInstance() : \PoP\QueryParsing\QueryParserInterface
    {
        /**
         * @var QueryParserInterface
         */
        $service = \PoP\Root\Container\ContainerBuilderFactory::getInstance()->get(\PoP\QueryParsing\QueryParserInterface::class);
        return $service;
    }
}
