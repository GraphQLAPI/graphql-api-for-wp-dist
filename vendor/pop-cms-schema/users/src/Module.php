<?php

declare (strict_types=1);
namespace PoPCMSSchema\Users;

use PoPAPI\API\Module as APIModule;
use PoPAPI\RESTAPI\Module as RESTAPIModule;
use PoPCMSSchema\CustomPosts\Module as CustomPostsModule;
use PoP\Root\App;
use PoP\Root\Exception\ComponentNotExistsException;
use PoP\Root\Module\AbstractModule;
use PoP\Root\Module\ModuleInterface;
class Module extends AbstractModule
{
    protected function requiresSatisfyingModule() : bool
    {
        return \true;
    }
    /**
     * @return array<class-string<ModuleInterface>>
     */
    public function getDependedModuleClasses() : array
    {
        return [\PoPCMSSchema\QueriedObject\Module::class];
    }
    /**
     * @return array<class-string<ModuleInterface>>
     */
    public function getDependedConditionalModuleClasses() : array
    {
        return [\PoPAPI\API\Module::class, \PoPAPI\RESTAPI\Module::class, \PoPCMSSchema\CustomPosts\Module::class];
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
        $this->initSchemaServices(\dirname(__DIR__), $skipSchema);
        try {
            if (\class_exists(APIModule::class) && App::getModule(APIModule::class)->isEnabled()) {
                $this->initServices(\dirname(__DIR__), '/ConditionalOnModule/API');
            }
        } catch (ComponentNotExistsException $exception) {
        }
        try {
            if (\class_exists(RESTAPIModule::class) && App::getModule(RESTAPIModule::class)->isEnabled()) {
                $this->initServices(\dirname(__DIR__), '/ConditionalOnModule/RESTAPI');
            }
        } catch (ComponentNotExistsException $exception) {
        }
        try {
            if (\class_exists(CustomPostsModule::class) && App::getModule(CustomPostsModule::class)->isEnabled()) {
                $this->initServices(\dirname(__DIR__), '/ConditionalOnModule/CustomPosts');
                $this->initSchemaServices(\dirname(__DIR__), $skipSchema || \in_array(\PoPCMSSchema\CustomPosts\Module::class, $skipSchemaModuleClasses), '/ConditionalOnModule/CustomPosts');
                try {
                    if (\class_exists(APIModule::class) && App::getModule(APIModule::class)->isEnabled()) {
                        $this->initServices(\dirname(__DIR__), '/ConditionalOnModule/CustomPosts/ConditionalOnModule/API');
                    }
                } catch (ComponentNotExistsException $exception) {
                }
                try {
                    if (\class_exists(RESTAPIModule::class) && App::getModule(RESTAPIModule::class)->isEnabled()) {
                        $this->initServices(\dirname(__DIR__), '/ConditionalOnModule/CustomPosts/ConditionalOnModule/RESTAPI');
                    }
                } catch (ComponentNotExistsException $exception) {
                }
            }
        } catch (ComponentNotExistsException $exception) {
        }
    }
}
