<?php

declare (strict_types=1);
namespace PoP\LooseContracts\Facades;

use PoP\LooseContracts\NameResolverInterface;
use PoP\Root\Container\ContainerBuilderFactory;
class NameResolverFacade
{
    public static function getInstance() : \PoP\LooseContracts\NameResolverInterface
    {
        /**
         * @var NameResolverInterface
         */
        $service = \PoP\Root\Container\ContainerBuilderFactory::getInstance()->get(\PoP\LooseContracts\NameResolverInterface::class);
        return $service;
    }
}
