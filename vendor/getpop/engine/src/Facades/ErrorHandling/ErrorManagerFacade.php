<?php

declare (strict_types=1);
namespace PoP\Engine\Facades\ErrorHandling;

use PoP\Engine\ErrorHandling\ErrorManagerInterface;
use PoP\Root\Container\ContainerBuilderFactory;
class ErrorManagerFacade
{
    public static function getInstance() : \PoP\Engine\ErrorHandling\ErrorManagerInterface
    {
        /**
         * @var ErrorManagerInterface
         */
        $service = \PoP\Root\Container\ContainerBuilderFactory::getInstance()->get(\PoP\Engine\ErrorHandling\ErrorManagerInterface::class);
        return $service;
    }
}
