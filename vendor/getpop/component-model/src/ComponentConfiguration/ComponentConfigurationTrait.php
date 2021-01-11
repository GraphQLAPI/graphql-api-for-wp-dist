<?php

declare(strict_types=1);

namespace PoP\ComponentModel\ComponentConfiguration;

use PoP\Hooks\Facades\HooksAPIFacade;

/**
 * Make the ComponentConfiguration be configurable
 */
trait ComponentConfigurationTrait
{
    protected static $configuration = [];
    protected static $initialized = [];

    public static function setConfiguration(array $configuration): void
    {
        self::$configuration = $configuration;
    }
    public static function hasConfigurationValue(string $option)
    {
        return isset(self::$configuration[$option]);
    }
    public static function getConfigurationValue(string $option)
    {
        return self::$configuration[$option];
    }

    protected static function maybeInitializeConfigurationValue(
        string $envVariable,
        &$selfProperty,
        $defaultValue = null,
        ?callable $callback = null,
        bool $useHook = true
    ): void {
        if (!isset(self::$initialized[$envVariable])) {
            self::$initialized[$envVariable] = true;

            $selfProperty = $defaultValue;
            // Initialize from configuration, environment or hook
            if (self::hasConfigurationValue($envVariable)) {
                // Priority: option has been set in the $configuration
                $selfProperty = self::getConfigurationValue($envVariable);
            } else {
                // Get the value from the environment function
                if (getenv($envVariable) !== false) {
                    $selfProperty = getenv($envVariable);
                    // Modify the type of the variable, from string to bool/int/array
                    if ($callback) {
                        $selfProperty = $callback($selfProperty);
                    }
                }
                // Allow to override the value with a hook
                if ($useHook) {
                    $hooksAPI = HooksAPIFacade::getInstance();
                    $class = \get_called_class();
                    $hookName = ComponentConfigurationHelpers::getHookName($class, $envVariable);
                    $selfProperty = $hooksAPI->applyFilters($hookName, $selfProperty, $class, $envVariable);
                }
            }
        }
    }
}
