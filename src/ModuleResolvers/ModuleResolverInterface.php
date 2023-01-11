<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\ModuleResolvers;

interface ModuleResolverInterface
{
    /**
     * @return string[]
     */
    public function getModulesToResolve(): array;
    /**
     * The priority to display the modules from this resolver in the Modules page.
     * The higher the number, the earlier it shows
     */
    public function getPriority(): int;
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
    /**
     * @return array<string[]> List of entries that must be satisfied, each entry is an array where at least 1 module must be satisfied
     * @param string $module
     */
    public function getDependedModuleLists($module): array;
    /**
     * Indicates if a module has all requirements satisfied (such as version of WordPress) to be enabled
     * @param string $module
     */
    public function areRequirementsSatisfied($module): bool;
    /**
     * Can the module be disabled by the user?
     * @param string $module
     */
    public function canBeDisabled($module): bool;
    /**
     * @param string $module
     */
    public function isHidden($module): bool;
    /**
     * @param string $module
     */
    public function areSettingsHidden($module): bool;
    /**
     * @param string $module
     */
    public function getID($module): string;
    /**
     * @param string $module
     */
    public function getName($module): string;
    /**
     * @param string $module
     */
    public function getDescription($module): string;
    /**
     * @param string $module
     */
    public function hasSettings($module): bool;
    /**
     * The type of the module
     * @param string $module
     */
    public function getModuleType($module): string;
    /**
     * Array with the inputs to show as settings for the module:
     * - name
     * - type (string, bool, int)
     * - possible values
     * - is multiple
     *
     * @return array<array<string,mixed>> List of settings for the module, each entry is an array with property => value
     * @param string $module
     */
    public function getSettings($module): array;
    /**
     * Default value for an option set by the module
     * @param string $module
     * @param string $option
     */
    public function getSettingOptionName($module, $option): string;
    /**
     * Indicate if the given value is valid for that option
     * @param mixed $value
     * @param string $module
     * @param string $option
     */
    public function isValidValue($module, $option, $value): bool;
    /**
     * Default value for an option set by the module
     * @return mixed
     * @param string $module
     * @param string $option
     */
    public function getSettingsDefaultValue($module, $option);
    /**
     * @param string $module
     */
    public function isEnabledByDefault($module): bool;
    // public function getURL(string $module): ?string;
    /**
     * @param string $module
     */
    public function getSlug($module): string;
    /**
     * Does the module have HTML Documentation?
     * @param string $module
     */
    public function hasDocumentation($module): bool;
    /**
     * HTML Documentation for the module
     * @param string $module
     */
    public function getDocumentation($module): ?string;
}
