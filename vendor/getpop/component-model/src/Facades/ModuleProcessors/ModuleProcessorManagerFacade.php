<?php

declare (strict_types=1);
namespace PoP\ComponentModel\Facades\ModuleProcessors;

use PoP\ComponentModel\ModuleProcessors\ModuleProcessorManagerInterface;
use PoP\Root\Container\ContainerBuilderFactory;
class ModuleProcessorManagerFacade
{
    public static function getInstance() : \PoP\ComponentModel\ModuleProcessors\ModuleProcessorManagerInterface
    {
        /**
         * @var ModuleProcessorManagerInterface
         */
        $service = \PoP\Root\Container\ContainerBuilderFactory::getInstance()->get(\PoP\ComponentModel\ModuleProcessors\ModuleProcessorManagerInterface::class);
        return $service;
    }
}
