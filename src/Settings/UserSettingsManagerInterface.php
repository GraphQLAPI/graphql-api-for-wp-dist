<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\Settings;

interface UserSettingsManagerInterface
{
    /**
     * Timestamp of latest executed write to DB, concerning plugin activation,
     * module enabled/disabled, user settings updated, to refresh the Service
     * Container
     */
    public function getContainerTimestamp(): int;
    /**
     * Timestamp of latest executed write to DB, concerning CPT entity created
     * or modified (such as Schema Configuration, ACL, etc), to refresh
     * the GraphQL schema
     */
    public function getOperationalTimestamp(): int;
    /**
     * Store the current time to indicate the latest executed write to DB,
     * concerning plugin activation, module enabled/disabled, user settings updated,
     * to refresh the Service Container
     */
    public function storeContainerTimestamp(): void;
    /**
     * Store the current time to indicate the latest executed write to DB,
     * concerning CPT entity created or modified (such as Schema Configuration,
     * ACL, etc), to refresh the GraphQL schema
     */
    public function storeOperationalTimestamp(): void;
    /**
     * Remove the timestamp
     */
    public function removeTimestamps(): void;
    /**
     * @param string $module
     * @param string $option
     */
    public function hasSetting($module, $option): bool;
    /**
     * @return mixed
     * @param string $module
     * @param string $option
     */
    public function getSetting($module, $option);
    /**
     * @param mixed $value
     * @param string $module
     * @param string $option
     */
    public function setSetting($module, $option, $value): void;
    /**
     * @param array<string,mixed> $optionValues
     * @param string $module
     */
    public function setSettings($module, $optionValues): void;
    /**
     * @param string $moduleID
     */
    public function hasSetModuleEnabled($moduleID): bool;
    /**
     * @param string $moduleID
     */
    public function isModuleEnabled($moduleID): bool;
    /**
     * @param string $moduleID
     * @param bool $isEnabled
     */
    public function setModuleEnabled($moduleID, $isEnabled): void;
    /**
     * @param array<string,bool> $moduleIDValues
     */
    public function setModulesEnabled($moduleIDValues): void;
}
