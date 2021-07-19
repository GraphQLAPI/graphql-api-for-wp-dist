<?php

declare (strict_types=1);
namespace PoP\ComponentModel\ModuleProcessors;

use PoP\ComponentModel\Facades\ModuleProcessors\ModuleProcessorManagerFacade;
abstract class AbstractModuleDecoratorProcessor implements \PoP\ComponentModel\ModuleProcessors\ModuleDecoratorProcessorInterface
{
    use ModulePathProcessorTrait;
    //-------------------------------------------------
    // PROTECTED Functions
    //-------------------------------------------------
    protected final function getModuleProcessor(array $module)
    {
        return $this->getModuleProcessordecorator($module);
    }
    protected final function getModuleProcessordecorator(array $module)
    {
        $processor = $this->getDecoratedmoduleProcessor($module);
        return $this->getModuledecoratorprocessorManager()->getProcessordecorator($processor);
    }
    protected final function getDecoratedmoduleProcessor(array $module)
    {
        $moduleprocessor_manager = ModuleProcessorManagerFacade::getInstance();
        return $moduleprocessor_manager->getProcessor($module);
    }
    protected function getModuledecoratorprocessorManager()
    {
        return null;
    }
    //-------------------------------------------------
    // PUBLIC Functions
    //-------------------------------------------------
    public final function getAllSubmodules(array $module) : array
    {
        $moduleprocessor_manager = ModuleProcessorManagerFacade::getInstance();
        $processor = $moduleprocessor_manager->getProcessor($module);
        return $processor->getAllSubmodules($module);
    }
}
