<?php

declare (strict_types=1);
namespace PoP\ComponentModel\TypeSerialization;

use PoP\ComponentModel\Engine\EngineIterationFieldSet;
use PoP\ComponentModel\Feedback\EngineIterationFeedbackStore;
use PoP\ComponentModel\TypeResolvers\LeafOutputTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\RelationalTypeResolverInterface;
use PoP\GraphQLParser\Spec\Parser\Ast\Directive;
use PoP\GraphQLParser\Spec\Parser\Ast\FieldInterface;
use SplObjectStorage;
use stdClass;
interface TypeSerializationServiceInterface
{
    /**
     * @param array<string|int,SplObjectStorage<FieldInterface,mixed>> $idFieldValues
     * @param array<string|int,EngineIterationFieldSet> $idFieldSet
     * @return array<string|int,SplObjectStorage<FieldInterface,mixed>>
     * @param array<string|int,object> $idObjects
     * @param \PoP\ComponentModel\TypeResolvers\RelationalTypeResolverInterface $relationalTypeResolver
     * @param \PoP\GraphQLParser\Spec\Parser\Ast\Directive $directive
     * @param \PoP\ComponentModel\Feedback\EngineIterationFeedbackStore $engineIterationFeedbackStore
     */
    public function serializeOutputTypeIDFieldValues($relationalTypeResolver, $idFieldValues, $idFieldSet, $idObjects, $directive, $engineIterationFeedbackStore) : array;
    /**
     * The response for Scalar Types and Enum types must be serialized.
     * The response type is the same as in the type's `serialize` method.
     *
     * @return string|int|float|bool|mixed[]|stdClass
     * @param mixed $value
     * @param \PoP\ComponentModel\TypeResolvers\LeafOutputTypeResolverInterface $fieldLeafOutputTypeResolver
     * @param \PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface $objectTypeResolver
     * @param \PoP\GraphQLParser\Spec\Parser\Ast\FieldInterface $field
     */
    public function serializeLeafOutputTypeValue($value, $fieldLeafOutputTypeResolver, $objectTypeResolver, $field);
}
