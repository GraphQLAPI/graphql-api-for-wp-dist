<?php

declare (strict_types=1);
namespace PoP\Root\StateManagers;

use PoP\Root\Module\ModuleInterface;
use PoP\Root\Exception\ComponentNotExistsException;
interface ModuleManagerInterface
{
    /**
     * Register and initialize a module
     *
     * @phpstan-param class-string<ModuleInterface> $moduleClass
     * @param string $moduleClass
     */
    public function register($moduleClass) : ModuleInterface;
    /**
     * @phpstan-param class-string<ModuleInterface> $moduleClass
     * @throws ComponentNotExistsException If the class of the module does not exist or has not been initialized
     * @param string $moduleClass
     */
    public function getModule($moduleClass) : ModuleInterface;
    /**
     * Configure modules
     */
    public function configureComponents() : void;
    /**
     * Boot all modules
     */
    public function bootSystem() : void;
    /**
     * Boot all modules
     */
    public function moduleLoaded() : void;
    /**
     * Boot all modules
     */
    public function boot() : void;
    /**
     * Boot all modules
     */
    public function afterBoot() : void;
}
