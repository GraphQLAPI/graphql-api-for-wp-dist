<?php

declare (strict_types=1);
namespace PoP\ComponentModel\Facades\DataStructure;

use PoP\ComponentModel\DataStructure\DataStructureManagerInterface;
use PoP\Root\Container\ContainerBuilderFactory;
class DataStructureManagerFacade
{
    public static function getInstance() : \PoP\ComponentModel\DataStructure\DataStructureManagerInterface
    {
        /**
         * @var DataStructureManagerInterface
         */
        $service = \PoP\Root\Container\ContainerBuilderFactory::getInstance()->get(\PoP\ComponentModel\DataStructure\DataStructureManagerInterface::class);
        return $service;
    }
}
