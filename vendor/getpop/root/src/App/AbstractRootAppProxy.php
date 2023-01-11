<?php

declare (strict_types=1);
namespace PoP\Root\App;

use PoP\Root\App as RootApp;
use PoP\Root\AppInterface as RootAppInterface;
use PoP\Root\AppLoaderInterface;
use PoP\Root\Module\ModuleInterface;
use PoP\Root\Container\ContainerBuilderFactory;
use PoP\Root\Container\ContainerInterface;
use PoP\Root\Container\SystemContainerBuilderFactory;
use PoP\Root\Exception\ComponentNotExistsException;
use PoP\Root\HttpFoundation\Request;
use PoP\Root\HttpFoundation\Response;
use PoP\Root\StateManagers\AppStateManagerInterface;
use PoP\Root\StateManagers\ModuleManagerInterface;
use PoP\Root\StateManagers\HookManagerInterface;
/**
 * Using proxy instead of inheritance, so that the upstream App
 * class is still the single source of truth for its own state
 */
abstract class AbstractRootAppProxy implements RootAppInterface
{
    /**
     * This function must be invoked at the very beginning,
     * to initialize the instance to run the application.
     *
     * Either inject the desired instance, or have the Root
     * provide the default one.
     * @param \PoP\Root\AppLoaderInterface|null $appLoader
     * @param \PoP\Root\StateManagers\HookManagerInterface|null $hookManager
     * @param \PoP\Root\HttpFoundation\Request|null $request
     * @param \PoP\Root\Container\ContainerBuilderFactory|null $containerBuilderFactory
     * @param \PoP\Root\Container\SystemContainerBuilderFactory|null $systemContainerBuilderFactory
     * @param \PoP\Root\StateManagers\ModuleManagerInterface|null $moduleManager
     * @param \PoP\Root\StateManagers\AppStateManagerInterface|null $appStateManager
     */
    public static function initialize($appLoader = null, $hookManager = null, $request = null, $containerBuilderFactory = null, $systemContainerBuilderFactory = null, $moduleManager = null, $appStateManager = null) : void
    {
        RootApp::initialize($appLoader, $hookManager, $request, $containerBuilderFactory, $systemContainerBuilderFactory, $moduleManager, $appStateManager);
    }
    public static function regenerateResponse() : void
    {
        RootApp::regenerateResponse();
    }
    public static function getAppLoader() : AppLoaderInterface
    {
        return RootApp::getAppLoader();
    }
    public static function getHookManager() : HookManagerInterface
    {
        return RootApp::getHookManager();
    }
    public static function getRequest() : Request
    {
        return RootApp::getRequest();
    }
    public static function getResponse() : Response
    {
        return RootApp::getResponse();
    }
    public static function getContainerBuilderFactory() : ContainerBuilderFactory
    {
        return RootApp::getContainerBuilderFactory();
    }
    public static function getSystemContainerBuilderFactory() : SystemContainerBuilderFactory
    {
        return RootApp::getSystemContainerBuilderFactory();
    }
    public static function getModuleManager() : ModuleManagerInterface
    {
        return RootApp::getModuleManager();
    }
    public static function getAppStateManager() : AppStateManagerInterface
    {
        return RootApp::getAppStateManager();
    }
    public static function isHTTPRequest() : bool
    {
        return RootApp::isHTTPRequest();
    }
    /**
     * Store Module classes to be initialized, and
     * inject them into the AppLoader when this is initialized.
     *
     * @param array<class-string<ModuleInterface>> $moduleClasses List of `Module` class to initialize
     */
    public static function stockAndInitializeModuleClasses($moduleClasses) : void
    {
        RootApp::stockAndInitializeModuleClasses($moduleClasses);
    }
    /**
     * Shortcut function.
     */
    public static final function getContainer() : ContainerInterface
    {
        return RootApp::getContainer();
    }
    /**
     * Shortcut function.
     */
    public static final function getSystemContainer() : ContainerInterface
    {
        return RootApp::getSystemContainer();
    }
    /**
     * Shortcut function.
     *
     * @phpstan-param class-string<ModuleInterface> $moduleClass
     * @throws ComponentNotExistsException
     * @param string $moduleClass
     */
    public static final function getModule($moduleClass) : ModuleInterface
    {
        return RootApp::getModule($moduleClass);
    }
    /**
     * Shortcut function.
     * @param string|string[] $keyOrPath The property key, or a property path for array values
     * @return mixed
     */
    public static final function getState($keyOrPath)
    {
        return RootApp::getState($keyOrPath);
    }
    /**
     * Shortcut function.
     * @param string|string[] $keyOrPath The property key, or a property path for array values
     * @return mixed
     */
    public static final function hasState($keyOrPath)
    {
        return RootApp::hasState($keyOrPath);
    }
    /**
     * Shortcut function.
     * @param string $tag
     * @param callable $function_to_add
     * @param int $priority
     * @param int $accepted_args
     */
    public static function addFilter($tag, $function_to_add, $priority = 10, $accepted_args = 1) : void
    {
        RootApp::addFilter($tag, $function_to_add, $priority, $accepted_args);
    }
    /**
     * Shortcut function.
     * @param string $tag
     * @param callable $function_to_remove
     * @param int $priority
     */
    public static function removeFilter($tag, $function_to_remove, $priority = 10) : bool
    {
        return RootApp::removeFilter($tag, $function_to_remove, $priority);
    }
    /**
     * Shortcut function.
     * @param mixed $value
     * @param mixed ...$args
     * @return mixed
     * @param string $tag
     */
    public static function applyFilters($tag, $value, ...$args)
    {
        return RootApp::applyFilters($tag, $value, ...$args);
    }
    /**
     * Shortcut function.
     * @param string $tag
     * @param callable $function_to_add
     * @param int $priority
     * @param int $accepted_args
     */
    public static function addAction($tag, $function_to_add, $priority = 10, $accepted_args = 1) : void
    {
        RootApp::addAction($tag, $function_to_add, $priority, $accepted_args);
    }
    /**
     * Shortcut function.
     * @param string $tag
     * @param callable $function_to_remove
     * @param int $priority
     */
    public static function removeAction($tag, $function_to_remove, $priority = 10) : bool
    {
        return RootApp::removeAction($tag, $function_to_remove, $priority);
    }
    /**
     * Shortcut function.
     * @param mixed ...$args
     * @param string $tag
     */
    public static function doAction($tag, ...$args) : void
    {
        RootApp::doAction($tag, ...$args);
    }
    /**
     * Shortcut function.
     *
     * Equivalent of $_POST[$key] ?? $default
     * @param mixed $default
     * @return mixed
     * @param string $key
     */
    public static final function request($key, $default = null)
    {
        return RootApp::request($key, $default);
    }
    /**
     * Shortcut function.
     *
     * Equivalent of $_GET[$key] ?? $default
     * @param mixed $default
     * @return mixed
     * @param string $key
     */
    public static final function query($key, $default = null)
    {
        return RootApp::query($key, $default);
    }
    /**
     * Shortcut function.
     *
     * Equivalent of $_COOKIES[$key] ?? $default
     * @param mixed $default
     * @return mixed
     * @param string $key
     */
    public static final function cookies($key, $default = null)
    {
        return RootApp::cookies($key, $default);
    }
    /**
     * Shortcut function.
     *
     * Equivalent of $_FILES[$key] ?? $default
     * @param mixed $default
     * @return mixed
     * @param string $key
     */
    public static final function files($key, $default = null)
    {
        return RootApp::files($key, $default);
    }
    /**
     * Shortcut function.
     *
     * Equivalent of $_SERVER[$key] ?? $default
     * @param mixed $default
     * @return mixed
     * @param string $key
     */
    public static final function server($key, $default = null)
    {
        return RootApp::server($key, $default);
    }
    /**
     * Shortcut function.
     *
     * Mostly equivalent to a subset of $_SERVER
     * @param mixed $default
     * @return mixed
     * @param string $key
     */
    public static final function headers($key, $default = null)
    {
        return RootApp::headers($key, $default);
    }
}
