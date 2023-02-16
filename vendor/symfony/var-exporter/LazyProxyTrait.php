<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace PrefixedByPoP\Symfony\Component\VarExporter;

use PrefixedByPoP\Symfony\Component\VarExporter\Hydrator as PublicHydrator;
use PrefixedByPoP\Symfony\Component\VarExporter\Internal\Hydrator;
use PrefixedByPoP\Symfony\Component\VarExporter\Internal\LazyObjectRegistry as Registry;
use PrefixedByPoP\Symfony\Component\VarExporter\Internal\LazyObjectState;
trait LazyProxyTrait
{
    /**
     * @var \Symfony\Component\VarExporter\Internal\LazyObjectState
     */
    private $lazyObjectState;
    /**
     * @var object
     */
    private $lazyObjectReal;
    /**
     * Creates a lazy-loading virtual proxy.
     *
     * @param \Closure():object $initializer Returns the proxied object
     * @param object $instance
     * @return $this
     */
    public static function createLazyProxy($initializer, $instance = null)
    {
        if (self::class !== ($class = $instance ? \get_class($instance) : static::class)) {
            $skippedProperties = ["\x00" . self::class . "\x00lazyObjectState" => \true];
        } elseif (\defined($class . '::LAZY_OBJECT_PROPERTY_SCOPES')) {
            Hydrator::$propertyScopes[$class] = Hydrator::$propertyScopes[$class] ?? $class::LAZY_OBJECT_PROPERTY_SCOPES;
        }
        $instance = $instance ?? (Registry::$classReflectors[$class] = Registry::$classReflectors[$class] ?? new \ReflectionClass($class))->newInstanceWithoutConstructor();
        $instance->lazyObjectState = new LazyObjectState($initializer);
        foreach (Registry::$classResetters[$class] = Registry::$classResetters[$class] ?? Registry::getClassResetters($class) as $reset) {
            $reset($instance, $skippedProperties = $skippedProperties ?? []);
        }
        return $instance;
    }
    /**
     * Returns whether the object is initialized.
     *
     * @param $partial Whether partially initialized objects should be considered as initialized
     * @param bool $partial
     */
    public function isLazyObjectInitialized($partial = \false) : bool
    {
        if (!isset($this->lazyObjectState) || Registry::$noInitializerState === $this->lazyObjectState) {
            return \true;
        }
        return \array_key_exists("\x00" . self::class . "\x00lazyObjectReal", (array) $this);
    }
    /**
     * Forces initialization of a lazy object and returns it.
     */
    public function initializeLazyObject() : parent
    {
        if (isset($this->lazyObjectReal)) {
            return $this->lazyObjectReal;
        }
        return $this;
    }
    /**
     * @return bool Returns false when the object cannot be reset, ie when it's not a lazy object
     */
    public function resetLazyObject() : bool
    {
        if (!isset($this->lazyObjectState) || Registry::$noInitializerState === $this->lazyObjectState) {
            return \false;
        }
        if (\array_key_exists("\x00" . self::class . "\x00lazyObjectReal", (array) $this)) {
            unset($this->lazyObjectReal);
        }
        return \true;
    }
    /**
     * @return mixed
     */
    public function &__get($name)
    {
        $propertyScopes = Hydrator::$propertyScopes[\get_class($this)] = Hydrator::$propertyScopes[\get_class($this)] ?? Hydrator::getPropertyScopes(\get_class($this));
        $scope = null;
        $instance = $this;
        if ([$class, , $readonlyScope] = $propertyScopes[$name] ?? null) {
            $scope = Registry::getScope($propertyScopes, $class, $name);
            if (null === $scope || isset($propertyScopes["\x00{$scope}\x00{$name}"])) {
                if ($state = $this->lazyObjectState ?? null) {
                    if ('lazyObjectReal' === $name && self::class === $scope) {
                        $this->lazyObjectReal = ($state->initializer)();
                        return $this->lazyObjectReal;
                    }
                    if (isset($this->lazyObjectReal)) {
                        $instance = $this->lazyObjectReal;
                    }
                }
                $parent = 2;
                goto get_in_scope;
            }
        }
        $parent = (Registry::$parentMethods[self::class] = Registry::$parentMethods[self::class] ?? Registry::getParentMethods(self::class))['get'];
        if (isset($this->lazyObjectReal)) {
            $instance = $this->lazyObjectReal;
        } else {
            if (2 === $parent) {
                return parent::__get($name);
            }
            $value = parent::__get($name);
            return $value;
        }
        if (!$parent && null === $class && !\array_key_exists($name, (array) $instance)) {
            $frame = \debug_backtrace(\DEBUG_BACKTRACE_IGNORE_ARGS, 1)[0];
            \trigger_error(\sprintf('Undefined property: %s::$%s in %s on line %s', \get_class($instance), $name, $frame['file'], $frame['line']), \E_USER_NOTICE);
        }
        get_in_scope:
        try {
            if (null === $scope) {
                if (null === $readonlyScope && 1 !== $parent) {
                    return $instance->{$name};
                }
                $value = $instance->{$name};
                return $value;
            }
            $accessor = Registry::$classAccessors[$scope] = Registry::$classAccessors[$scope] ?? Registry::getClassAccessors($scope);
            return $accessor['get']($instance, $name, null !== $readonlyScope || 1 === $parent);
        } catch (\Error $e) {
            if (\Error::class !== \get_class($e) || \strncmp($e->getMessage(), 'Cannot access uninitialized non-nullable property', \strlen('Cannot access uninitialized non-nullable property')) !== 0) {
                throw $e;
            }
            try {
                if (null === $scope) {
                    $instance->{$name} = [];
                    return $instance->{$name};
                }
                $accessor['set']($instance, $name, []);
                return $accessor['get']($instance, $name, null !== $readonlyScope || 1 === $parent);
            } catch (\Error $exception) {
                throw $e;
            }
        }
    }
    public function __set($name, $value) : void
    {
        $propertyScopes = Hydrator::$propertyScopes[\get_class($this)] = Hydrator::$propertyScopes[\get_class($this)] ?? Hydrator::getPropertyScopes(\get_class($this));
        $scope = null;
        $instance = $this;
        if ([$class, , $readonlyScope] = $propertyScopes[$name] ?? null) {
            $scope = Registry::getScope($propertyScopes, $class, $name, $readonlyScope);
            if ($readonlyScope === $scope || isset($propertyScopes["\x00{$scope}\x00{$name}"])) {
                if (isset($this->lazyObjectState)) {
                    if ('lazyObjectReal' === $name && self::class === $scope) {
                        $this->lazyObjectReal = $value;
                        return;
                    }
                    if (isset($this->lazyObjectReal)) {
                        $instance = $this->lazyObjectReal;
                    }
                }
                goto set_in_scope;
            }
        }
        if (isset($this->lazyObjectReal)) {
            $instance = $this->lazyObjectReal;
        } elseif ((Registry::$parentMethods[self::class] = Registry::$parentMethods[self::class] ?? Registry::getParentMethods(self::class))['set']) {
            parent::__set($name, $value);
            return;
        }
        set_in_scope:
        if (null === $scope) {
            $instance->{$name} = $value;
        } else {
            $accessor = Registry::$classAccessors[$scope] = Registry::$classAccessors[$scope] ?? Registry::getClassAccessors($scope);
            $accessor['set']($instance, $name, $value);
        }
    }
    public function __isset($name) : bool
    {
        $propertyScopes = Hydrator::$propertyScopes[\get_class($this)] = Hydrator::$propertyScopes[\get_class($this)] ?? Hydrator::getPropertyScopes(\get_class($this));
        $scope = null;
        $instance = $this;
        if ([$class] = $propertyScopes[$name] ?? null) {
            $scope = Registry::getScope($propertyScopes, $class, $name);
            if (null === $scope || isset($propertyScopes["\x00{$scope}\x00{$name}"])) {
                if (isset($this->lazyObjectState)) {
                    if ('lazyObjectReal' === $name && self::class === $scope) {
                        $state = $this->lazyObjectState ?? null;
                        return null !== ($this->lazyObjectReal = $state ? ($state->initializer)() : null);
                    }
                    if (isset($this->lazyObjectReal)) {
                        $instance = $this->lazyObjectReal;
                    }
                }
                goto isset_in_scope;
            }
        }
        if (isset($this->lazyObjectReal)) {
            $instance = $this->lazyObjectReal;
        } elseif ((Registry::$parentMethods[self::class] = Registry::$parentMethods[self::class] ?? Registry::getParentMethods(self::class))['isset']) {
            return parent::__isset($name);
        }
        isset_in_scope:
        if (null === $scope) {
            return isset($instance->{$name});
        }
        $accessor = Registry::$classAccessors[$scope] = Registry::$classAccessors[$scope] ?? Registry::getClassAccessors($scope);
        return $accessor['isset']($instance, $name);
    }
    public function __unset($name) : void
    {
        $propertyScopes = Hydrator::$propertyScopes[\get_class($this)] = Hydrator::$propertyScopes[\get_class($this)] ?? Hydrator::getPropertyScopes(\get_class($this));
        $scope = null;
        $instance = $this;
        if ([$class, , $readonlyScope] = $propertyScopes[$name] ?? null) {
            $scope = Registry::getScope($propertyScopes, $class, $name, $readonlyScope);
            if ($readonlyScope === $scope || isset($propertyScopes["\x00{$scope}\x00{$name}"])) {
                if (isset($this->lazyObjectState)) {
                    if ('lazyObjectReal' === $name && self::class === $scope) {
                        unset($this->lazyObjectReal);
                        return;
                    }
                    if (isset($this->lazyObjectReal)) {
                        $instance = $this->lazyObjectReal;
                    }
                }
                goto unset_in_scope;
            }
        }
        if (isset($this->lazyObjectReal)) {
            $instance = $this->lazyObjectReal;
        } elseif ((Registry::$parentMethods[self::class] = Registry::$parentMethods[self::class] ?? Registry::getParentMethods(self::class))['unset']) {
            parent::__unset($name);
            return;
        }
        unset_in_scope:
        if (null === $scope) {
            unset($instance->{$name});
        } else {
            $accessor = Registry::$classAccessors[$scope] = Registry::$classAccessors[$scope] ?? Registry::getClassAccessors($scope);
            $accessor['unset']($instance, $name);
        }
    }
    public function __clone()
    {
        if (!isset($this->lazyObjectState)) {
            if ((Registry::$parentMethods[self::class] = Registry::$parentMethods[self::class] ?? Registry::getParentMethods(self::class))['clone']) {
                parent::__clone();
            }
            return;
        }
        if (\array_key_exists("\x00" . self::class . "\x00lazyObjectReal", (array) $this)) {
            $this->lazyObjectReal = clone $this->lazyObjectReal;
        }
        if ($state = $this->lazyObjectState ?? null) {
            $this->lazyObjectState = clone $state;
        }
    }
    public function __serialize() : array
    {
        $class = self::class;
        if (!isset($this->lazyObjectReal) && (Registry::$parentMethods[$class] = Registry::$parentMethods[$class] ?? Registry::getParentMethods($class))['serialize']) {
            $properties = parent::__serialize();
        } else {
            $properties = (array) $this;
        }
        unset($properties["\x00{$class}\x00lazyObjectState"]);
        if (isset($this->lazyObjectReal) || Registry::$parentMethods[$class]['serialize'] || !Registry::$parentMethods[$class]['sleep']) {
            return $properties;
        }
        $scope = \get_parent_class($class);
        $data = [];
        foreach (parent::__sleep() as $name) {
            $value = $properties[$k = $name] ?? $properties[$k = "\x00*\x00{$name}"] ?? $properties[$k = "\x00{$scope}\x00{$name}"] ?? ($k = null);
            if (null === $k) {
                \trigger_error(\sprintf('serialize(): "%s" returned as member variable from __sleep() but does not exist', $name), \E_USER_NOTICE);
            } else {
                $data[$k] = $value;
            }
        }
        return $data;
    }
    public function __unserialize(array $data) : void
    {
        $class = self::class;
        if (isset($data["\x00{$class}\x00lazyObjectReal"])) {
            foreach (Registry::$classResetters[$class] = Registry::$classResetters[$class] ?? Registry::getClassResetters($class) as $reset) {
                $reset($this, $data);
            }
            if (1 < \count($data)) {
                PublicHydrator::hydrate($this, $data);
            } else {
                $this->lazyObjectReal = $data["\x00{$class}\x00lazyObjectReal"];
            }
            $this->lazyObjectState = Registry::$noInitializerState = Registry::$noInitializerState ?? new LazyObjectState(static function () {
                throw new \LogicException('Lazy proxy has no initializer.');
            });
        } elseif ((Registry::$parentMethods[$class] = Registry::$parentMethods[$class] ?? Registry::getParentMethods($class))['unserialize']) {
            parent::__unserialize($data);
        } else {
            PublicHydrator::hydrate($this, $data);
            if (Registry::$parentMethods[$class]['wakeup']) {
                parent::__wakeup();
            }
        }
    }
    public function __destruct()
    {
        if (isset($this->lazyObjectState)) {
            return;
        }
        if ((Registry::$parentMethods[self::class] = Registry::$parentMethods[self::class] ?? Registry::getParentMethods(self::class))['destruct']) {
            parent::__destruct();
        }
    }
}
