<?php

declare (strict_types=1);
namespace PoP\Definitions\Facades;

use PoP\Definitions\DefinitionManagerInterface;
use PoP\Root\Container\ContainerBuilderFactory;
class DefinitionManagerFacade
{
    public static function getInstance() : \PoP\Definitions\DefinitionManagerInterface
    {
        /**
         * @var DefinitionManagerInterface
         */
        $service = \PoP\Root\Container\ContainerBuilderFactory::getInstance()->get(\PoP\Definitions\DefinitionManagerInterface::class);
        return $service;
    }
}
