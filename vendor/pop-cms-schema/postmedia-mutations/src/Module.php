<?php

declare (strict_types=1);
namespace PoPCMSSchema\PostMediaMutations;

use PoPCMSSchema\CustomPostMediaMutations\Module as CustomPostMediaMutationsModule;
use PoP\Root\App;
use PoP\Root\Exception\ComponentNotExistsException;
use PoP\Root\Module\AbstractModule;
use PoP\Root\Module\ModuleInterface;
class Module extends AbstractModule
{
    /**
     * @return array<class-string<ModuleInterface>>
     */
    public function getDependedModuleClasses() : array
    {
        return [\PoPCMSSchema\CustomPostMediaMutations\Module::class, \PoPCMSSchema\PostMutations\Module::class];
    }
    /**
     * Initialize services
     *
     * @param array<class-string<ModuleInterface>> $skipSchemaModuleClasses
     * @param bool $skipSchema
     */
    protected function initializeContainerServices($skipSchema, $skipSchemaModuleClasses) : void
    {
        try {
            if (\class_exists(CustomPostMediaMutationsModule::class) && App::getModule(CustomPostMediaMutationsModule::class)->isEnabled()) {
                $this->initSchemaServices(\dirname(__DIR__), $skipSchema || \in_array(CustomPostMediaMutationsModule::class, $skipSchemaModuleClasses), '/ConditionalOnModule/CustomPostMediaMutations');
            }
        } catch (ComponentNotExistsException $exception) {
        }
    }
}
