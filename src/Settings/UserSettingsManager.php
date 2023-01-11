<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\Settings;

use GraphQLAPI\GraphQLAPI\Facades\Registries\SystemModuleRegistryFacade;

class UserSettingsManager implements UserSettingsManagerInterface
{
    private const TIMESTAMP_CONTAINER = 'container';
    private const TIMESTAMP_OPERATIONAL = 'operational';

    /**
     * Cache the values in memory
     *
     * @var array<string,array<string,mixed>>
     */
    protected $options = [];

    /**
     * Timestamp of latest executed write to DB, concerning plugin activation,
     * module enabled/disabled, user settings updated.
     *
     * If there is not timestamp yet, then we just installed the plugin.
     *
     * In that case, we must return a random `time()` timestamp and not
     * a fixed value such as `0`, because the service container
     * will be generated on each interaction with WordPress,
     * including WP-CLI.
     *
     * Using `0` as the default value, when installing the plugin
     * and an extension via WP-CLI (before accessing wp-admin)
     * it will throw errors, because after installing the main plugin
     * the container cache is generated and cached with timestamp `0`,
     * and it would be loaded again when installing the extension,
     * however the cache does not contain the services from the extension.
     *
     * By providing `time()`, the cached service container is always
     * a one-time-use before accessing the wp-admin and
     * having a new timestamp generated via `purgeContainer`.
     * @param string $key
     */
    protected function getTimestamp($key): int
    {
        $timestamps = \get_option(Options::TIMESTAMPS, [$key => time()]);
        return (int) $timestamps[$key];
    }
    /**
     * Static timestamp, reflecting when the service container has been regenerated.
     * Should change not so often
     */
    public function getContainerTimestamp(): int
    {
        return $this->getTimestamp(self::TIMESTAMP_CONTAINER);
    }
    /**
     * Dynamic timestamp, reflecting when new entities modifying the schema are
     * added to the DB. Can change often
     */
    public function getOperationalTimestamp(): int
    {
        return $this->getTimestamp(self::TIMESTAMP_OPERATIONAL);
    }
    /**
     * Store the current time to indicate the latest executed write to DB,
     * concerning plugin activation, module enabled/disabled, user settings updated,
     * to refresh the Service Container.
     *
     * When this value is updated, the "operational" timestamp is also updated.
     */
    public function storeContainerTimestamp(): void
    {
        $time = time();
        $timestamps = [
            self::TIMESTAMP_CONTAINER => $time,
            self::TIMESTAMP_OPERATIONAL => $time,
        ];
        \update_option(Options::TIMESTAMPS, $timestamps);
    }
    /**
     * Store the current time to indicate the latest executed write to DB,
     * concerning CPT entity created or modified (such as Schema Configuration,
     * ACL, etc), to refresh the GraphQL schema
     */
    public function storeOperationalTimestamp(): void
    {
        $timestamps = [
            self::TIMESTAMP_CONTAINER => $this->getContainerTimestamp(),
            self::TIMESTAMP_OPERATIONAL => time(),
        ];
        \update_option(Options::TIMESTAMPS, $timestamps);
    }
    /**
     * Remove the timestamp
     */
    public function removeTimestamps(): void
    {
        \delete_option(Options::TIMESTAMPS);
    }

    /**
     * @param string $module
     * @param string $option
     */
    public function hasSetting($module, $option): bool
    {
        $moduleRegistry = SystemModuleRegistryFacade::getInstance();
        $moduleResolver = $moduleRegistry->getModuleResolver($module);
        $item = $moduleResolver->getSettingOptionName($module, $option);
        return $this->hasItem(Options::SETTINGS, $item);
    }

    /**
     * @return mixed
     * @param string $module
     * @param string $option
     */
    public function getSetting($module, $option)
    {
        $moduleRegistry = SystemModuleRegistryFacade::getInstance();
        $moduleResolver = $moduleRegistry->getModuleResolver($module);

        // If the item is saved in the DB, retrieve it
        $item = $moduleResolver->getSettingOptionName($module, $option);
        if ($this->hasItem(Options::SETTINGS, $item)) {
            return $this->getItem(Options::SETTINGS, $item);
        }

        // Otherwise, return the default value
        return $moduleResolver->getSettingsDefaultValue($module, $option);
    }

    /**
     * @param mixed $value
     * @param string $module
     * @param string $option
     */
    public function setSetting($module, $option, $value): void
    {
        $moduleRegistry = SystemModuleRegistryFacade::getInstance();
        $moduleResolver = $moduleRegistry->getModuleResolver($module);

        $item = $moduleResolver->getSettingOptionName($module, $option);

        $this->setOptionItem(Options::SETTINGS, $item, $value);
    }

    /**
     * @param array<string,mixed> $optionValues
     * @param string $module
     */
    public function setSettings($module, $optionValues): void
    {
        $moduleRegistry = SystemModuleRegistryFacade::getInstance();
        $moduleResolver = $moduleRegistry->getModuleResolver($module);

        $itemValues = [];
        foreach ($optionValues as $option => $value) {
            $item = $moduleResolver->getSettingOptionName($module, $option);
            $itemValues[$item] = $value;
        }

        $this->setOptionItems(Options::SETTINGS, $itemValues);
    }

    /**
     * @param string $moduleID
     */
    public function hasSetModuleEnabled($moduleID): bool
    {
        return $this->hasItem(Options::MODULES, $moduleID);
    }

    /**
     * @param string $moduleID
     */
    public function isModuleEnabled($moduleID): bool
    {
        return (bool) $this->getItem(Options::MODULES, $moduleID);
    }

    /**
     * @param string $moduleID
     * @param bool $isEnabled
     */
    public function setModuleEnabled($moduleID, $isEnabled): void
    {
        $this->setOptionItem(Options::MODULES, $moduleID, $isEnabled);
    }

    /**
     * @param mixed $value
     * @param string $optionName
     * @param string $item
     */
    protected function setOptionItem($optionName, $item, $value): void
    {
        $this->storeItem($optionName, $item, $value);

        // Update the timestamp
        $this->storeContainerTimestamp();
    }

    /**
     * @param array<string,mixed> $itemValues
     * @param string $optionName
     */
    protected function setOptionItems($optionName, $itemValues): void
    {
        $this->storeItems($optionName, $itemValues);

        // Update the timestamp
        $this->storeContainerTimestamp();
    }

    /**
     * @param array<string,bool> $moduleIDValues
     */
    public function setModulesEnabled($moduleIDValues): void
    {
        $this->storeItems(Options::MODULES, $moduleIDValues);

        // Update the timestamp
        $this->storeContainerTimestamp();
    }

    /**
     * Get the stored value for the option under the group
     * @return mixed
     * @param string $optionName
     * @param string $item
     */
    protected function getItem($optionName, $item)
    {
        $this->maybeLoadOptions($optionName);
        return $this->options[$optionName][$item];
    }

    /**
     * Is there a stored value for the option under the group
     * @param string $optionName
     * @param string $item
     */
    protected function hasItem($optionName, $item): bool
    {
        $this->maybeLoadOptions($optionName);
        return isset($this->options[$optionName][$item]);
    }

    /**
     * Load the options from the DB
     * @param string $optionName
     */
    protected function maybeLoadOptions($optionName): void
    {
        // Lazy load the options
        if (!isset($this->options[$optionName])) {
            $this->options[$optionName] = \get_option($optionName, []);
        }
    }

    /**
     * Store the options in the DB
     * @param mixed $value
     * @param string $optionName
     * @param string $item
     */
    protected function storeItem($optionName, $item, $value): void
    {
        $this->storeItems($optionName, [$item => $value]);
    }

    /**
     * Store the options in the DB
     *
     * @param array<string,mixed> $itemValues
     * @param string $optionName
     */
    protected function storeItems($optionName, $itemValues): void
    {
        $this->maybeLoadOptions($optionName);
        // Change the values of the items
        $this->options[$optionName] = array_merge(
            $this->options[$optionName],
            $itemValues
        );
        // Save to the DB
        \update_option($optionName, $this->options[$optionName]);
    }
}
