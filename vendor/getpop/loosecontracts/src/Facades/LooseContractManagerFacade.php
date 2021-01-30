<?php

declare (strict_types=1);
namespace PoP\LooseContracts\Facades;

use PoP\LooseContracts\LooseContractManagerInterface;
use PoP\Root\Container\ContainerBuilderFactory;
class LooseContractManagerFacade
{
    public static function getInstance() : \PoP\LooseContracts\LooseContractManagerInterface
    {
        /**
         * @var LooseContractManagerInterface
         */
        $service = \PoP\Root\Container\ContainerBuilderFactory::getInstance()->get(\PoP\LooseContracts\LooseContractManagerInterface::class);
        return $service;
    }
}
