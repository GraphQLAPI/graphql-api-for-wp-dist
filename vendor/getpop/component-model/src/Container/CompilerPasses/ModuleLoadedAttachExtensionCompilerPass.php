<?php

declare (strict_types=1);
namespace PoP\ComponentModel\Container\CompilerPasses;

use PoP\Root\Module\ApplicationEvents;
class ModuleLoadedAttachExtensionCompilerPass extends \PoP\ComponentModel\Container\CompilerPasses\AbstractAttachExtensionCompilerPass
{
    protected function getAttachExtensionEvent() : string
    {
        return ApplicationEvents::MODULE_LOADED;
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
