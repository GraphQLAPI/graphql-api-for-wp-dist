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

use PrefixedByPoP\Symfony\Component\DependencyInjection\Exception\InvalidArgumentException;
/**
 * Compiler Pass Configuration.
 *
 * This class has a default configuration embedded.
 *
 * @author Johannes M. Schmitt <schmittjoh@gmail.com>
 */
class PassConfig
{
    public const TYPE_AFTER_REMOVING = 'afterRemoving';
    public const TYPE_BEFORE_OPTIMIZATION = 'beforeOptimization';
    public const TYPE_BEFORE_REMOVING = 'beforeRemoving';
    public const TYPE_OPTIMIZE = 'optimization';
    public const TYPE_REMOVE = 'removing';
    private $mergePass;
    private $afterRemovingPasses = [];
    private $beforeOptimizationPasses = [];
    private $beforeRemovingPasses = [];
    private $optimizationPasses;
    private $removingPasses;
    public function __construct()
    {
        $this->mergePass = new \PrefixedByPoP\Symfony\Component\DependencyInjection\Compiler\MergeExtensionConfigurationPass();
        $this->beforeOptimizationPasses = [100 => [new \PrefixedByPoP\Symfony\Component\DependencyInjection\Compiler\ResolveClassPass(), new \PrefixedByPoP\Symfony\Component\DependencyInjection\Compiler\ResolveInstanceofConditionalsPass(), new \PrefixedByPoP\Symfony\Component\DependencyInjection\Compiler\RegisterEnvVarProcessorsPass()], -1000 => [new \PrefixedByPoP\Symfony\Component\DependencyInjection\Compiler\ExtensionCompilerPass()]];
        $this->optimizationPasses = [[new \PrefixedByPoP\Symfony\Component\DependencyInjection\Compiler\AutoAliasServicePass(), new \PrefixedByPoP\Symfony\Component\DependencyInjection\Compiler\ValidateEnvPlaceholdersPass(), new \PrefixedByPoP\Symfony\Component\DependencyInjection\Compiler\ResolveDecoratorStackPass(), new \PrefixedByPoP\Symfony\Component\DependencyInjection\Compiler\ResolveChildDefinitionsPass(), new \PrefixedByPoP\Symfony\Component\DependencyInjection\Compiler\RegisterServiceSubscribersPass(), new \PrefixedByPoP\Symfony\Component\DependencyInjection\Compiler\ResolveParameterPlaceHoldersPass(\false, \false), new \PrefixedByPoP\Symfony\Component\DependencyInjection\Compiler\ResolveFactoryClassPass(), new \PrefixedByPoP\Symfony\Component\DependencyInjection\Compiler\ResolveNamedArgumentsPass(), new \PrefixedByPoP\Symfony\Component\DependencyInjection\Compiler\AutowireRequiredMethodsPass(), new \PrefixedByPoP\Symfony\Component\DependencyInjection\Compiler\AutowireRequiredPropertiesPass(), new \PrefixedByPoP\Symfony\Component\DependencyInjection\Compiler\ResolveBindingsPass(), new \PrefixedByPoP\Symfony\Component\DependencyInjection\Compiler\ServiceLocatorTagPass(), new \PrefixedByPoP\Symfony\Component\DependencyInjection\Compiler\DecoratorServicePass(), new \PrefixedByPoP\Symfony\Component\DependencyInjection\Compiler\CheckDefinitionValidityPass(), new \PrefixedByPoP\Symfony\Component\DependencyInjection\Compiler\AutowirePass(\false), new \PrefixedByPoP\Symfony\Component\DependencyInjection\Compiler\ResolveTaggedIteratorArgumentPass(), new \PrefixedByPoP\Symfony\Component\DependencyInjection\Compiler\ResolveServiceSubscribersPass(), new \PrefixedByPoP\Symfony\Component\DependencyInjection\Compiler\ResolveReferencesToAliasesPass(), new \PrefixedByPoP\Symfony\Component\DependencyInjection\Compiler\ResolveInvalidReferencesPass(), new \PrefixedByPoP\Symfony\Component\DependencyInjection\Compiler\AnalyzeServiceReferencesPass(\true), new \PrefixedByPoP\Symfony\Component\DependencyInjection\Compiler\CheckCircularReferencesPass(), new \PrefixedByPoP\Symfony\Component\DependencyInjection\Compiler\CheckReferenceValidityPass(), new \PrefixedByPoP\Symfony\Component\DependencyInjection\Compiler\CheckArgumentsValidityPass(\false)]];
        $this->removingPasses = [[new \PrefixedByPoP\Symfony\Component\DependencyInjection\Compiler\RemovePrivateAliasesPass(), new \PrefixedByPoP\Symfony\Component\DependencyInjection\Compiler\ReplaceAliasByActualDefinitionPass(), new \PrefixedByPoP\Symfony\Component\DependencyInjection\Compiler\RemoveAbstractDefinitionsPass(), new \PrefixedByPoP\Symfony\Component\DependencyInjection\Compiler\RemoveUnusedDefinitionsPass(), new \PrefixedByPoP\Symfony\Component\DependencyInjection\Compiler\AnalyzeServiceReferencesPass(), new \PrefixedByPoP\Symfony\Component\DependencyInjection\Compiler\CheckExceptionOnInvalidReferenceBehaviorPass(), new \PrefixedByPoP\Symfony\Component\DependencyInjection\Compiler\InlineServiceDefinitionsPass(new \PrefixedByPoP\Symfony\Component\DependencyInjection\Compiler\AnalyzeServiceReferencesPass()), new \PrefixedByPoP\Symfony\Component\DependencyInjection\Compiler\AnalyzeServiceReferencesPass(), new \PrefixedByPoP\Symfony\Component\DependencyInjection\Compiler\DefinitionErrorExceptionPass()]];
        $this->afterRemovingPasses = [[new \PrefixedByPoP\Symfony\Component\DependencyInjection\Compiler\ResolveHotPathPass(), new \PrefixedByPoP\Symfony\Component\DependencyInjection\Compiler\ResolveNoPreloadPass(), new \PrefixedByPoP\Symfony\Component\DependencyInjection\Compiler\AliasDeprecatedPublicServicesPass()]];
    }
    /**
     * Returns all passes in order to be processed.
     *
     * @return CompilerPassInterface[]
     */
    public function getPasses()
    {
        return \array_merge([$this->mergePass], $this->getBeforeOptimizationPasses(), $this->getOptimizationPasses(), $this->getBeforeRemovingPasses(), $this->getRemovingPasses(), $this->getAfterRemovingPasses());
    }
    /**
     * Adds a pass.
     *
     * @throws InvalidArgumentException when a pass type doesn't exist
     */
    public function addPass(\PrefixedByPoP\Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface $pass, string $type = self::TYPE_BEFORE_OPTIMIZATION, int $priority = 0)
    {
        $property = $type . 'Passes';
        if (!isset($this->{$property})) {
            throw new \PrefixedByPoP\Symfony\Component\DependencyInjection\Exception\InvalidArgumentException(\sprintf('Invalid type "%s".', $type));
        }
        $passes =& $this->{$property};
        if (!isset($passes[$priority])) {
            $passes[$priority] = [];
        }
        $passes[$priority][] = $pass;
    }
    /**
     * Gets all passes for the AfterRemoving pass.
     *
     * @return CompilerPassInterface[]
     */
    public function getAfterRemovingPasses()
    {
        return $this->sortPasses($this->afterRemovingPasses);
    }
    /**
     * Gets all passes for the BeforeOptimization pass.
     *
     * @return CompilerPassInterface[]
     */
    public function getBeforeOptimizationPasses()
    {
        return $this->sortPasses($this->beforeOptimizationPasses);
    }
    /**
     * Gets all passes for the BeforeRemoving pass.
     *
     * @return CompilerPassInterface[]
     */
    public function getBeforeRemovingPasses()
    {
        return $this->sortPasses($this->beforeRemovingPasses);
    }
    /**
     * Gets all passes for the Optimization pass.
     *
     * @return CompilerPassInterface[]
     */
    public function getOptimizationPasses()
    {
        return $this->sortPasses($this->optimizationPasses);
    }
    /**
     * Gets all passes for the Removing pass.
     *
     * @return CompilerPassInterface[]
     */
    public function getRemovingPasses()
    {
        return $this->sortPasses($this->removingPasses);
    }
    /**
     * Gets the Merge pass.
     *
     * @return CompilerPassInterface
     */
    public function getMergePass()
    {
        return $this->mergePass;
    }
    public function setMergePass(\PrefixedByPoP\Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface $pass)
    {
        $this->mergePass = $pass;
    }
    /**
     * Sets the AfterRemoving passes.
     *
     * @param CompilerPassInterface[] $passes
     */
    public function setAfterRemovingPasses(array $passes)
    {
        $this->afterRemovingPasses = [$passes];
    }
    /**
     * Sets the BeforeOptimization passes.
     *
     * @param CompilerPassInterface[] $passes
     */
    public function setBeforeOptimizationPasses(array $passes)
    {
        $this->beforeOptimizationPasses = [$passes];
    }
    /**
     * Sets the BeforeRemoving passes.
     *
     * @param CompilerPassInterface[] $passes
     */
    public function setBeforeRemovingPasses(array $passes)
    {
        $this->beforeRemovingPasses = [$passes];
    }
    /**
     * Sets the Optimization passes.
     *
     * @param CompilerPassInterface[] $passes
     */
    public function setOptimizationPasses(array $passes)
    {
        $this->optimizationPasses = [$passes];
    }
    /**
     * Sets the Removing passes.
     *
     * @param CompilerPassInterface[] $passes
     */
    public function setRemovingPasses(array $passes)
    {
        $this->removingPasses = [$passes];
    }
    /**
     * Sort passes by priority.
     *
     * @param array $passes CompilerPassInterface instances with their priority as key
     *
     * @return CompilerPassInterface[]
     */
    private function sortPasses(array $passes) : array
    {
        if (0 === \count($passes)) {
            return [];
        }
        \krsort($passes);
        // Flatten the array
        return \array_merge(...$passes);
    }
}
