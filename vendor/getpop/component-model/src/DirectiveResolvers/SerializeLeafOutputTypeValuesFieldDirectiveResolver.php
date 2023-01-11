<?php

declare (strict_types=1);
namespace PoP\ComponentModel\DirectiveResolvers;

use PoP\ComponentModel\QueryResolution\FieldDataAccessProviderInterface;
use PoP\ComponentModel\Directives\DirectiveKinds;
use PoP\ComponentModel\Engine\EngineIterationFieldSet;
use PoP\ComponentModel\Feedback\EngineIterationFeedbackStore;
use PoP\ComponentModel\TypeResolvers\PipelinePositions;
use PoP\ComponentModel\TypeResolvers\RelationalTypeResolverInterface;
use PoP\ComponentModel\TypeSerialization\TypeSerializationServiceInterface;
use PoP\GraphQLParser\Spec\Parser\Ast\FieldInterface;
use SplObjectStorage;
final class SerializeLeafOutputTypeValuesFieldDirectiveResolver extends \PoP\ComponentModel\DirectiveResolvers\AbstractGlobalFieldDirectiveResolver
{
    /**
     * @var \PoP\ComponentModel\TypeSerialization\TypeSerializationServiceInterface|null
     */
    private $typeSerializationService;
    /**
     * @param \PoP\ComponentModel\TypeSerialization\TypeSerializationServiceInterface $typeSerializationService
     */
    public final function setTypeSerializationService($typeSerializationService) : void
    {
        $this->typeSerializationService = $typeSerializationService;
    }
    protected final function getTypeSerializationService() : TypeSerializationServiceInterface
    {
        /** @var TypeSerializationServiceInterface */
        return $this->typeSerializationService = $this->typeSerializationService ?? $this->instanceManager->getInstance(TypeSerializationServiceInterface::class);
    }
    public function getDirectiveName() : string
    {
        return 'serializeLeafOutputTypeValues';
    }
    /**
     * This is a system directive
     */
    public function getDirectiveKind() : string
    {
        return DirectiveKinds::SYSTEM;
    }
    /**
     * Execute it at the very end, after everything else,
     * to allow other directives to deal with the unserialized value
     */
    public function getPipelinePosition() : string
    {
        return PipelinePositions::AFTER_SERIALIZE;
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
        $serializedIDFieldValues = $this->getTypeSerializationService()->serializeOutputTypeIDFieldValues($relationalTypeResolver, $resolvedIDFieldValues, $idFieldSet, $idObjects, $this->directive, $engineIterationFeedbackStore);
        foreach ($serializedIDFieldValues as $id => $serializedFieldValues) {
            /** @var FieldInterface $field */
            foreach ($serializedFieldValues as $field) {
                $serializedValue = $serializedFieldValues[$field];
                $resolvedIDFieldValues[$id][$field] = $serializedValue;
            }
        }
    }
    /**
     * @param \PoP\ComponentModel\TypeResolvers\RelationalTypeResolverInterface $relationalTypeResolver
     */
    public function getDirectiveDescription($relationalTypeResolver) : ?string
    {
        return $this->__('Serialize the results for fields of Scalar and Enum Types. This directive is already included by the engine, since its execution is mandatory', 'component-model');
    }
}
