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
use PoP\Root\StateManagers\AppStateManager;
use PoP\Root\StateManagers\AppStateManagerInterface;
use PoP\Root\StateManagers\ModuleManager;
use PoP\Root\StateManagers\ModuleManagerInterface;
use PoP\Root\StateManagers\HookManager;
use PoP\Root\StateManagers\HookManagerInterface;
/**
 * Keep all state in the application stored and accessible
 * through this class, so that regenerating this class
 * provides a new state.
 *
 * Needed for PHPUnit.
 */
class App implements \PoP\Root\AppInterface
{
    /**
     * @var \PoP\Root\AppLoaderInterface
     */
    protected static $appLoader;
    /**
     * @var \PoP\Root\StateManagers\HookManagerInterface
     */
    protected static $hookManager;
    /**
     * @var \PoP\Root\HttpFoundation\Request
     */
    protected static $request;
    /**
     * @var \PoP\Root\HttpFoundation\Response
     */
    protected static $response;
    /**
     * @var \PoP\Root\Container\ContainerBuilderFactory
     */
    protected static $containerBuilderFactory;
    /**
     * @var \PoP\Root\Container\SystemContainerBuilderFactory
     */
    protected static $systemContainerBuilderFactory;
    /**
     * @var \PoP\Root\StateManagers\ModuleManagerInterface
     */
    protected static $moduleManager;
    /**
     * @var \PoP\Root\StateManagers\AppStateManagerInterface
     */
    protected static $appStateManager;
    /** @var array<class-string<ModuleInterface>> */
    protected static $moduleClassesToInitialize = [];
    /**
     * @var bool
     */
    protected static $isHTTPRequest;
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
        self::$appLoader = $appLoader ?? static::createAppLoader();
        self::$hookManager = $hookManager ?? static::createHookManager();
        self::$request = $request ?? static::createRequest();
        self::$containerBuilderFactory = $containerBuilderFactory ?? static::createContainerBuilderFactory();
        self::$systemContainerBuilderFactory = $systemContainerBuilderFactory ?? static::createSystemContainerBuilderFactory();
        self::$moduleManager = $moduleManager ?? static::createComponentManager();
        self::$appStateManager = $appStateManager ?? static::createAppStateManager();
        static::regenerateResponse();
        // Inject the Components slated for initialization
        self::$appLoader->addModuleClassesToInitialize(self::$moduleClassesToInitialize);
        self::$moduleClassesToInitialize = [];
        /**
         * Indicate if this App is invoked via an HTTP request.
         * If not, it may be directly invoked as a PHP component,
         * or from a PHPUnit test.
         */
        self::$isHTTPRequest = self::server('REQUEST_METHOD') !== null;
    }
    protected static function createAppLoader() : \PoP\Root\AppLoaderInterface
    {
        return new \PoP\Root\AppLoader();
    }
    protected static function createHookManager() : HookManagerInterface
    {
        return new HookManager();
    }
    protected static function createRequest() : Request
    {
        return Request::createFromGlobals();
    }
    /**
     * @see https://symfony.com/doc/current/components/http_foundation.html#response
     */
    protected static function createResponse() : Response
    {
        return new Response();
    }
    protected static function createContainerBuilderFactory() : ContainerBuilderFactory
    {
        return new ContainerBuilderFactory();
    }
    protected static function createSystemContainerBuilderFactory() : SystemContainerBuilderFactory
    {
        return new SystemContainerBuilderFactory();
    }
    protected static function createComponentManager() : ModuleManagerInterface
    {
        return new ModuleManager();
    }
    protected static function createAppStateManager() : AppStateManagerInterface
    {
        return new AppStateManager();
    }
    public static function regenerateResponse() : void
    {
        self::$response = static::createResponse();
    }
    public static function getAppLoader() : \PoP\Root\AppLoaderInterface
    {
        return self::$appLoader;
    }
    public static function getHookManager() : HookManagerInterface
    {
        return self::$hookManager;
    }
    public static function getRequest() : Request
    {
        return self::$request;
    }
    public static function getResponse() : Response
    {
        return self::$response;
    }
    public static function getContainerBuilderFactory() : ContainerBuilderFactory
    {
        return self::$containerBuilderFactory;
    }
    public static function getSystemContainerBuilderFactory() : SystemContainerBuilderFactory
    {
        return self::$systemContainerBuilderFactory;
    }
    public static function getModuleManager() : ModuleManagerInterface
    {
        return self::$moduleManager;
    }
    public static function getAppStateManager() : AppStateManagerInterface
    {
        return self::$appStateManager;
    }
    public static function isHTTPRequest() : bool
    {
        return self::$isHTTPRequest;
    }
    /**
     * Store Module classes to be initialized, and
     * inject them into the AppLoader when this is initialized.
     *
     * @param array<class-string<ModuleInterface>> $moduleClasses List of `Module` class to initialize
     */
    public static function stockAndInitializeModuleClasses($moduleClasses) : void
    {
        self::$moduleClassesToInitialize = \array_merge(self::$moduleClassesToInitialize, $moduleClasses);
    }
    /**
     * Shortcut function.
     */
    public static final function getContainer() : ContainerInterface
    {
        return self::getContainerBuilderFactory()->getInstance();
    }
    /**
     * Shortcut function.
     */
    public static final function getSystemContainer() : ContainerInterface
    {
        return self::getSystemContainerBuilderFactory()->getInstance();
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
        return self::getModuleManager()->getModule($moduleClass);
    }
    /**
     * Shortcut function.
     * @param string|string[] $keyOrPath The property key, or a property path for array values
     * @return mixed
     */
    public static final function getState($keyOrPath)
    {
        $appStateManager = self::getAppStateManager();
        if (\is_array($keyOrPath)) {
            /** @var string[] */
            $path = $keyOrPath;
            return $appStateManager->getUnder($path);
        }
        /** @var string */
        $key = $keyOrPath;
        return $appStateManager->get($key);
    }
    /**
     * Shortcut function.
     * @param string|string[] $keyOrPath The property key, or a property path for array values
     * @return mixed
     */
    public static final function hasState($keyOrPath)
    {
        $appStateManager = self::getAppStateManager();
        if (\is_array($keyOrPath)) {
            /** @var string[] */
            $path = $keyOrPath;
            return $appStateManager->hasUnder($path);
        }
        /** @var string */
        $key = $keyOrPath;
        return $appStateManager->has($key);
    }
    /**
     * Shortcut function.
     * @param string $tag
     * @param callable $function_to_add
     * @param int $priority
     * @param int $accepted_args
     */
    public static final function addFilter($tag, $function_to_add, $priority = 10, $accepted_args = 1) : void
    {
        self::getHookManager()->addFilter($tag, $function_to_add, $priority, $accepted_args);
    }
    /**
     * Shortcut function.
     * @param string $tag
     * @param callable $function_to_remove
     * @param int $priority
     */
    public static final function removeFilter($tag, $function_to_remove, $priority = 10) : bool
    {
        return self::getHookManager()->removeFilter($tag, $function_to_remove, $priority);
    }
    /**
     * Shortcut function.
     * @param mixed $value
     * @param mixed ...$args
     * @return mixed
     * @param string $tag
     */
    public static final function applyFilters($tag, $value, ...$args)
    {
        return self::getHookManager()->applyFilters($tag, $value, ...$args);
    }
    /**
     * Shortcut function.
     * @param string $tag
     * @param callable $function_to_add
     * @param int $priority
     * @param int $accepted_args
     */
    public static final function addAction($tag, $function_to_add, $priority = 10, $accepted_args = 1) : void
    {
        self::getHookManager()->addAction($tag, $function_to_add, $priority, $accepted_args);
    }
    /**
     * Shortcut function.
     * @param string $tag
     * @param callable $function_to_remove
     * @param int $priority
     */
    public static final function removeAction($tag, $function_to_remove, $priority = 10) : bool
    {
        return self::getHookManager()->removeAction($tag, $function_to_remove, $priority);
    }
    /**
     * Shortcut function.
     * @param mixed ...$args
     * @param string $tag
     */
    public static final function doAction($tag, ...$args) : void
    {
        self::getHookManager()->doAction($tag, ...$args);
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
        return self::getRequest()->request->get($key, $default);
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
        return self::getRequest()->query->get($key, $default);
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
        return self::getRequest()->cookies->get($key, $default);
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
        return self::getRequest()->files->get($key, $default);
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
        return self::getRequest()->server->get($key, $default);
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
        return self::getRequest()->headers->get($key, $default);
    }
}
