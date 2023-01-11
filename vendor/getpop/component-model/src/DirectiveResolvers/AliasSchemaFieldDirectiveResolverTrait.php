<?php

declare (strict_types=1);
namespace PoP\ComponentModel\DirectiveResolvers;

use PoP\ComponentModel\QueryResolution\FieldDataAccessProviderInterface;
use PoP\ComponentModel\Engine\EngineIterationFieldSet;
use PoP\ComponentModel\Feedback\EngineIterationFeedbackStore;
use PoP\ComponentModel\TypeResolvers\InputTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\RelationalTypeResolverInterface;
use PoP\GraphQLParser\Spec\Parser\Ast\Directive;
use PoP\GraphQLParser\Spec\Parser\Ast\FieldInterface;
use SplObjectStorage;
/**
 * Create an alias of a directive, to use when:
 *
 * - the same directive is registered more than once (eg: by different plugins)
 * - want to rename the directive (steps: alias the directive, then remove access to the original)
 *
 * This trait, to be applied on a DirectiveResolver class, uses the Proxy design pattern:
 * every function executed on the aliasing directive executes the same function on the aliased directive.
 *
 * The aliased DirectiveResolver is chosen to be of class `AbstractFieldDirectiveResolver`,
 * since it comprises interfaces `FieldDirectiveResolverInterface`
 * and `SchemaFieldDirectiveResolverInterface`, whose functions must be aliased.
 *
 * @author Leonardo Losoviz <leo@getpop.org>
 */
trait AliasSchemaFieldDirectiveResolverTrait
{
    /**
     * The specific `DirectiveResolver` class that is being aliased
     */
    protected abstract function getAliasedFieldDirectiveResolver() : \PoP\ComponentModel\DirectiveResolvers\AbstractFieldDirectiveResolver;
    /**
     * Proxy pattern: execute same function on the aliased DirectiveResolver
     * @param \PoP\ComponentModel\TypeResolvers\RelationalTypeResolverInterface $relationalTypeResolver
     */
    public function getDirectiveDescription($relationalTypeResolver) : ?string
    {
        $aliasedFieldDirectiveResolver = $this->getAliasedFieldDirectiveResolver();
        return $aliasedFieldDirectiveResolver->getDirectiveDescription($relationalTypeResolver);
    }
    /**
     * Proxy pattern: execute same function on the aliased DirectiveResolver
     *
     * @return array<string,InputTypeResolverInterface>
     * @param \PoP\ComponentModel\TypeResolvers\RelationalTypeResolverInterface $relationalTypeResolver
     */
    public function getDirectiveArgNameTypeResolvers($relationalTypeResolver) : array
    {
        $aliasedFieldDirectiveResolver = $this->getAliasedFieldDirectiveResolver();
        return $aliasedFieldDirectiveResolver->getDirectiveArgNameTypeResolvers($relationalTypeResolver);
    }
    /**
     * Proxy pattern: execute same function on the aliased DirectiveResolver
     * @param \PoP\ComponentModel\TypeResolvers\RelationalTypeResolverInterface $relationalTypeResolver
     * @param string $directiveArgName
     */
    public function getDirectiveArgDescription($relationalTypeResolver, $directiveArgName) : ?string
    {
        $aliasedFieldDirectiveResolver = $this->getAliasedFieldDirectiveResolver();
        return $aliasedFieldDirectiveResolver->getDirectiveArgDescription($relationalTypeResolver, $directiveArgName);
    }
    /**
     * Proxy pattern: execute same function on the aliased DirectiveResolver
     * @return mixed
     * @param \PoP\ComponentModel\TypeResolvers\RelationalTypeResolverInterface $relationalTypeResolver
     * @param string $directiveArgName
     */
    public function getDirectiveArgDefaultValue($relationalTypeResolver, $directiveArgName)
    {
        $aliasedFieldDirectiveResolver = $this->getAliasedFieldDirectiveResolver();
        return $aliasedFieldDirectiveResolver->getDirectiveArgDefaultValue($relationalTypeResolver, $directiveArgName);
    }
    /**
     * Proxy pattern: execute same function on the aliased DirectiveResolver
     * @param \PoP\ComponentModel\TypeResolvers\RelationalTypeResolverInterface $relationalTypeResolver
     * @param string $directiveArgName
     */
    public function getDirectiveArgTypeModifiers($relationalTypeResolver, $directiveArgName) : int
    {
        $aliasedFieldDirectiveResolver = $this->getAliasedFieldDirectiveResolver();
        return $aliasedFieldDirectiveResolver->getDirectiveArgTypeModifiers($relationalTypeResolver, $directiveArgName);
    }
    /**
     * Proxy pattern: execute same function on the aliased DirectiveResolver
     * @return mixed[]
     * @param \PoP\ComponentModel\TypeResolvers\RelationalTypeResolverInterface $relationalTypeResolver
     */
    public function getConsolidatedDirectiveArgNameTypeResolvers($relationalTypeResolver) : array
    {
        $aliasedFieldDirectiveResolver = $this->getAliasedFieldDirectiveResolver();
        return $aliasedFieldDirectiveResolver->getConsolidatedDirectiveArgNameTypeResolvers($relationalTypeResolver);
    }
    /**
     * Proxy pattern: execute same function on the aliased DirectiveResolver
     * @param \PoP\ComponentModel\TypeResolvers\RelationalTypeResolverInterface $relationalTypeResolver
     * @param string $directiveArgName
     */
    public function getConsolidatedDirectiveArgDescription($relationalTypeResolver, $directiveArgName) : ?string
    {
        $aliasedFieldDirectiveResolver = $this->getAliasedFieldDirectiveResolver();
        return $aliasedFieldDirectiveResolver->getConsolidatedDirectiveArgDescription($relationalTypeResolver, $directiveArgName);
    }
    /**
     * Proxy pattern: execute same function on the aliased DirectiveResolver
     * @return mixed
     * @param \PoP\ComponentModel\TypeResolvers\RelationalTypeResolverInterface $relationalTypeResolver
     * @param string $directiveArgName
     */
    public function getConsolidatedDirectiveArgDefaultValue($relationalTypeResolver, $directiveArgName)
    {
        $aliasedFieldDirectiveResolver = $this->getAliasedFieldDirectiveResolver();
        return $aliasedFieldDirectiveResolver->getConsolidatedDirectiveArgDefaultValue($relationalTypeResolver, $directiveArgName);
    }
    /**
     * Proxy pattern: execute same function on the aliased DirectiveResolver
     * @param \PoP\ComponentModel\TypeResolvers\RelationalTypeResolverInterface $relationalTypeResolver
     * @param string $directiveArgName
     */
    public function getConsolidatedDirectiveArgTypeModifiers($relationalTypeResolver, $directiveArgName) : int
    {
        $aliasedFieldDirectiveResolver = $this->getAliasedFieldDirectiveResolver();
        return $aliasedFieldDirectiveResolver->getConsolidatedDirectiveArgTypeModifiers($relationalTypeResolver, $directiveArgName);
    }
    /**
     * Proxy pattern: execute same function on the aliased DirectiveResolver
     * @param \PoP\ComponentModel\TypeResolvers\RelationalTypeResolverInterface $relationalTypeResolver
     */
    public function getDirectiveDeprecationMessage($relationalTypeResolver) : ?string
    {
        $aliasedFieldDirectiveResolver = $this->getAliasedFieldDirectiveResolver();
        return $aliasedFieldDirectiveResolver->getDirectiveDeprecationMessage($relationalTypeResolver);
    }
    /**
     * Proxy pattern: execute same function on the aliased DirectiveResolver
     * @param \PoP\ComponentModel\TypeResolvers\RelationalTypeResolverInterface $relationalTypeResolver
     */
    public function isGlobal($relationalTypeResolver) : bool
    {
        $aliasedFieldDirectiveResolver = $this->getAliasedFieldDirectiveResolver();
        return $aliasedFieldDirectiveResolver->isGlobal($relationalTypeResolver);
    }
    /**
     * Proxy pattern: execute same function on the aliased DirectiveResolver
     * @return mixed[]
     */
    public function getFieldNamesToApplyTo() : array
    {
        $aliasedFieldDirectiveResolver = $this->getAliasedFieldDirectiveResolver();
        return $aliasedFieldDirectiveResolver->getFieldNamesToApplyTo();
    }
    /**
     * Proxy pattern: execute same function on the aliased DirectiveResolver
     */
    public function getDirectiveKind() : string
    {
        $aliasedFieldDirectiveResolver = $this->getAliasedFieldDirectiveResolver();
        return $aliasedFieldDirectiveResolver->getDirectiveKind();
    }
    /**
     * Proxy pattern: execute same function on the aliased DirectiveResolver
     */
    public function getPipelinePosition() : string
    {
        $aliasedFieldDirectiveResolver = $this->getAliasedFieldDirectiveResolver();
        return $aliasedFieldDirectiveResolver->getPipelinePosition();
    }
    /**
     * Proxy pattern: execute same function on the aliased DirectiveResolver
     */
    public function isDirectiveEnabled() : bool
    {
        $aliasedFieldDirectiveResolver = $this->getAliasedFieldDirectiveResolver();
        return $aliasedFieldDirectiveResolver->isDirectiveEnabled();
    }
    /**
     * Proxy pattern: execute same function on the aliased DirectiveResolver
     * @param \PoP\ComponentModel\TypeResolvers\RelationalTypeResolverInterface $relationalTypeResolver
     * @param \PoP\GraphQLParser\Spec\Parser\Ast\Directive $directive
     */
    public function resolveCanProcessDirective($relationalTypeResolver, $directive) : bool
    {
        $aliasedFieldDirectiveResolver = $this->getAliasedFieldDirectiveResolver();
        return $aliasedFieldDirectiveResolver->resolveCanProcessDirective($relationalTypeResolver, $directive);
    }
    /**
     * Proxy pattern: execute same function on the aliased DirectiveResolver
     */
    public function isRepeatable() : bool
    {
        $aliasedFieldDirectiveResolver = $this->getAliasedFieldDirectiveResolver();
        return $aliasedFieldDirectiveResolver->isRepeatable();
    }
    /**
     * Proxy pattern: execute same function on the aliased DirectiveResolver
     */
    public function needsSomeIDFieldToExecute() : bool
    {
        $aliasedFieldDirectiveResolver = $this->getAliasedFieldDirectiveResolver();
        return $aliasedFieldDirectiveResolver->needsSomeIDFieldToExecute();
    }
    /**
     * Proxy pattern: execute same function on the aliased DirectiveResolver
     *
     * @param array<string|int,EngineIterationFieldSet> $idFieldSet
     * @param array<array<string|int,EngineIterationFieldSet>> $succeedingPipelineIDFieldSet
     * @param array<FieldDataAccessProviderInterface> $succeedingPipelineFieldDataAccessProviders
     * @param array<string,array<string|int,SplObjectStorage<FieldInterface,mixed>>> $previouslyResolvedIDFieldValues
     * @param array<string|int,SplObjectStorage<FieldInterface,mixed>> $resolvedIDFieldValues
     * @param array<FieldDirectiveResolverInterface> $succeedingPipelineFieldDirectiveResolvers
     * @param array<string|int,object> $idObjects
     * @param array<string,array<string|int,SplObjectStorage<FieldInterface,array<string|int>>>> $unionTypeOutputKeyIDs
     * @param array<string,mixed> $messages
     * @param \PoP\ComponentModel\TypeResolvers\RelationalTypeResolverInterface $relationalTypeResolver
     * @param \PoP\ComponentModel\QueryResolution\FieldDataAccessProviderInterface $fieldDataAccessProvider
     * @param \PoP\ComponentModel\Feedback\EngineIterationFeedbackStore $engineIterationFeedbackStore
     */
    public function resolveDirective($relationalTypeResolver, $idFieldSet, $fieldDataAccessProvider, $succeedingPipelineFieldDirectiveResolvers, $idObjects, $unionTypeOutputKeyIDs, $previouslyResolvedIDFieldValues, &$succeedingPipelineIDFieldSet, &$succeedingPipelineFieldDataAccessProviders, &$resolvedIDFieldValues, &$messages, $engineIterationFeedbackStore) : void
    {
        $aliasedFieldDirectiveResolver = $this->getAliasedFieldDirectiveResolver();
        $aliasedFieldDirectiveResolver->resolveDirective($relationalTypeResolver, $idFieldSet, $fieldDataAccessProvider, $succeedingPipelineFieldDirectiveResolvers, $idObjects, $unionTypeOutputKeyIDs, $previouslyResolvedIDFieldValues, $succeedingPipelineIDFieldSet, $succeedingPipelineFieldDataAccessProviders, $resolvedIDFieldValues, $messages, $engineIterationFeedbackStore);
    }
    /**
     * Proxy pattern: execute same function on the aliased DirectiveResolver
     * @param \PoP\ComponentModel\TypeResolvers\RelationalTypeResolverInterface $relationalTypeResolver
     */
    public function skipExposingDirectiveInSchema($relationalTypeResolver) : bool
    {
        $aliasedFieldDirectiveResolver = $this->getAliasedFieldDirectiveResolver();
        return $aliasedFieldDirectiveResolver->skipExposingDirectiveInSchema($relationalTypeResolver);
    }
    /**
     * Proxy pattern: execute same function on the aliased DirectiveResolver
     * @param \PoP\ComponentModel\TypeResolvers\RelationalTypeResolverInterface $relationalTypeResolver
     */
    public function getDirectiveVersion($relationalTypeResolver) : ?string
    {
        $aliasedFieldDirectiveResolver = $this->getAliasedFieldDirectiveResolver();
        return $aliasedFieldDirectiveResolver->getDirectiveVersion($relationalTypeResolver);
    }
}
