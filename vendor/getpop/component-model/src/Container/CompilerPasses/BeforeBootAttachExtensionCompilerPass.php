<?php

declare (strict_types=1);
namespace PoP\ComponentModel\Container\CompilerPasses;

use PoP\Root\Component\ApplicationEvents;
class BeforeBootAttachExtensionCompilerPass extends \PoP\ComponentModel\Container\CompilerPasses\AbstractAttachExtensionCompilerPass
{
    protected function getAttachExtensionEvent() : string
    {
        return ApplicationEvents::BEFORE_BOOT;
    }
    /**
     * @return array<string,string>
     */
    protected function getAttachableClassGroups() : array
    {
        // Nothing to initialize
        return [];
    }
}
