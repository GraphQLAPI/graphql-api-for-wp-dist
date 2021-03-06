<?php

declare (strict_types=1);
namespace PoP\ComponentModel\Facades\Info;

use PoP\ComponentModel\Info\ApplicationInfoInterface;
use PoP\Root\Container\ContainerBuilderFactory;
class ApplicationInfoFacade
{
    public static function getInstance() : \PoP\ComponentModel\Info\ApplicationInfoInterface
    {
        /**
         * @var ApplicationInfoInterface
         */
        $service = \PoP\Root\Container\ContainerBuilderFactory::getInstance()->get(\PoP\ComponentModel\Info\ApplicationInfoInterface::class);
        return $service;
    }
}
