<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\Registries;

use GraphQLAPI\GraphQLAPI\Exception\ModuleNotExistsException;
use GraphQLAPI\GraphQLAPI\Facades\UserSettingsManagerFacade;
use GraphQLAPI\GraphQLAPI\ModuleResolvers\ModuleResolverInterface;
use GraphQLAPI\GraphQLAPI\Settings\UserSettingsManagerInterface;

class ModuleRegistry implements ModuleRegistryInterface
{
    /**
     * @var \GraphQLAPI\GraphQLAPI\Settings\UserSettingsManagerInterface|null
     */
    private $userSettingsManager;

    /**
     * @param \GraphQLAPI\GraphQLAPI\Settings\UserSettingsManagerInterface $userSettingsManager
     */
    public function setUserSettingsManager($userSettingsManager): void
    {
        $this->userSettingsManager = $userSettingsManager;
    }
    protected function getUserSettingsManager(): UserSettingsManagerInterface
    {
        return $this->userSettingsManager = $this->userSettingsManager ?? UserSettingsManagerFacade::getInstance();
    }

    /**
     * @var ModuleResolverInterface[]
     */
    protected $moduleResolvers = [];

    /**
     * @var array<string,ModuleResolverInterface>
     */
    protected $modulesResolversByModuleAndPriority = [];

    /**
     * @var array<string,ModuleResolverInterface>
     */
    protected $moduleResolversByModule = [];

    /**
     * @param \GraphQLAPI\GraphQLAPI\ModuleResolvers\ModuleResolverInterface $moduleResolver
     */
    public function addModuleResolver($moduleResolver): void
    {
        $this->moduleResolvers[] = $moduleResolver;
        foreach ($moduleResolver->getModulesToResolve() as $module) {
            $this->moduleResolversByModule[$module] = $moduleResolver;
        }
    }
    /**
     * Order the moduleResolvers by priority
     * @return array<string,ModuleResolverInterface>
     */
    protected function getModuleResolversByModuleAndPriority(): array
    {
        if (empty($this->modulesResolversByModuleAndPriority)) {
            $moduleResolvers = $this->moduleResolvers;
            uasort($moduleResolvers, function (ModuleResolverInterface $a, ModuleResolverInterface $b): int {
                return $b->getPriority() <=> $a->getPriority();
            });
            foreach ($moduleResolvers as $moduleResolver) {
                foreach ($moduleResolver->getModulesToResolve() as $module) {
                    $this->modulesResolversByModuleAndPriority[$module] = $moduleResolver;
                }
            }
        }
        return $this->modulesResolversByModuleAndPriority;
    }

    /**
     * @return string[]
     * @param bool $onlyEnabled
     * @param bool $onlyHasSettings
     * @param bool $onlyVisible
     * @param bool $onlyWithVisibleSettings
     */
    public function getAllModules($onlyEnabled = false, $onlyHasSettings = false, $onlyVisible = false, $onlyWithVisibleSettings = false): array
    {
        $modules = array_keys($this->getModuleResolversByModuleAndPriority());
        if ($onlyEnabled) {
            $modules = array_filter(
                $modules,
                function (string $module) {
                    return $this->isModuleEnabled($module);
                }
            );
        }
        if ($onlyHasSettings) {
            $modules = array_filter(
                $modules,
                function (string $module) {
                    return $this->getModuleResolver($module)->hasSettings($module);
                }
            );
        }
        if ($onlyVisible) {
            $modules = array_filter(
                $modules,
                function (string $module) {
                    return !$this->getModuleResolver($module)->isHidden($module);
                }
            );
        }
        if ($onlyWithVisibleSettings) {
            $modules = array_filter(
                $modules,
                function (string $module) {
                    return !$this->getModuleResolver($module)->areSettingsHidden($module);
                }
            );
        }
        return array_values($modules);
    }
    /**
     * @throws ModuleNotExistsException If module does not exist
     * @param string $module
     */
    public function getModuleResolver($module): ModuleResolverInterface
    {
        if (!isset($this->moduleResolversByModule[$module])) {
            throw new ModuleNotExistsException(sprintf(
                \__('Module \'%s\' does not exist', 'graphql-api'),
                $module
            ));
        }
        return $this->moduleResolversByModule[$module];
    }
    /**
     * @param string $module
     */
    public function isModuleEnabled($module): bool
    {
        $moduleResolver = $this->getModuleResolver($module);
        // Check that all requirements are satisfied
        if (!$moduleResolver->areRequirementsSatisfied($module)) {
            return false;
        }
        // Check that all depended-upon modules are enabled
        if (!$this->areDependedModulesEnabled($module)) {
            return false;
        }
        // If the user can't disable it, then it must be enabled
        if (!$moduleResolver->canBeDisabled($module)) {
            return true;
        }
        $moduleID = $moduleResolver->getID($module);
        // Check if the value has been saved on the DB
        if ($this->getUserSettingsManager()->hasSetModuleEnabled($moduleID)) {
            return $this->getUserSettingsManager()->isModuleEnabled($moduleID);
        }
        // Get the default value from the resolver
        return $moduleResolver->isEnabledByDefault($module);
    }

    /**
     * Indicate if a module's depended-upon modules are all enabled
     * @param string $module
     */
    protected function areDependedModulesEnabled($module): bool
    {
        $moduleResolver = $this->getModuleResolver($module);
        // Check that all depended-upon modules are enabled
        $dependedModuleLists = $moduleResolver->getDependedModuleLists($module);
        /**
         * This is a list of lists of modules, as to model both OR and AND conditions
         * The innermost list is an OR: if any module is enabled, then the condition succeeds
         * The outermost list is an AND: all list must succeed for this module to be enabled
         * Eg: the Schema Configuration is enabled if either the Custom Endpoints or
         * the Persisted Query are enabled:
         * [
         *   [self::PERSISTED_QUERIES, self::CUSTOM_ENDPOINTS],
         * ]
         */
        foreach ($dependedModuleLists as $dependedModuleList) {
            if (!$dependedModuleList) {
                continue;
            }
            $dependedModuleListEnabled = array_map(
                function (string $dependedModule): bool {
                    // Check if it has the "inverse" token at the beginning,
                    // then it depends on the module being disabled, not enabled
                    if (substr($dependedModule, 0, strlen(ModuleRegistryTokens::INVERSE_DEPENDENCY)) === ModuleRegistryTokens::INVERSE_DEPENDENCY) {
                        // The module is everything after the token
                        $dependedModule = substr($dependedModule, strlen(ModuleRegistryTokens::INVERSE_DEPENDENCY));
                        return !$this->isModuleEnabled($dependedModule);
                    }
                    return $this->isModuleEnabled($dependedModule);
                },
                $dependedModuleList
            );
            if (!in_array(true, $dependedModuleListEnabled)) {
                return false;
            }
        }
        return true;
    }

    /**
     * If a module was disabled by the user, then the user can enable it.
     * If it is disabled because its requirements are not satisfied,
     * or its dependencies themselves disabled, then it cannot be enabled by the user.
     * @param string $module
     */
    public function canModuleBeEnabled($module): bool
    {
        $moduleResolver = $this->getModuleResolver($module);
        // Check that all requirements are satisfied
        if (!$moduleResolver->areRequirementsSatisfied($module)) {
            return false;
        }
        // Check that all depended-upon modules are enabled
        if (!$this->areDependedModulesEnabled($module)) {
            return false;
        }
        return true;
    }

    /**
     * Used to indicate that the dependency on the module is on its being disabled, not enabled
     * @param string $dependedModule
     */
    public function getInverseDependency($dependedModule): string
    {
        // Check if it already has the "inverse" token at the beginning,
        // then take it back to normal
        if ($this->isInverseDependency($dependedModule)) {
            // The module is everything after the token "!"
            return substr($dependedModule, strlen(ModuleRegistryTokens::INVERSE_DEPENDENCY));
        }
        // Add "!" before the module
        return ModuleRegistryTokens::INVERSE_DEPENDENCY . $dependedModule;
    }
    /**
     * Indicate if the dependency is on its being disabled, not enabled
     * @param string $dependedModule
     */
    public function isInverseDependency($dependedModule): bool
    {
        return substr($dependedModule, 0, strlen(ModuleRegistryTokens::INVERSE_DEPENDENCY)) === ModuleRegistryTokens::INVERSE_DEPENDENCY;
    }
}
