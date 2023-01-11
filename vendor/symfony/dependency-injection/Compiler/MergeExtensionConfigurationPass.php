<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace PrefixedByPoP\Symfony\Component\DependencyInjection\Compiler;

use PrefixedByPoP\Symfony\Component\Config\Definition\BaseNode;
use PrefixedByPoP\Symfony\Component\DependencyInjection\ContainerBuilder;
use PrefixedByPoP\Symfony\Component\DependencyInjection\Exception\LogicException;
use PrefixedByPoP\Symfony\Component\DependencyInjection\Exception\RuntimeException;
use PrefixedByPoP\Symfony\Component\DependencyInjection\Extension\ConfigurationExtensionInterface;
use PrefixedByPoP\Symfony\Component\DependencyInjection\Extension\Extension;
use PrefixedByPoP\Symfony\Component\DependencyInjection\Extension\ExtensionInterface;
use PrefixedByPoP\Symfony\Component\DependencyInjection\Extension\PrependExtensionInterface;
use PrefixedByPoP\Symfony\Component\DependencyInjection\ParameterBag\EnvPlaceholderParameterBag;
use PrefixedByPoP\Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
/**
 * Merges extension configs into the container builder.
 *
 * @author Fabien Potencier <fabien@symfony.com>
 */
class MergeExtensionConfigurationPass implements CompilerPassInterface
{
    /**
     * @param \Symfony\Component\DependencyInjection\ContainerBuilder $container
     */
    public function process($container)
    {
        $parameters = $container->getParameterBag()->all();
        $definitions = $container->getDefinitions();
        $aliases = $container->getAliases();
        $exprLangProviders = $container->getExpressionLanguageProviders();
        $configAvailable = \class_exists(BaseNode::class);
        foreach ($container->getExtensions() as $extension) {
            if ($extension instanceof PrependExtensionInterface) {
                $extension->prepend($container);
            }
        }
        foreach ($container->getExtensions() as $name => $extension) {
            if (!($config = $container->getExtensionConfig($name))) {
                // this extension was not called
                continue;
            }
            $resolvingBag = $container->getParameterBag();
            if ($resolvingBag instanceof EnvPlaceholderParameterBag && $extension instanceof Extension) {
                // create a dedicated bag so that we can track env vars per-extension
                $resolvingBag = new MergeExtensionConfigurationParameterBag($resolvingBag);
                if ($configAvailable) {
                    BaseNode::setPlaceholderUniquePrefix($resolvingBag->getEnvPlaceholderUniquePrefix());
                }
            }
            $config = $resolvingBag->resolveValue($config);
            try {
                $tmpContainer = new MergeExtensionConfigurationContainerBuilder($extension, $resolvingBag);
                $tmpContainer->setResourceTracking($container->isTrackingResources());
                $tmpContainer->addObjectResource($extension);
                if ($extension instanceof ConfigurationExtensionInterface && null !== ($configuration = $extension->getConfiguration($config, $tmpContainer))) {
                    $tmpContainer->addObjectResource($configuration);
                }
                foreach ($exprLangProviders as $provider) {
                    $tmpContainer->addExpressionLanguageProvider($provider);
                }
                $extension->load($config, $tmpContainer);
            } catch (\Exception $e) {
                if ($resolvingBag instanceof MergeExtensionConfigurationParameterBag) {
                    $container->getParameterBag()->mergeEnvPlaceholders($resolvingBag);
                }
                throw $e;
            }
            if ($resolvingBag instanceof MergeExtensionConfigurationParameterBag) {
                // don't keep track of env vars that are *overridden* when configs are merged
                $resolvingBag->freezeAfterProcessing($extension, $tmpContainer);
            }
            $container->merge($tmpContainer);
            $container->getParameterBag()->add($parameters);
        }
        $container->addDefinitions($definitions);
        $container->addAliases($aliases);
    }
}
/**
 * @internal
 */
class MergeExtensionConfigurationParameterBag extends EnvPlaceholderParameterBag
{
    /**
     * @var mixed[]
     */
    private $processedEnvPlaceholders;
    public function __construct(parent $parameterBag)
    {
        parent::__construct($parameterBag->all());
        $this->mergeEnvPlaceholders($parameterBag);
    }
    /**
     * @param \Symfony\Component\DependencyInjection\Extension\Extension $extension
     * @param \Symfony\Component\DependencyInjection\ContainerBuilder $container
     */
    public function freezeAfterProcessing($extension, $container)
    {
        if (!($config = $extension->getProcessedConfigs())) {
            // Extension::processConfiguration() wasn't called, we cannot know how configs were merged
            return;
        }
        $this->processedEnvPlaceholders = [];
        // serialize config and container to catch env vars nested in object graphs
        $config = \serialize($config) . \serialize($container->getDefinitions()) . \serialize($container->getAliases()) . \serialize($container->getParameterBag()->all());
        if (\false === \stripos($config, 'env_')) {
            return;
        }
        \preg_match_all('/env_[a-f0-9]{16}_\\w+_[a-f0-9]{32}/Ui', $config, $matches);
        $usedPlaceholders = \array_flip($matches[0]);
        foreach (parent::getEnvPlaceholders() as $env => $placeholders) {
            foreach ($placeholders as $placeholder) {
                if (isset($usedPlaceholders[$placeholder])) {
                    $this->processedEnvPlaceholders[$env] = $placeholders;
                    break;
                }
            }
        }
    }
    public function getEnvPlaceholders() : array
    {
        return $this->processedEnvPlaceholders ?? parent::getEnvPlaceholders();
    }
    public function getUnusedEnvPlaceholders() : array
    {
        return !isset($this->processedEnvPlaceholders) ? [] : \array_diff_key(parent::getEnvPlaceholders(), $this->processedEnvPlaceholders);
    }
}
/**
 * A container builder preventing using methods that wouldn't have any effect from extensions.
 *
 * @internal
 */
class MergeExtensionConfigurationContainerBuilder extends ContainerBuilder
{
    /**
     * @var string
     */
    private $extensionClass;
    public function __construct(ExtensionInterface $extension, ParameterBagInterface $parameterBag = null)
    {
        parent::__construct($parameterBag);
        $this->extensionClass = \get_class($extension);
    }
    /**
     * @return $this
     * @param \Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface $pass
     * @param string $type
     * @param int $priority
     */
    public function addCompilerPass($pass, $type = PassConfig::TYPE_BEFORE_OPTIMIZATION, $priority = 0)
    {
        throw new LogicException(\sprintf('You cannot add compiler pass "%s" from extension "%s". Compiler passes must be registered before the container is compiled.', \get_debug_type($pass), $this->extensionClass));
    }
    /**
     * @param \Symfony\Component\DependencyInjection\Extension\ExtensionInterface $extension
     */
    public function registerExtension($extension)
    {
        throw new LogicException(\sprintf('You cannot register extension "%s" from "%s". Extensions must be registered before the container is compiled.', \get_debug_type($extension), $this->extensionClass));
    }
    /**
     * @param bool $resolveEnvPlaceholders
     */
    public function compile($resolveEnvPlaceholders = \false)
    {
        throw new LogicException(\sprintf('Cannot compile the container in extension "%s".', $this->extensionClass));
    }
    /**
     * @param string|bool $format
     * @param mixed $value
     * @return mixed
     * @param mixed[]|null $usedEnvs
     */
    public function resolveEnvPlaceholders($value, $format = null, &$usedEnvs = null)
    {
        if (\true !== $format || !\is_string($value)) {
            return parent::resolveEnvPlaceholders($value, $format, $usedEnvs);
        }
        $bag = $this->getParameterBag();
        $value = $bag->resolveValue($value);
        if (!$bag instanceof EnvPlaceholderParameterBag) {
            return parent::resolveEnvPlaceholders($value, $format, $usedEnvs);
        }
        foreach ($bag->getEnvPlaceholders() as $env => $placeholders) {
            if (\strpos($env, ':') === \false) {
                continue;
            }
            foreach ($placeholders as $placeholder) {
                if (\false !== \stripos($value, $placeholder)) {
                    throw new RuntimeException(\sprintf('Using a cast in "env(%s)" is incompatible with resolution at compile time in "%s". The logic in the extension should be moved to a compiler pass, or an env parameter with no cast should be used instead.', $env, $this->extensionClass));
                }
            }
        }
        return parent::resolveEnvPlaceholders($value, $format, $usedEnvs);
    }
}
