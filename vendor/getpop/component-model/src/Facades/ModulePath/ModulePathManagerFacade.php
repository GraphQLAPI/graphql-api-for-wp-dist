<?php

declare (strict_types=1);
namespace PoP\ComponentModel\Facades\ModulePath;

use PoP\ComponentModel\ModulePath\ModulePathManagerInterface;
use PoP\Root\Container\ContainerBuilderFactory;
class ModulePathManagerFacade
{
    public static function getInstance() : \PoP\ComponentModel\ModulePath\ModulePathManagerInterface
    {
        /**
         * @var ModulePathManagerInterface
         */
        $service = \PoP\Root\Container\ContainerBuilderFactory::getInstance()->get(\PoP\ComponentModel\ModulePath\ModulePathManagerInterface::class);
        return $service;
    }
}
