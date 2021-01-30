<?php

declare (strict_types=1);
namespace PoP\ComponentModel\Facades\ModuleFiltering;

use PoP\ComponentModel\ModuleFiltering\ModuleFilterManagerInterface;
use PoP\Root\Container\ContainerBuilderFactory;
class ModuleFilterManagerFacade
{
    public static function getInstance() : \PoP\ComponentModel\ModuleFiltering\ModuleFilterManagerInterface
    {
        /**
         * @var ModuleFilterManagerInterface
         */
        $service = \PoP\Root\Container\ContainerBuilderFactory::getInstance()->get(\PoP\ComponentModel\ModuleFiltering\ModuleFilterManagerInterface::class);
        return $service;
    }
}
