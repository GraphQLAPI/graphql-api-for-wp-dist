<?php

declare (strict_types=1);
namespace PoP\Root;

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
 * Single class hosting all the top-level instances to run the application
 */
interface AppInterface
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
    public static function initialize($appLoader = null, $hookManager = null, $request = null, $containerBuilderFactory = null, $systemContainerBuilderFactory = null, $moduleManager = null, $appStateManager = null) : void;
    public static function regenerateResponse() : void;
    public static function getAppLoader() : \PoP\Root\AppLoaderInterface;
    public static function getHookManager() : HookManagerInterface;
    public static function getRequest() : Request;
    public static function getResponse() : Response;
    public static function getContainerBuilderFactory() : ContainerBuilderFactory;
    public static function getSystemContainerBuilderFactory() : SystemContainerBuilderFactory;
    public static function getModuleManager() : ModuleManagerInterface;
    public static function getAppStateManager() : AppStateManagerInterface;
    public static function isHTTPRequest() : bool;
    /**
     * Store Module classes to be initialized, and
     * inject them into the AppLoader when this is initialized.
     *
     * @param array<class-string<ModuleInterface>> $moduleClasses List of `Module` class to initialize
     */
    public static function stockAndInitializeModuleClasses($moduleClasses) : void;
    /**
     * Shortcut function.
     */
    public static function getContainer() : ContainerInterface;
    /**
     * Shortcut function.
     */
    public static function getSystemContainer() : ContainerInterface;
    /**
     * Shortcut function.
     *
     * @phpstan-param class-string<ModuleInterface> $moduleClass
     * @throws ComponentNotExistsException
     * @param string $moduleClass
     */
    public static function getModule($moduleClass) : ModuleInterface;
    /**
     * Shortcut function.
     * @param string|string[] $keyOrPath The property key, or a property path for array values
     * @return mixed
     */
    public static function getState($keyOrPath);
    /**
     * Shortcut function.
     * @param string|string[] $keyOrPath The property key, or a property path for array values
     * @return mixed
     */
    public static function hasState($keyOrPath);
    /**
     * Shortcut function.
     * @param string $tag
     * @param callable $function_to_add
     * @param int $priority
     * @param int $accepted_args
     */
    public static function addFilter($tag, $function_to_add, $priority = 10, $accepted_args = 1) : void;
    /**
     * Shortcut function.
     * @param string $tag
     * @param callable $function_to_remove
     * @param int $priority
     */
    public static function removeFilter($tag, $function_to_remove, $priority = 10) : bool;
    /**
     * Shortcut function.
     * @param mixed $value
     * @param mixed ...$args
     * @return mixed
     * @param string $tag
     */
    public static function applyFilters($tag, $value, ...$args);
    /**
     * Shortcut function.
     * @param string $tag
     * @param callable $function_to_add
     * @param int $priority
     * @param int $accepted_args
     */
    public static function addAction($tag, $function_to_add, $priority = 10, $accepted_args = 1) : void;
    /**
     * Shortcut function.
     * @param string $tag
     * @param callable $function_to_remove
     * @param int $priority
     */
    public static function removeAction($tag, $function_to_remove, $priority = 10) : bool;
    /**
     * Shortcut function.
     * @param mixed ...$args
     * @param string $tag
     */
    public static function doAction($tag, ...$args) : void;
    /**
     * Shortcut function.
     *
     * Equivalent of $_POST[$key] ?? $default
     * @param mixed $default
     * @return mixed
     * @param string $key
     */
    public static function request($key, $default = null);
    /**
     * Shortcut function.
     *
     * Equivalent of $_GET[$key] ?? $default
     * @param mixed $default
     * @return mixed
     * @param string $key
     */
    public static function query($key, $default = null);
    /**
     * Shortcut function.
     *
     * Equivalent of $_COOKIES[$key] ?? $default
     * @param mixed $default
     * @return mixed
     * @param string $key
     */
    public static function cookies($key, $default = null);
    /**
     * Shortcut function.
     *
     * Equivalent of $_FILES[$key] ?? $default
     * @param mixed $default
     * @return mixed
     * @param string $key
     */
    public static function files($key, $default = null);
    /**
     * Shortcut function.
     *
     * Equivalent of $_SERVER[$key] ?? $default
     * @param mixed $default
     * @return mixed
     * @param string $key
     */
    public static function server($key, $default = null);
    /**
     * Shortcut function.
     *
     * Mostly equivalent to a subset of $_SERVER
     * @param mixed $default
     * @return mixed
     * @param string $key
     */
    public static function headers($key, $default = null);
}
