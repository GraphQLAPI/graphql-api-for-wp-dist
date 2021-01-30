<?php

declare (strict_types=1);
namespace PoP\ComponentModel\Facades\ModulePath;

use PoP\ComponentModel\ModulePath\ModulePathHelpersInterface;
use PoP\Root\Container\ContainerBuilderFactory;
class ModulePathHelpersFacade
{
    public static function getInstance() : \PoP\ComponentModel\ModulePath\ModulePathHelpersInterface
    {
        /**
         * @var ModulePathHelpersInterface
         */
        $service = \PoP\Root\Container\ContainerBuilderFactory::getInstance()->get(\PoP\ComponentModel\ModulePath\ModulePathHelpersInterface::class);
        return $service;
    }
}
