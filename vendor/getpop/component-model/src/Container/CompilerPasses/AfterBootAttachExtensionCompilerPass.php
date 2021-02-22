<?php

declare (strict_types=1);
namespace PoP\ComponentModel\Container\CompilerPasses;

use PoP\ComponentModel\AttachableExtensions\AttachableExtensionGroups;
use PoP\ComponentModel\Component\ApplicationEvents;
use PoP\ComponentModel\TypeResolverDecorators\TypeResolverDecoratorInterface;
class AfterBootAttachExtensionCompilerPass extends \PoP\ComponentModel\Container\CompilerPasses\AbstractAttachExtensionCompilerPass
{
    protected function getAttachExtensionEvent() : string
    {
        return \PoP\ComponentModel\Component\ApplicationEvents::AFTER_BOOT;
    }
    /**
     * @return array<string,string>
     */
    protected function getAttachableClassGroups() : array
    {
        return [\PoP\ComponentModel\TypeResolverDecorators\TypeResolverDecoratorInterface::class => \PoP\ComponentModel\AttachableExtensions\AttachableExtensionGroups::TYPERESOLVERDECORATORS];
    }
}
