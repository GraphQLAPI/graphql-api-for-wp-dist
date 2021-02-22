<?php

declare (strict_types=1);
namespace PoP\ComponentModel\Container\CompilerPasses;

use PoP\ComponentModel\AttachableExtensions\AttachableExtensionGroups;
use PoP\ComponentModel\Component\ApplicationEvents;
use PoP\ComponentModel\DirectiveResolvers\DirectiveResolverInterface;
use PoP\ComponentModel\FieldResolvers\FieldResolverInterface;
use PoP\ComponentModel\TypeResolverPickers\TypeResolverPickerInterface;
class BeforeBootAttachExtensionCompilerPass extends \PoP\ComponentModel\Container\CompilerPasses\AbstractAttachExtensionCompilerPass
{
    protected function getAttachExtensionEvent() : string
    {
        return \PoP\ComponentModel\Component\ApplicationEvents::BEFORE_BOOT;
    }
    /**
     * @return array<string,string>
     */
    protected function getAttachableClassGroups() : array
    {
        return [\PoP\ComponentModel\FieldResolvers\FieldResolverInterface::class => \PoP\ComponentModel\AttachableExtensions\AttachableExtensionGroups::FIELDRESOLVERS, \PoP\ComponentModel\DirectiveResolvers\DirectiveResolverInterface::class => \PoP\ComponentModel\AttachableExtensions\AttachableExtensionGroups::DIRECTIVERESOLVERS, \PoP\ComponentModel\TypeResolverPickers\TypeResolverPickerInterface::class => \PoP\ComponentModel\AttachableExtensions\AttachableExtensionGroups::TYPERESOLVERPICKERS];
    }
}
