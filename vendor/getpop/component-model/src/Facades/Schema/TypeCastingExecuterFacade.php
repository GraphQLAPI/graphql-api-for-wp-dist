<?php

declare (strict_types=1);
namespace PoP\ComponentModel\Facades\Schema;

use PoP\ComponentModel\Schema\TypeCastingExecuterInterface;
use PoP\Root\Container\ContainerBuilderFactory;
class TypeCastingExecuterFacade
{
    public static function getInstance() : \PoP\ComponentModel\Schema\TypeCastingExecuterInterface
    {
        /**
         * @var TypeCastingExecuterInterface
         */
        $service = \PoP\Root\Container\ContainerBuilderFactory::getInstance()->get(\PoP\ComponentModel\Schema\TypeCastingExecuterInterface::class);
        return $service;
    }
}
