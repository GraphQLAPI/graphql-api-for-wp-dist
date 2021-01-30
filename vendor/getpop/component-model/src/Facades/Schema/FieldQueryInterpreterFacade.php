<?php

declare (strict_types=1);
namespace PoP\ComponentModel\Facades\Schema;

use PoP\ComponentModel\Schema\FieldQueryInterpreterInterface;
use PoP\Root\Container\ContainerBuilderFactory;
class FieldQueryInterpreterFacade
{
    public static function getInstance() : \PoP\ComponentModel\Schema\FieldQueryInterpreterInterface
    {
        /**
         * @var FieldQueryInterpreterInterface
         */
        $service = \PoP\Root\Container\ContainerBuilderFactory::getInstance()->get(\PoP\ComponentModel\Schema\FieldQueryInterpreterInterface::class);
        return $service;
    }
}
