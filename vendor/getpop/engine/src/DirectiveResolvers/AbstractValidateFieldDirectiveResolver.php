<?php

declare (strict_types=1);
namespace PoP\Engine\DirectiveResolvers;

use PoP\ComponentModel\DirectiveResolvers\FieldDirectiveResolverInterface;
use PoP\ComponentModel\DirectiveResolvers\RemoveIDFieldSetFieldDirectiveResolverTrait;
use PoP\ComponentModel\Directives\DirectiveKinds;
use PoP\ComponentModel\Engine\EngineIterationFieldSet;
use PoP\ComponentModel\Feedback\EngineIterationFeedbackStore;
use PoP\ComponentModel\QueryResolution\FieldDataAccessProviderInterface;
use PoP\ComponentModel\TypeResolvers\RelationalTypeResolverInterface;
use PoP\GraphQLParser\Spec\Parser\Ast\FieldInterface;
use SplObjectStorage;
abstract class AbstractValidateFieldDirectiveResolver extends \PoP\Engine\DirectiveResolvers\AbstractGlobalFieldDirectiveResolver
{
    use RemoveIDFieldSetFieldDirectiveResolverTrait;
    /**
     * Validations are by default a "Schema" type directive
     */
    public function getDirectiveKind() : string
    {
        return DirectiveKinds::SCHEMA;
    }
    /**
     * Each validate can execute multiple times (eg: several @validateDoesLoggedInUserHaveAnyCapability)
     */
    public function isRepeatable() : bool
    {
        return \true;
    }
    /**
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
        $this->validateAndFilterFields($relationalTypeResolver, $idFieldSet, $fieldDataAccessProvider, $succeedingPipelineIDFieldSet, $resolvedIDFieldValues, $engineIterationFeedbackStore);
    }
    /**
     * @param array<string|int,EngineIterationFieldSet> $idFieldSet
     * @param array<string|int,SplObjectStorage<FieldInterface,mixed>> $resolvedIDFieldValues
     * @param array<array<string|int,EngineIterationFieldSet>> $succeedingPipelineIDFieldSet
     * @param \PoP\ComponentModel\TypeResolvers\RelationalTypeResolverInterface $relationalTypeResolver
     * @param \PoP\ComponentModel\QueryResolution\FieldDataAccessProviderInterface $fieldDataAccessProvider
     * @param \PoP\ComponentModel\Feedback\EngineIterationFeedbackStore $engineIterationFeedbackStore
     */
    protected function validateAndFilterFields($relationalTypeResolver, $idFieldSet, $fieldDataAccessProvider, &$succeedingPipelineIDFieldSet, &$resolvedIDFieldValues, $engineIterationFeedbackStore) : void
    {
        $failedIDFieldSet = $this->validateIDFieldSet($relationalTypeResolver, $idFieldSet, $fieldDataAccessProvider, $succeedingPipelineIDFieldSet, $resolvedIDFieldValues, $engineIterationFeedbackStore);
        // Remove from the data_fields list to execute on the object for the next stages of the pipeline
        if ($failedIDFieldSet !== []) {
            $this->removeIDFieldSet($succeedingPipelineIDFieldSet, $failedIDFieldSet);
            $this->setFieldResponseValueAsNull($resolvedIDFieldValues, $failedIDFieldSet);
        }
    }
    /**
     * @param array<string|int,EngineIterationFieldSet> $idFieldSet
     * @return array<string|int,EngineIterationFieldSet> Failed $idFieldSet
     * @param array<string|int,SplObjectStorage<FieldInterface,mixed>> $resolvedIDFieldValues
     * @param array<array<string|int,EngineIterationFieldSet>> $succeedingPipelineIDFieldSet
     * @param \PoP\ComponentModel\TypeResolvers\RelationalTypeResolverInterface $relationalTypeResolver
     * @param \PoP\ComponentModel\QueryResolution\FieldDataAccessProviderInterface $fieldDataAccessProvider
     * @param \PoP\ComponentModel\Feedback\EngineIterationFeedbackStore $engineIterationFeedbackStore
     */
    protected abstract function validateIDFieldSet($relationalTypeResolver, $idFieldSet, $fieldDataAccessProvider, &$succeedingPipelineIDFieldSet, &$resolvedIDFieldValues, $engineIterationFeedbackStore) : array;
}
