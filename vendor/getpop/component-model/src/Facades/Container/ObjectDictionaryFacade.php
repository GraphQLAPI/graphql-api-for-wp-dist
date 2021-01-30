<?php

declare (strict_types=1);
namespace PoP\ComponentModel\Facades\Container;

use PoP\ComponentModel\Container\ObjectDictionaryInterface;
use PoP\Root\Container\ContainerBuilderFactory;
class ObjectDictionaryFacade
{
    public static function getInstance() : \PoP\ComponentModel\Container\ObjectDictionaryInterface
    {
        /**
         * @var ObjectDictionaryInterface
         */
        $service = \PoP\Root\Container\ContainerBuilderFactory::getInstance()->get(\PoP\ComponentModel\Container\ObjectDictionaryInterface::class);
        return $service;
    }
}
