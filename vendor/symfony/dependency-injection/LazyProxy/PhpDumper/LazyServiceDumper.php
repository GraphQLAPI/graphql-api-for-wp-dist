<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace PrefixedByPoP\Symfony\Component\DependencyInjection\LazyProxy\PhpDumper;

use PrefixedByPoP\Symfony\Component\DependencyInjection\Definition;
use PrefixedByPoP\Symfony\Component\DependencyInjection\Exception\InvalidArgumentException;
use PrefixedByPoP\Symfony\Component\VarExporter\Exception\LogicException;
use PrefixedByPoP\Symfony\Component\VarExporter\ProxyHelper;
/**
 * @author Nicolas Grekas <p@tchwork.com>
 */
final class LazyServiceDumper implements DumperInterface
{
    /**
     * @var string
     */
    private $salt = '';
    public function __construct(string $salt = '')
    {
        $this->salt = $salt;
    }
    /**
     * @param \Symfony\Component\DependencyInjection\Definition $definition
     * @param bool|null $asGhostObject
     * @param string|null $id
     */
    public function isProxyCandidate($definition, &$asGhostObject = null, $id = null) : bool
    {
        $asGhostObject = \false;
        if ($definition->hasTag('proxy')) {
            if (!$definition->isLazy()) {
                throw new InvalidArgumentException(\sprintf('Invalid definition for service "%s": setting the "proxy" tag on a service requires it to be "lazy".', $id ?? $definition->getClass()));
            }
            return \true;
        }
        if (!$definition->isLazy()) {
            return \false;
        }
        if (!($class = $definition->getClass()) || !(\class_exists($class) || \interface_exists($class, \false))) {
            return \false;
        }
        if ($definition->getFactory()) {
            return \true;
        }
        foreach ($definition->getMethodCalls() as $call) {
            if ($call[2] ?? \false) {
                return \true;
            }
        }
        try {
            $asGhostObject = (bool) ProxyHelper::generateLazyGhost(new \ReflectionClass($class));
        } catch (LogicException $exception) {
        }
        return \true;
    }
    /**
     * @param \Symfony\Component\DependencyInjection\Definition $definition
     * @param string $id
     * @param string $factoryCode
     */
    public function getProxyFactoryCode($definition, $id, $factoryCode) : string
    {
        $instantiation = 'return';
        if ($definition->isShared()) {
            $instantiation .= \sprintf(' $this->%s[%s] =', $definition->isPublic() && !$definition->isPrivate() ? 'services' : 'privates', \var_export($id, \true));
        }
        $asGhostObject = \strpos($factoryCode, '$proxy') !== \false;
        $proxyClass = $this->getProxyClass($definition, $asGhostObject);
        if (!$asGhostObject) {
            return <<<EOF
        if (true === \$lazyLoad) {
            {$instantiation} \$this->createProxy('{$proxyClass}', fn () => \\{$proxyClass}::createLazyProxy(fn () => {$factoryCode}));
        }


EOF;
        }
        if (\preg_match('/^\\$this->\\w++\\(\\$proxy\\)$/', $factoryCode)) {
            $factoryCode = \substr_replace($factoryCode, '(...)', -8);
        } else {
            $factoryCode = \sprintf('fn ($proxy) => %s', $factoryCode);
        }
        return <<<EOF
        if (true === \$lazyLoad) {
            {$instantiation} \$this->createProxy('{$proxyClass}', fn () => \\{$proxyClass}::createLazyGhost({$factoryCode}));
        }


EOF;
    }
    /**
     * @param \Symfony\Component\DependencyInjection\Definition $definition
     * @param string|null $id
     */
    public function getProxyCode($definition, $id = null) : string
    {
        if (!$this->isProxyCandidate($definition, $asGhostObject, $id)) {
            throw new InvalidArgumentException(\sprintf('Cannot instantiate lazy proxy for service "%s".', $id ?? $definition->getClass()));
        }
        $proxyClass = $this->getProxyClass($definition, $asGhostObject, $class);
        if ($asGhostObject) {
            try {
                return 'class ' . $proxyClass . ProxyHelper::generateLazyGhost($class);
            } catch (LogicException $e) {
                throw new InvalidArgumentException(\sprintf('Cannot generate lazy ghost for service "%s".', $id ?? $definition->getClass()), 0, $e);
            }
        }
        $interfaces = [];
        if ($definition->hasTag('proxy')) {
            foreach ($definition->getTag('proxy') as $tag) {
                if (!isset($tag['interface'])) {
                    throw new InvalidArgumentException(\sprintf('Invalid definition for service "%s": the "interface" attribute is missing on a "proxy" tag.', $id ?? $definition->getClass()));
                }
                if (!\interface_exists($tag['interface']) && !\class_exists($tag['interface'], \false)) {
                    throw new InvalidArgumentException(\sprintf('Invalid definition for service "%s": several "proxy" tags found but "%s" is not an interface.', $id ?? $definition->getClass(), $tag['interface']));
                }
                if (!\is_a($class->name, $tag['interface'], \true)) {
                    throw new InvalidArgumentException(\sprintf('Invalid "proxy" tag for service "%s": class "%s" doesn\'t implement "%s".', $id ?? $definition->getClass(), $definition->getClass(), $tag['interface']));
                }
                $interfaces[] = new \ReflectionClass($tag['interface']);
            }
            $class = 1 === \count($interfaces) && !$interfaces[0]->isInterface() ? \array_pop($interfaces) : null;
        } elseif ($class->isInterface()) {
            $interfaces = [$class];
            $class = null;
        }
        try {
            return (\PHP_VERSION_ID >= 80200 && (($class2 = $class) ? $class2->isReadOnly() : null) ? 'readonly ' : '') . 'class ' . $proxyClass . ProxyHelper::generateLazyProxy($class, $interfaces);
        } catch (LogicException $e) {
            throw new InvalidArgumentException(\sprintf('Cannot generate lazy proxy for service "%s".', $id ?? $definition->getClass()), 0, $e);
        }
    }
    /**
     * @param \Symfony\Component\DependencyInjection\Definition $definition
     * @param bool $asGhostObject
     * @param \ReflectionClass|null $class
     */
    public function getProxyClass($definition, $asGhostObject, &$class = null) : string
    {
        $class = new \ReflectionClass($definition->getClass());
        return \preg_replace('/^.*\\\\/', '', $class->name) . ($asGhostObject ? 'Ghost' : 'Proxy') . \ucfirst(\substr(\hash('sha256', $this->salt . '+' . $class->name), -7));
    }
}
