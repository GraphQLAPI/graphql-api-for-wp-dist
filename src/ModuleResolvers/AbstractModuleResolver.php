<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\ModuleResolvers;

use GraphQLAPI\GraphQLAPI\Registries\ModuleRegistryInterface;
use PoP\Root\Services\BasicServiceTrait;

abstract class AbstractModuleResolver implements ModuleResolverInterface
{
    use BasicServiceTrait;

    /**
     * @var \GraphQLAPI\GraphQLAPI\Registries\ModuleRegistryInterface|null
     */
    private $moduleRegistry;

    /**
     * @param \GraphQLAPI\GraphQLAPI\Registries\ModuleRegistryInterface $moduleRegistry
     */
    final public function setModuleRegistry($moduleRegistry): void
    {
        $this->moduleRegistry = $moduleRegistry;
    }
    final protected function getModuleRegistry(): ModuleRegistryInterface
    {
        /** @var ModuleRegistryInterface */
        return $this->moduleRegistry = $this->moduleRegistry ?? $this->instanceManager->getInstance(ModuleRegistryInterface::class);
    }

    /**
     * The priority to display the modules from this resolver in the Modules page.
     * The higher the number, the earlier it shows
     */
    public function getPriority(): int
    {
        return 10;
    }

    /**
     * @return array<string[]> List of entries that must be satisfied, each entry is an array where at least 1 module must be satisfied
     * @param string $module
     */
    public function getDependedModuleLists($module): array
    {
        return [];
    }

    /**
     * @param string $module
     */
    public function areRequirementsSatisfied($module): bool
    {
        return true;
    }

    /**
     * @param string $module
     */
    public function canBeDisabled($module): bool
    {
        return true;
    }

    /**
     * @param string $module
     */
    public function isHidden($module): bool
    {
        return false;
    }

    /**
     * @param string $module
     */
    public function areSettingsHidden($module): bool
    {
        return false;
    }

    /**
     * @param string $module
     */
    public function getID($module): string
    {
        $moduleID = strtolower($module);
        // $moduleID = strtolower(str_replace(
        //     ['/', ' '],
        //     '-',
        //     $moduleID
        // ));
        /**
         * Replace all the "\" from the namespace with "_"
         * Otherwise there is problem when encoding/decoding,
         * since "\" is encoded as "\\".
         * Do not use "." because it can't be used as an HTML ID
         */
        return str_replace(
            '\\', //['\\', '/', ' '],
            '_',
            $moduleID
        );
    }

    /**
     * @param string $module
     */
    public function getDescription($module): string
    {
        return '';
    }

    /**
     * Name of the setting item, to store in the DB
     * @param string $module
     * @param string $option
     */
    public function getSettingOptionName($module, $option): string
    {
        // Use slug to remove the "\" which can create trouble
        return $this->getSlug($module) . '_' . $option;
    }

    /**
     * @param string $module
     */
    public function hasSettings($module): bool
    {
        return !empty($this->getSettings($module));
    }

    /**
     * Array with key as the name of the setting, and value as its definition:
     * type (input, checkbox, select), enum values (if it is a select)
     *
     * @return array<array<string,mixed>> List of settings for the module, each entry is an array with property => value
     * @param string $module
     */
    public function getSettings($module): array
    {
        return [];
    }

    /**
     * Indicate if the given value is valid for that option
     * @param mixed $value
     * @param string $module
     * @param string $option
     */
    public function isValidValue($module, $option, $value): bool
    {
        return true;
    }

    /**
     * Default value for an option set by the module
     * @return mixed
     * @param string $module
     * @param string $option
     */
    public function getSettingsDefaultValue($module, $option)
    {
        return null;
    }

    /**
     * @param string $module
     */
    public function isEnabledByDefault($module): bool
    {
        return true;
    }

    // /**
    //  * By default, point to https://graphql-api.com/modules/{component-slug}
    //  */
    // public function getURL(string $module): ?string
    // {
    //     $moduleSlug = $this->getSlug($module);
    //     $moduleURLBase = $this->getURLBase($module);
    //     return \trailingslashit($moduleURLBase) . $moduleSlug . '/';
    // }
    /**
     * By default, the slug is the module's name, without the owner/package
     * @param string $module
     */
    public function getSlug($module): string
    {
        $pos = strrpos($module, '\\');
        if ($pos !== false) {
            return substr($module, $pos + strlen('\\'));
        }
        return $module;
    }

    // /**
    //  * Return the default URL base for the module, defined through configuration
    //  * By default, point to https://graphql-api.com/modules/{component-slug}
    //  */
    // protected function getURLBase(string $module): string
    // {
    //     /** @var ModuleConfiguration */
    //     $moduleConfiguration = App::getModule(Module::class)->getConfiguration();
    //     return $moduleConfiguration->getModuleURLBase();
    // }
    /**
     * Does the module have HTML Documentation?
     * @param string $module
     */
    public function hasDocumentation($module): bool
    {
        return !empty($this->getDocumentation($module));
    }

    /**
     * HTML Documentation for the module
     * @param string $module
     */
    public function getDocumentation($module): ?string
    {
        return null;
    }
}
