<?php

declare (strict_types=1);
namespace PoP\ComponentModel\Facades\ModelInstance;

use PoP\ComponentModel\ModelInstance\ModelInstanceInterface;
use PoP\Root\Container\ContainerBuilderFactory;
class ModelInstanceFacade
{
    public static function getInstance() : \PoP\ComponentModel\ModelInstance\ModelInstanceInterface
    {
        /**
         * @var ModelInstanceInterface
         */
        $service = \PoP\Root\Container\ContainerBuilderFactory::getInstance()->get(\PoP\ComponentModel\ModelInstance\ModelInstanceInterface::class);
        return $service;
    }
}
