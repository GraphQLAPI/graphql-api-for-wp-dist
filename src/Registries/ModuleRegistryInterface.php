<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\Registries;

use GraphQLAPI\GraphQLAPI\Exception\ModuleNotExistsException;
use GraphQLAPI\GraphQLAPI\ModuleResolvers\ModuleResolverInterface;

interface ModuleRegistryInterface
{
    /**
     * @param \GraphQLAPI\GraphQLAPI\ModuleResolvers\ModuleResolverInterface $moduleResolver
     */
    public function addModuleResolver($moduleResolver): void;
    /**
     * @return string[]
     * @param bool $onlyEnabled
     * @param bool $onlyHasSettings
     * @param bool $onlyVisible
     * @param bool $onlyWithVisibleSettings
     */
    public function getAllModules($onlyEnabled = false, $onlyHasSettings = false, $onlyVisible = true, $onlyWithVisibleSettings = false): array;
    /**
     * @throws ModuleNotExistsException If module does not exist
     * @param string $module
     */
    public function getModuleResolver($module): ModuleResolverInterface;
    /**
     * @param string $module
     */
    public function isModuleEnabled($module): bool;
    /**
     * If a module was disabled by the user, then the user can enable it.
     * If it is disabled because its requirements are not satisfied,
     * or its dependencies themselves disabled, then it cannot be enabled by the user.
     * @param string $module
     */
    public function canModuleBeEnabled($module): bool;
    /**
     * Used to indicate that the dependency on the module is on its being disabled, not enabled
     * @param string $dependedModule
     */
    public function getInverseDependency($dependedModule): string;
    /**
     * Indicate if the dependency is on its being disabled, not enabled
     * @param string $dependedModule
     */
    public function isInverseDependency($dependedModule): bool;
}
