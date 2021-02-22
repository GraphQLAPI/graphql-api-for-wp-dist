<?php

declare (strict_types=1);
namespace PoP\ComponentModel\Facades\AttachableExtensions;

use PoP\ComponentModel\AttachableExtensions\AttachExtensionServiceInterface;
use PoP\Root\Container\ContainerBuilderFactory;
class AttachExtensionServiceFacade
{
    public static function getInstance() : \PoP\ComponentModel\AttachableExtensions\AttachExtensionServiceInterface
    {
        /**
         * @var AttachExtensionServiceInterface
         */
        $service = \PoP\Root\Container\ContainerBuilderFactory::getInstance()->get(\PoP\ComponentModel\AttachableExtensions\AttachExtensionServiceInterface::class);
        return $service;
    }
}
