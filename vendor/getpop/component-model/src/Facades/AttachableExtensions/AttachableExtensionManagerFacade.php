<?php

declare (strict_types=1);
namespace PoP\ComponentModel\Facades\AttachableExtensions;

use PoP\ComponentModel\AttachableExtensions\AttachableExtensionManagerInterface;
use PoP\Root\Container\ContainerBuilderFactory;
class AttachableExtensionManagerFacade
{
    public static function getInstance() : \PoP\ComponentModel\AttachableExtensions\AttachableExtensionManagerInterface
    {
        /**
         * @var AttachableExtensionManagerInterface
         */
        $service = \PoP\Root\Container\ContainerBuilderFactory::getInstance()->get(\PoP\ComponentModel\AttachableExtensions\AttachableExtensionManagerInterface::class);
        return $service;
    }
}
