<?php

declare (strict_types=1);
namespace PoP\Root\Module;

use PoP\Root\App;
use PoP\Root\Helpers\ClassHelpers;
use PoP\Root\Module\ModuleInterface;
abstract class AbstractModuleConfiguration implements \PoP\Root\Module\ModuleConfigurationInterface
{
    /**
     * @var array<string, mixed>
     */
    protected $configuration;
    /**
     * @param array<string,mixed> $configuration
     */
    public final function __construct(array $configuration)
    {
        $this->configuration = $configuration;
    }
    /**
     * @param string $envVariable
     */
    public function hasConfigurationValue($envVariable) : bool
    {
        return \array_key_exists($envVariable, $this->configuration);
    }
    /**
     * @return mixed
     * @param string $envVariable
     */
    public function getConfigurationValue($envVariable)
    {
        return $this->configuration[$envVariable] ?? null;
    }
    /**
     * @param mixed $defaultValue
     * @return mixed
     * @param string $envVariable
     * @param callable|null $callback
     */
    protected function retrieveConfigurationValueOrUseDefault($envVariable, $defaultValue, $callback = null)
    {
        // Initialized from configuration? Then use that one directly.
        if ($this->hasConfigurationValue($envVariable)) {
            return $this->getConfigurationValue($envVariable);
        }
        /**
         * Otherwise, initialize from environment.
         * First set the default value, for if there's no env var defined.
         */
        $this->configuration[$envVariable] = $defaultValue;
        /**
         * Get the value from the environment, converting it
         * to the appropriate type via a callback function.
         */
        $envValue = \getenv($envVariable);
        if ($envValue !== \false) {
            // Modify the type of the variable, from string to bool/int/array
            $this->configuration[$envVariable] = $callback !== null ? $callback($envValue) : $envValue;
        }
        if (!$this->enableHook($envVariable)) {
            return $this->configuration[$envVariable];
        }
        $class = $this->getModuleClass();
        $hookName = \PoP\Root\Module\ModuleConfigurationHelpers::getHookName($class, $envVariable);
        $this->configuration[$envVariable] = App::applyFilters($hookName, $this->configuration[$envVariable], $class, $envVariable);
        return $this->configuration[$envVariable];
    }
    /**
     * @param string $envVariable
     */
    protected function enableHook($envVariable) : bool
    {
        return \true;
    }
    /**
     * Package's Module class, of type ModuleInterface.
     * By standard, it is "NamespaceOwner\Project\Module::class"
     *
     * @phpstan-return class-string<ModuleInterface>
     */
    protected function getModuleClass() : string
    {
        $classNamespace = ClassHelpers::getClassPSR4Namespace(\get_called_class());
        /** @var class-string<ModuleInterface> */
        return $classNamespace . '\\Module';
    }
}
