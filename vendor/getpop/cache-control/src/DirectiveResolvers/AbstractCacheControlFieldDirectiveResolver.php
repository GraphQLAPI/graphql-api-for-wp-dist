<?php

declare (strict_types=1);
namespace PoP\CacheControl\DirectiveResolvers;

use PoP\CacheControl\FeedbackItemProviders\FeedbackItemProvider;
use PoP\CacheControl\Managers\CacheControlEngineInterface;
use PoP\ComponentModel\DirectiveResolvers\FieldDirectiveResolverInterface;
use PoP\ComponentModel\Directives\DirectiveKinds;
use PoP\ComponentModel\Directives\FieldDirectiveBehaviors;
use PoP\ComponentModel\Engine\EngineIterationFieldSet;
use PoP\ComponentModel\Feedback\EngineIterationFeedbackStore;
use PoP\ComponentModel\Feedback\ObjectTypeFieldResolutionFeedback;
use PoP\ComponentModel\Feedback\ObjectTypeFieldResolutionFeedbackStore;
use PoP\ComponentModel\QueryResolution\FieldDataAccessProviderInterface;
use PoP\ComponentModel\TypeResolvers\InputTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\RelationalTypeResolverInterface;
use PoP\Engine\DirectiveResolvers\AbstractGlobalFieldDirectiveResolver;
use PoP\GraphQLParser\Spec\Parser\Ast\AstInterface;
use PoP\GraphQLParser\Spec\Parser\Ast\FieldInterface;
use PoP\Root\Feedback\FeedbackItemResolution;
use SplObjectStorage;
abstract class AbstractCacheControlFieldDirectiveResolver extends AbstractGlobalFieldDirectiveResolver implements \PoP\CacheControl\DirectiveResolvers\CacheControlFieldDirectiveResolverInterface
{
    /**
     * @var \PoP\CacheControl\Managers\CacheControlEngineInterface|null
     */
    private $cacheControlEngine;
    /**
     * @param \PoP\CacheControl\Managers\CacheControlEngineInterface $cacheControlEngine
     */
    public final function setCacheControlEngine($cacheControlEngine) : void
    {
        $this->cacheControlEngine = $cacheControlEngine;
    }
    protected final function getCacheControlEngine() : CacheControlEngineInterface
    {
        /** @var CacheControlEngineInterface */
        return $this->cacheControlEngine = $this->cacheControlEngine ?? $this->instanceManager->getInstance(CacheControlEngineInterface::class);
    }
    public function getDirectiveName() : string
    {
        return 'cacheControl';
    }
    /**
     * @cacheControl will never be applied to the Operation,
     * however as the @validate... directives can be added
     * to the Operation, and these add @cacheControl as a
     * dependency, then also enable Operations for it.
     */
    public function getFieldDirectiveBehavior() : string
    {
        return FieldDirectiveBehaviors::FIELD_AND_OPERATION;
    }
    /**
     * Set the cache even when there are no elements: they might've been removed due to some validation, and this caching maxAge must be respected!
     */
    public function needsSomeIDFieldToExecute() : bool
    {
        return \false;
    }
    /**
     * Because this directive will be implemented several times, make its schema definition be added only once
     * @param \PoP\ComponentModel\TypeResolvers\RelationalTypeResolverInterface $relationalTypeResolver
     */
    public function skipExposingDirectiveInSchema($relationalTypeResolver) : bool
    {
        return \true;
    }
    /**
     * This is a "Schema" type directive
     */
    public function getDirectiveKind() : string
    {
        return DirectiveKinds::SCHEMA;
    }
    /**
     * Allow it to execute multiple times
     */
    public function isRepeatable() : bool
    {
        return \true;
    }
    /**
     * @param \PoP\ComponentModel\TypeResolvers\RelationalTypeResolverInterface $relationalTypeResolver
     */
    public function getDirectiveDescription($relationalTypeResolver) : ?string
    {
        return $this->__('HTTP caching (https://tools.ietf.org/html/rfc7234): Cache the response by setting a Cache-Control header with a max-age value; this value is calculated as the minimum max-age value among all requested fields. If any field has max-age: 0, a corresponding \'no-store\' value is sent, indicating to not cache the response', 'cache-control');
    }
    /**
     * @return array<string,InputTypeResolverInterface>
     * @param \PoP\ComponentModel\TypeResolvers\RelationalTypeResolverInterface $relationalTypeResolver
     */
    public function getDirectiveArgNameTypeResolvers($relationalTypeResolver) : array
    {
        return \array_merge(parent::getDirectiveArgNameTypeResolvers($relationalTypeResolver), ['maxAge' => $this->getIntScalarTypeResolver()]);
    }
    /**
     * @param \PoP\ComponentModel\TypeResolvers\RelationalTypeResolverInterface $relationalTypeResolver
     * @param string $directiveArgName
     */
    public function getDirectiveArgDescription($relationalTypeResolver, $directiveArgName) : ?string
    {
        switch ($directiveArgName) {
            case 'maxAge':
                return $this->__('Use a specific max-age value for the field, instead of the one configured in the directive', 'cache-control');
            default:
                return parent::getDirectiveArgDescription($relationalTypeResolver, $directiveArgName);
        }
    }
    /**
     * Validate the constraints for a directive argument
     * @param mixed $directiveArgValue
     * @param string $directiveArgName
     * @param \PoP\GraphQLParser\Spec\Parser\Ast\AstInterface $astNode
     * @param \PoP\ComponentModel\Feedback\ObjectTypeFieldResolutionFeedbackStore $objectTypeFieldResolutionFeedbackStore
     */
    protected function validateDirectiveArgValue($directiveArgName, $directiveArgValue, $astNode, $objectTypeFieldResolutionFeedbackStore) : void
    {
        parent::validateDirectiveArgValue($directiveArgName, $directiveArgValue, $astNode, $objectTypeFieldResolutionFeedbackStore);
        switch ($directiveArgName) {
            case 'maxAge':
                if ($directiveArgValue < 0) {
                    $objectTypeFieldResolutionFeedbackStore->addError(new ObjectTypeFieldResolutionFeedback(new FeedbackItemResolution(FeedbackItemProvider::class, FeedbackItemProvider::E1), $astNode));
                }
                break;
        }
    }
    /**
     * Get the cache control for this field, and set it on the Engine
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
        $this->resolveCacheControlDirective();
    }
    public function resolveCacheControlDirective() : void
    {
        // Set the max age from this field into the service which will calculate the max age for the request, based on all fields
        // If it was provided as a directiveArg, use that value. Otherwise, use the one from the class
        $directiveArgs = $this->directiveDataAccessor->getDirectiveArgs();
        $maxAge = $directiveArgs['maxAge'] ?? $this->getMaxAge();
        if (!\is_null($maxAge)) {
            $this->getCacheControlEngine()->addMaxAge($maxAge);
        }
    }
    public abstract function getMaxAge() : ?int;
}
