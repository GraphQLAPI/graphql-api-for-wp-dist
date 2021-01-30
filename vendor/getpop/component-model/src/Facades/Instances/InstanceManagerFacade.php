<?php

declare (strict_types=1);
namespace PoP\ComponentModel\Facades\Instances;

use PoP\ComponentModel\Instances\InstanceManagerInterface;
use PoP\Root\Container\ContainerBuilderFactory;
class InstanceManagerFacade
{
    public static function getInstance() : \PoP\ComponentModel\Instances\InstanceManagerInterface
    {
        /**
         * @var InstanceManagerInterface
         */
        $service = \PoP\Root\Container\ContainerBuilderFactory::getInstance()->get(\PoP\ComponentModel\Instances\InstanceManagerInterface::class);
        return $service;
    }
}
