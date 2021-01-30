<?php

declare (strict_types=1);
namespace PoP\ComponentModel\Facades\MutationResolution;

use PoP\ComponentModel\MutationResolution\MutationResolutionManagerInterface;
use PoP\Root\Container\ContainerBuilderFactory;
class MutationResolutionManagerFacade
{
    public static function getInstance() : \PoP\ComponentModel\MutationResolution\MutationResolutionManagerInterface
    {
        /**
         * @var MutationResolutionManagerInterface
         */
        $service = \PoP\Root\Container\ContainerBuilderFactory::getInstance()->get(\PoP\ComponentModel\MutationResolution\MutationResolutionManagerInterface::class);
        return $service;
    }
}
