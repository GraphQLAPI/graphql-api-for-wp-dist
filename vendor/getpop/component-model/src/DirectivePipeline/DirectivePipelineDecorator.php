<?php

declare (strict_types=1);
namespace PoP\ComponentModel\DirectivePipeline;

use PoP\ComponentModel\DirectiveResolvers\FieldDirectiveResolverInterface;
use PrefixedByPoP\League\Pipeline\PipelineInterface;
use PoP\ComponentModel\Engine\EngineIterationFieldSet;
use PoP\ComponentModel\Feedback\EngineIterationFeedbackStore;
use PoP\ComponentModel\QueryResolution\FieldDataAccessProviderInterface;
use PoP\ComponentModel\TypeResolvers\RelationalTypeResolverInterface;
use PoP\GraphQLParser\Spec\Parser\Ast\FieldInterface;
use SplObjectStorage;
class DirectivePipelineDecorator
{
    /**
     * @readonly
     * @var \League\Pipeline\PipelineInterface
     */
    private $pipeline;
    public function __construct(PipelineInterface $pipeline)
    {
        $this->pipeline = $pipeline;
    }
    /**
     * @param array<array<string|int,EngineIterationFieldSet>> $pipelineIDFieldSet
     * @param array<FieldDataAccessProviderInterface> $pipelineFieldDataAccessProviders
     * @param array<string,array<string|int,SplObjectStorage<FieldInterface,mixed>>> $previouslyResolvedIDFieldValues
     * @param array<string|int,SplObjectStorage<FieldInterface,mixed>> $resolvedIDFieldValues
     * @param array<FieldDirectiveResolverInterface> $pipelineFieldDirectiveResolvers
     * @param array<string|int,object> $idObjects
     * @param array<string,array<string|int,SplObjectStorage<FieldInterface,array<string|int>>>> $unionTypeOutputKeyIDs
     * @param array<string,mixed> $messages
     * @param \PoP\ComponentModel\TypeResolvers\RelationalTypeResolverInterface $relationalTypeResolver
     * @param \PoP\ComponentModel\Feedback\EngineIterationFeedbackStore $engineIterationFeedbackStore
     */
    public function resolveDirectivePipeline($relationalTypeResolver, $pipelineIDFieldSet, $pipelineFieldDataAccessProviders, $pipelineFieldDirectiveResolvers, $idObjects, $unionTypeOutputKeyIDs, $previouslyResolvedIDFieldValues, &$resolvedIDFieldValues, &$messages, $engineIterationFeedbackStore) : void
    {
        $payload = $this->pipeline->__invoke(\PoP\ComponentModel\DirectivePipeline\DirectivePipelineUtils::convertArgumentsToPayload($relationalTypeResolver, $pipelineFieldDirectiveResolvers, $idObjects, $unionTypeOutputKeyIDs, $previouslyResolvedIDFieldValues, $pipelineIDFieldSet, $pipelineFieldDataAccessProviders, $resolvedIDFieldValues, $messages, $engineIterationFeedbackStore));
        list($relationalTypeResolver, $pipelineFieldDirectiveResolvers, $idObjects, $unionTypeOutputKeyIDs, $previouslyResolvedIDFieldValues, $pipelineIDFieldSet, $pipelineFieldDataAccessProviders, $resolvedIDFieldValues, $messages, $engineIterationFeedbackStore, ) = \PoP\ComponentModel\DirectivePipeline\DirectivePipelineUtils::extractArgumentsFromPayload($payload);
    }
}
