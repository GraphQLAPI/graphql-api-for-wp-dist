<?php

declare (strict_types=1);
namespace PoP\Root;

use PoP\Root\Constants\HookNames;
use PoP\Root\Dotenv\DotenvBuilderFactory;
use PoP\Root\Facades\SystemCompilerPassRegistryFacade;
use PoP\Root\Module\ModuleInterface;
use PrefixedByPoP\Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
/**
 * Application Loader
 */
class AppLoader implements \PoP\Root\AppLoaderInterface
{
    /**
     * Has the module been initialized?
     *
     * @var string[]
     * @phpstan-var array<class-string<ModuleInterface>>
     */
    protected $initializedModuleClasses = [];
    /**
     * Module in their initialization order
     *
     * @var string[]
     * @phpstan-var array<class-string<ModuleInterface>>
     */
    protected $orderedModuleClasses = [];
    /**
     * Module classes to be initialized
     *
     * @var string[]
     * @phpstan-var array<class-string<ModuleInterface>>
     */
    protected $moduleClassesToInitialize = [];
    /**
     * [key]: Module class, [value]: Configuration
     *
     * @var array<string,array<string,mixed>>
     * @phpstan-var array<class-string<ModuleInterface>,array<string,mixed>>
     */
    protected $moduleClassConfiguration = [];
    /**
     * [key]: State key, [value]: Value
     *
     * @var array<string,mixed>
     */
    protected $initialAppState = [];
    /**
     * List of `Module` class which must not initialize their Schema services
     *
     * @var string[]
     * @phpstan-var array<class-string<ModuleInterface>>
     */
    protected $skipSchemaModuleClasses = [];
    /**
     * Cache if a module must skipSchema or not, stored under its class
     *
     * @var array<string,bool>
     * @phpstan-var array<class-string<ModuleInterface>,bool>
     */
    protected $skipSchemaForModuleCache = [];
    /**
     * Inject Compiler Passes to boot the System (eg: when testing)
     *
     * @var array<class-string<CompilerPassInterface>>
     */
    protected $systemContainerCompilerPassClasses = [];
    /**
     * Inject Compiler Passes to boot the Application (eg: when testing)
     *
     * @var array<class-string<CompilerPassInterface>>
     */
    protected $applicationContainerCompilerPassClasses = [];
    /**
     * Add Module classes to be initialized
     *
     * @param array<class-string<ModuleInterface>> $moduleClasses List of `Module` class to initialize
     */
    public function addModuleClassesToInitialize($moduleClasses) : void
    {
        $this->moduleClassesToInitialize = \array_merge($this->moduleClassesToInitialize, $moduleClasses);
    }
    /**
     * Add configuration for the Module classes
     *
     * @param array<string,array<string,mixed>> $moduleClassConfiguration [key]: Module class, [value]: Configuration
     * @phpstan-param array<class-string<ModuleInterface>,array<string,mixed>> $moduleClassConfiguration
     */
    public function addModuleClassConfiguration($moduleClassConfiguration) : void
    {
        // Allow to override entries under each Module
        foreach ($moduleClassConfiguration as $moduleClass => $moduleConfiguration) {
            $this->moduleClassConfiguration[$moduleClass] = $this->moduleClassConfiguration[$moduleClass] ?? [];
            $this->moduleClassConfiguration[$moduleClass] = \array_merge($this->moduleClassConfiguration[$moduleClass], $moduleConfiguration);
        }
    }
    /**
     * Inject Compiler Passes to boot the System (eg: when testing)
     *
     * @param array<class-string<CompilerPassInterface>> $systemContainerCompilerPassClasses List of `CompilerPass` class to initialize
     */
    public function addSystemContainerCompilerPassClasses($systemContainerCompilerPassClasses) : void
    {
        $this->systemContainerCompilerPassClasses = \array_merge($this->systemContainerCompilerPassClasses, $systemContainerCompilerPassClasses);
    }
    /**
     * Inject Compiler Passes to boot the Application (eg: when testing)
     *
     * @param array<class-string<CompilerPassInterface>> $applicationContainerCompilerPassClasses List of `CompilerPass` class to initialize
     */
    public function addApplicationContainerCompilerPassClasses($applicationContainerCompilerPassClasses) : void
    {
        $this->applicationContainerCompilerPassClasses = \array_merge($this->applicationContainerCompilerPassClasses, $applicationContainerCompilerPassClasses);
    }
    /**
     * Set the initial state, eg: when passing state via the request is disabled
     *
     * @param array<string,mixed> $initialAppState
     */
    public function setInitialAppState($initialAppState) : void
    {
        $this->initialAppState = $initialAppState;
    }
    /**
     * Merge some initial state
     *
     * @param array<string,mixed> $initialAppState
     */
    public function mergeInitialAppState($initialAppState) : void
    {
        $this->initialAppState = \array_merge($this->initialAppState, $initialAppState);
    }
    /**
     * Add schema Module classes to skip initializing
     *
     * @param array<class-string<ModuleInterface>> $skipSchemaModuleClasses List of `Module` class which must not initialize their Schema services
     */
    public function addSchemaModuleClassesToSkip($skipSchemaModuleClasses) : void
    {
        $this->skipSchemaModuleClasses = \array_merge($this->skipSchemaModuleClasses, $skipSchemaModuleClasses);
    }
    /**
     * Get the array of modules ordered by how they must be initialized,
     * following the Composer dependencies tree
     *
     * @param string[] $moduleClasses List of `Module` class to initialize
     * @phpstan-param array<class-string<ModuleInterface>> $moduleClasses
     */
    private function addComponentsOrderedForInitialization(array $moduleClasses, bool $isDev) : void
    {
        /**
         * If any module class has already been initialized,
         * then do nothing
         */
        $moduleClasses = \array_diff($moduleClasses, $this->initializedModuleClasses);
        $moduleManager = \PoP\Root\App::getModuleManager();
        foreach ($moduleClasses as $moduleClass) {
            $this->initializedModuleClasses[] = $moduleClass;
            // Initialize and register the Module
            $module = $moduleManager->register($moduleClass);
            // Initialize all depended-upon PoP modules
            if ($dependedModuleClasses = \array_diff($module->getDependedModuleClasses(), $this->initializedModuleClasses)) {
                $this->addComponentsOrderedForInitialization($dependedModuleClasses, $isDev);
            }
            if ($isDev) {
                if ($devDependedModuleClasses = \array_diff($module->getDevDependedModuleClasses(), $this->initializedModuleClasses)) {
                    $this->addComponentsOrderedForInitialization($devDependedModuleClasses, $isDev);
                }
                if (\PoP\Root\Environment::isApplicationEnvironmentDevPHPUnit()) {
                    if ($devPHPUnitDependedModuleClasses = \array_diff($module->getDevPHPUnitDependedModuleClasses(), $this->initializedModuleClasses)) {
                        $this->addComponentsOrderedForInitialization($devPHPUnitDependedModuleClasses, $isDev);
                    }
                }
            }
            // Initialize all depended-upon PoP conditional modules, if they are installed
            $dependedConditionalModuleClasses = \array_filter(
                $module->getDependedConditionalModuleClasses(),
                // Rector does not downgrade `class_exists(...)` properly, so keep as string
                'class_exists'
            );
            if ($dependedConditionalModuleClasses = \array_diff($dependedConditionalModuleClasses, $this->initializedModuleClasses)) {
                $this->addComponentsOrderedForInitialization($dependedConditionalModuleClasses, $isDev);
            }
            // We reached the bottom of the rung, add the module to the list
            $this->orderedModuleClasses[] = $moduleClass;
            /**
             * If this compononent satisfies the contracts for other
             * modules, set them as "satisfied".
             */
            foreach ($module->getSatisfiedModuleClasses() as $satisfiedComponentClass) {
                $satisfiedComponent = \PoP\Root\App::getModule($satisfiedComponentClass);
                $satisfiedComponent->setSatisfyingModule($module);
            }
        }
    }
    /**
     * Get the array of modules ordered by how they must be initialized,
     * following the Composer dependencies tree
     *
     * @param boolean $isDev Indicate if testing with PHPUnit, as to load modules only for DEV
     */
    public function initializeModules($isDev = \false) : void
    {
        // Initialize Dotenv (before the ContainerBuilder, since this one uses environment constants)
        DotenvBuilderFactory::init();
        /**
         * Calculate the modules in their initialization order
         */
        $this->addComponentsOrderedForInitialization($this->moduleClassesToInitialize, $isDev);
        /**
         * After initialized, and before booting,
         * allow the modules to inject their own configuration
         */
        $this->configureComponents();
    }
    /**
     * Boot the application. It does these steps:
     *
     * 1. Initialize Symfony's Dotenv module (to get config from ENV)
     * 2. Calculate in what order will the Components (including from main Plugin and Extensions) will be initialized (based on their Composer dependency order)
     * 3. Allow Components to customize the module configuration for themselves, and the modules they can see
     * 4. Register all Components with the ModuleManager
     * 5. Initialize the System Container, have all Components inject services, and compile it, making "system" services (eg: hooks, translation) available for initializing Application Container services
     *
     * @param boolean|null $cacheContainerConfiguration Indicate if to cache the container. If null, it gets the value from ENV
     * @param string|null $containerNamespace Provide the namespace, to regenerate the cache whenever the application is upgraded. If null, it gets the value from ENV
     * @param string|null $containerDirectory Provide the directory, to regenerate the cache whenever the application is upgraded. If null, it uses the default /tmp folder by the OS
     */
    public function bootSystem($cacheContainerConfiguration = null, $containerNamespace = null, $containerDirectory = null) : void
    {
        /**
         * System container: initialize it and compile it already,
         * since it will be used to initialize the Application container
         */
        \PoP\Root\App::getSystemContainerBuilderFactory()->init($cacheContainerConfiguration, $containerNamespace, $containerDirectory);
        /**
         * Have all Components register their Container services,
         * and already compile the container.
         * This way, these services become available for initializing
         * Application Container services.
         */
        foreach ($this->orderedModuleClasses as $moduleClass) {
            $module = \PoP\Root\App::getModule($moduleClass);
            if (!$module->isEnabled()) {
                continue;
            }
            $module->initializeSystem();
        }
        $systemCompilerPasses = \array_map(function ($class) {
            return new $class();
        }, $this->getSystemContainerCompilerPasses());
        \PoP\Root\App::getSystemContainerBuilderFactory()->maybeCompileAndCacheContainer($systemCompilerPasses);
        // Finally boot the modules
        $this->bootSystemComponents();
    }
    /**
     * Trigger after initializing all modules,
     * and before booting the system
     */
    protected function configureComponents() : void
    {
        \PoP\Root\App::getModuleManager()->configureComponents();
    }
    /**
     * Trigger "moduleLoaded", "boot" and "afterBoot" events on all the Components,
     * for them to execute any custom extra logic
     */
    protected function bootSystemComponents() : void
    {
        \PoP\Root\App::getModuleManager()->bootSystem();
    }
    /**
     * @return array<class-string<CompilerPassInterface>>
     */
    protected final function getSystemContainerCompilerPasses() : array
    {
        // Collect the compiler pass classes from all modules
        $compilerPassClasses = $this->systemContainerCompilerPassClasses;
        foreach ($this->orderedModuleClasses as $moduleClass) {
            $module = \PoP\Root\App::getModule($moduleClass);
            if (!$module->isEnabled()) {
                continue;
            }
            $item1Unpacked = $module->getSystemContainerCompilerPassClasses();
            $compilerPassClasses = \array_merge($compilerPassClasses, $item1Unpacked);
        }
        /** @var array<class-string<CompilerPassInterface>> */
        return \array_values(\array_unique($compilerPassClasses));
    }
    /**
     * Boot the application. It does these steps:
     *
     * 1. Initialize the Application Container, have all Components inject services, and compile it
     * 2. Trigger "moduleLoaded", "boot" and "afterBoot" events on all the Components, for them to execute any custom extra logic
     *
     * @param boolean|null $cacheContainerConfiguration Indicate if to cache the container. If null, it gets the value from ENV
     * @param string|null $containerNamespace Provide the namespace, to regenerate the cache whenever the application is upgraded. If null, it gets the value from ENV
     * @param string|null $containerDirectory Provide the directory, to regenerate the cache whenever the application is upgraded. If null, it uses the default /tmp folder by the OS
     */
    public function bootApplication($cacheContainerConfiguration = null, $containerNamespace = null, $containerDirectory = null) : void
    {
        /**
         * Allow each module to customize the configuration for itself,
         * and for its depended-upon modules.
         * Hence this is executed from bottom to top
         */
        foreach (\array_reverse($this->orderedModuleClasses) as $moduleClass) {
            $module = \PoP\Root\App::getModule($moduleClass);
            if (!$module->isEnabled()) {
                continue;
            }
            $module->customizeModuleClassConfiguration($this->moduleClassConfiguration);
        }
        /**
         * Initialize the Application container only
         */
        \PoP\Root\App::getContainerBuilderFactory()->init($cacheContainerConfiguration, $containerNamespace, $containerDirectory);
        /**
         * Initialize the container services by the Components
         */
        foreach ($this->orderedModuleClasses as $moduleClass) {
            // Initialize the module, passing its configuration, and checking if its schema must be skipped
            $module = \PoP\Root\App::getModule($moduleClass);
            if (!$module->isEnabled()) {
                continue;
            }
            $moduleConfiguration = $this->moduleClassConfiguration[$moduleClass] ?? [];
            $skipSchemaForModule = $this->skipSchemaForModule($module);
            /** @var array<class-string<ModuleInterface>> */
            $skipSchemaModuleClasses = $this->skipSchemaModuleClasses;
            $module->initialize($moduleConfiguration, $skipSchemaForModule, $skipSchemaModuleClasses);
        }
        // Register CompilerPasses, Compile and Cache
        // Symfony's DependencyInjection Application Container
        $systemCompilerPassRegistry = SystemCompilerPassRegistryFacade::getInstance();
        $systemCompilerPasses = $systemCompilerPassRegistry->getCompilerPasses();
        $item1Unpacked = \array_map(function (string $compilerPassClass) {
            return new $compilerPassClass();
        }, $this->applicationContainerCompilerPassClasses);
        $applicationCompilerPasses = \array_merge($systemCompilerPasses, $item1Unpacked);
        \PoP\Root\App::getContainerBuilderFactory()->maybeCompileAndCacheContainer($applicationCompilerPasses);
        // Initialize the modules
        \PoP\Root\App::getModuleManager()->moduleLoaded();
    }
    /**
     * @param \PoP\Root\Module\ModuleInterface $module
     */
    public function skipSchemaForModule($module) : bool
    {
        $moduleClass = \get_class($module);
        if (!isset($this->skipSchemaForModuleCache[$moduleClass])) {
            $this->skipSchemaForModuleCache[$moduleClass] = \in_array($moduleClass, $this->skipSchemaModuleClasses) || $module->skipSchema();
        }
        return $this->skipSchemaForModuleCache[$moduleClass];
    }
    /**
     * Trigger "moduleLoaded", "preBoot", "boot" and "afterBoot"
     * events on all the Components, for them to execute
     * any custom extra logic.
     */
    public function bootApplicationModules() : void
    {
        $appStateManager = \PoP\Root\App::getAppStateManager();
        $appStateManager->initializeAppState($this->initialAppState);
        $moduleManager = \PoP\Root\App::getModuleManager();
        // Allow to execute the SchemaConfigurator in this event
        $moduleManager->preBoot();
        $moduleManager->boot();
        /**
         * After the services have been initialized, we can then parse the GraphQL query.
         * This way, the SchemaConfigutationExecuter can inject its hooks
         * (eg: Composable Directives enabled?) before the env var is read for
         * first time and, then, initialized.
         */
        $appStateManager->executeAppState();
        $moduleManager->afterBoot();
        // Allow to inject functionality
        \PoP\Root\App::doAction(HookNames::AFTER_BOOT_APPLICATION);
    }
}
