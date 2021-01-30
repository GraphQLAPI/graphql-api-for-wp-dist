<?php

declare (strict_types=1);
namespace GraphQLAPI\MarkdownConvertor\Facades;

use GraphQLAPI\MarkdownConvertor\MarkdownConvertorInterface;
use PoP\Root\Container\ContainerBuilderFactory;
class MarkdownConvertorFacade
{
    public static function getInstance() : \GraphQLAPI\MarkdownConvertor\MarkdownConvertorInterface
    {
        /**
         * @var MarkdownConvertorInterface
         */
        $service = \PoP\Root\Container\ContainerBuilderFactory::getInstance()->get(\GraphQLAPI\MarkdownConvertor\MarkdownConvertorInterface::class);
        return $service;
    }
}
