<?php

declare (strict_types=1);
namespace PoPCMSSchema\CustomPostTagMutations;

use PoP\Root\Module\ModuleInterface;
use PoP\Root\Module\AbstractModule;
class Module extends AbstractModule
{
    /**
     * @return array<class-string<ModuleInterface>>
     */
    public function getDependedModuleClasses() : array
    {
        return [\PoPCMSSchema\CustomPostMutations\Module::class, \PoPCMSSchema\Tags\Module::class];
    }
    /**
     * Initialize services
     *
     * @param array<class-string<ModuleInterface>> $skipSchemaModuleClasses
     * @param bool $skipSchema
     */
    protected function initializeContainerServices($skipSchema, $skipSchemaModuleClasses) : void
    {
        $this->initServices(\dirname(__DIR__));
    }
}
