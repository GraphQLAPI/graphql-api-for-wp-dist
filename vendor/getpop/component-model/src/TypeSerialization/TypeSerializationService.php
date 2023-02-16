<?php

declare (strict_types=1);
namespace PoP\ComponentModel\TypeSerialization;

use PoP\ComponentModel\App;
use PoP\ComponentModel\Engine\EngineIterationFieldSet;
use PoP\ComponentModel\Feedback\EngineIterationFeedbackStore;
use PoP\ComponentModel\Schema\SchemaTypeModifiers;
use PoP\ComponentModel\TypeResolvers\LeafOutputTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\RelationalTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\ScalarType\DangerouslyNonSpecificScalarTypeScalarTypeResolver;
use PoP\ComponentModel\TypeResolvers\UnionType\UnionTypeResolverInterface;
use PoP\GraphQLParser\Spec\Parser\Ast\Directive;
use PoP\GraphQLParser\Spec\Parser\Ast\FieldInterface;
use PoP\Root\Services\BasicServiceTrait;
use SplObjectStorage;
use stdClass;
class TypeSerializationService implements \PoP\ComponentModel\TypeSerialization\TypeSerializationServiceInterface
{
    use BasicServiceTrait;
    /**
     * @var \PoP\ComponentModel\TypeResolvers\ScalarType\DangerouslyNonSpecificScalarTypeScalarTypeResolver|null
     */
    private $dangerouslyNonSpecificScalarTypeScalarTypeResolver;
    /**
     * @param \PoP\ComponentModel\TypeResolvers\ScalarType\DangerouslyNonSpecificScalarTypeScalarTypeResolver $dangerouslyNonSpecificScalarTypeScalarTypeResolver
     */
    public final function setDangerouslyNonSpecificScalarTypeScalarTypeResolver($dangerouslyNonSpecificScalarTypeScalarTypeResolver) : void
    {
        $this->dangerouslyNonSpecificScalarTypeScalarTypeResolver = $dangerouslyNonSpecificScalarTypeScalarTypeResolver;
    }
    protected final function getDangerouslyNonSpecificScalarTypeScalarTypeResolver() : DangerouslyNonSpecificScalarTypeScalarTypeResolver
    {
        /** @var DangerouslyNonSpecificScalarTypeScalarTypeResolver */
        return $this->dangerouslyNonSpecificScalarTypeScalarTypeResolver = $this->dangerouslyNonSpecificScalarTypeScalarTypeResolver ?? $this->instanceManager->getInstance(DangerouslyNonSpecificScalarTypeScalarTypeResolver::class);
    }
    /**
     * @param array<string|int,SplObjectStorage<FieldInterface,mixed>> $idFieldValues
     * @param array<string|int,EngineIterationFieldSet> $idFieldSet
     * @return array<string|int,SplObjectStorage<FieldInterface,mixed>>
     * @param array<string|int,object> $idObjects
     * @param \PoP\ComponentModel\TypeResolvers\RelationalTypeResolverInterface $relationalTypeResolver
     * @param \PoP\GraphQLParser\Spec\Parser\Ast\Directive $directive
     * @param \PoP\ComponentModel\Feedback\EngineIterationFeedbackStore $engineIterationFeedbackStore
     */
    public function serializeOutputTypeIDFieldValues($relationalTypeResolver, $idFieldValues, $idFieldSet, $idObjects, $directive, $engineIterationFeedbackStore) : array
    {
        if (!$idObjects) {
            return [];
        }
        /** @var array<string|int,SplObjectStorage<FieldInterface,mixed>> */
        $serializedIDFieldValues = [];
        $unionTypeResolver = null;
        $targetObjectTypeResolver = null;
        $isUnionTypeResolver = $relationalTypeResolver instanceof UnionTypeResolverInterface;
        if ($isUnionTypeResolver) {
            /** @var UnionTypeResolverInterface */
            $unionTypeResolver = $relationalTypeResolver;
        } else {
            /** @var ObjectTypeResolverInterface */
            $targetObjectTypeResolver = $relationalTypeResolver;
        }
        foreach ($idFieldSet as $id => $fieldSet) {
            // Obtain its ID and the required data-fields for that ID
            $object = $idObjects[$id];
            if ($isUnionTypeResolver) {
                /** @var UnionTypeResolverInterface $unionTypeResolver */
                $targetObjectTypeResolver = $unionTypeResolver->getTargetObjectTypeResolver($object);
            }
            /** @var SplObjectStorage<FieldInterface,mixed> */
            $fieldValues = $serializedIDFieldValues[$id] ?? new SplObjectStorage();
            foreach ($fieldSet->fields as $field) {
                $value = $idFieldValues[$id][$field] ?? null;
                if ($value === null) {
                    continue;
                }
                /** @var ObjectTypeResolverInterface $targetObjectTypeResolver */
                $fieldTypeResolver = $targetObjectTypeResolver->getFieldTypeResolver($field);
                if ($fieldTypeResolver === null) {
                    continue;
                }
                /**
                 * If it is not a leaf node, then it is a relational node.
                 * Then the values are the IDs of the elements to load.
                 *
                 * Retrieve them directly, as they don't need
                 * to be serialized, and they can be still useful.
                 *
                 * Eg, the Resolved Field Value Reference in this query:
                 *
                 *   ```
                 *   {
                 *       self {
                 *           id
                 *       }
                 *       nonLeaf: _echo(value: $__self)
                 *   }
                 *   ```
                 *
                 * ...must still retrieve the value for `self`, which is
                 * a relational node.
                 */
                if (!$fieldTypeResolver instanceof LeafOutputTypeResolverInterface) {
                    $fieldValues[$field] = $value;
                    continue;
                }
                /** @var LeafOutputTypeResolverInterface */
                $fieldLeafOutputTypeResolver = $fieldTypeResolver;
                // Serialize the scalar/enum value stored in $idFieldValues
                $fieldValues[$field] = $this->serializeLeafOutputTypeValue($value, $fieldLeafOutputTypeResolver, $targetObjectTypeResolver, $field);
            }
            $serializedIDFieldValues[$id] = $fieldValues;
        }
        return $serializedIDFieldValues;
    }
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
    public function serializeLeafOutputTypeValue($value, $fieldLeafOutputTypeResolver, $objectTypeResolver, $field)
    {
        /**
         * `DangerouslyNonSpecificScalar` is a special scalar type which is not coerced or validated.
         * In particular, it does not need to validate if it is an array or not,
         * as according to the applied WrappingType.
         */
        if ($fieldLeafOutputTypeResolver === $this->getDangerouslyNonSpecificScalarTypeScalarTypeResolver()) {
            /**
             * Array is not supported by `serialize`, but can still be handled
             * by DangerouslyNonSpecificScalar. So convert it into stdClass
             */
            $isArray = \is_array($value);
            if ($isArray) {
                $value = (object) $value;
            }
            $serializedValue = $fieldLeafOutputTypeResolver->serialize($value);
            if ($isArray) {
                /** @var stdClass $serializedValue */
                return (array) $serializedValue;
            }
            return $serializedValue;
        }
        $fieldTypeModifiers = $this->getFieldTypeModifiersFromAppStateOrField($objectTypeResolver, $field);
        /**
         * If the value is an array of arrays, then serialize each subelement to the item type.
         * To make sure the array is not associative (on which case it should be treated
         * as a JSONObject instead of an array), also apply `array_values`
         */
        $fieldLeafOutputTypeIsArrayOfArrays = ($fieldTypeModifiers & SchemaTypeModifiers::IS_ARRAY_OF_ARRAYS) === SchemaTypeModifiers::IS_ARRAY_OF_ARRAYS;
        if ($fieldLeafOutputTypeIsArrayOfArrays) {
            return \array_values(\array_map(
                // If it contains a null value, return it as is
                function (?array $arrayValueElem) use($fieldLeafOutputTypeResolver) {
                    return $arrayValueElem === null ? null : \array_values(\array_map(function ($arrayOfArraysValueElem) use($fieldLeafOutputTypeResolver) {
                        return $arrayOfArraysValueElem === null ? null : $fieldLeafOutputTypeResolver->serialize($arrayOfArraysValueElem);
                    }, $arrayValueElem));
                },
                $value
            ));
        }
        // If the value is an array, then serialize each element to the item type
        $fieldLeafOutputTypeIsArray = ($fieldTypeModifiers & SchemaTypeModifiers::IS_ARRAY) === SchemaTypeModifiers::IS_ARRAY;
        if ($fieldLeafOutputTypeIsArray) {
            return \array_values(\array_map(function ($arrayValueElem) use($fieldLeafOutputTypeResolver) {
                return $arrayValueElem === null ? null : $fieldLeafOutputTypeResolver->serialize($arrayValueElem);
            }, $value));
        }
        // Otherwise, simply serialize the given value directly
        return $fieldLeafOutputTypeResolver->serialize($value);
    }
    /**
     * The modifiers for "IsArrayOfArrays" and "IsArray"
     * can be provided via the AppState, because @forEach
     * will decrease on 1 level the cardinality of the value,
     * not corresponding anymore with that one from the type
     * in the field.
     *
     * For instance, in the following query, the cardinality
     * of `roleNames` is `[String]`, but that one received
     * by field `_titleCase` is `String`:
     *
     *   ```
     *   {
     *     users {
     *       roleNames
     *         @forEach(passOnwardsAs: "value")
     *           @applyField(
     *             name: "_titleCase"
     *             arguments: {
     *               text: $value
     *             },
     *             setResultInResponse: true
     *           )
     *     }
     *   }
     *   ```
     * @param \PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface $objectTypeResolver
     * @param \PoP\GraphQLParser\Spec\Parser\Ast\FieldInterface $field
     */
    protected function getFieldTypeModifiersFromAppStateOrField($objectTypeResolver, $field) : int
    {
        /** @var SplObjectStorage<FieldInterface,int|null> */
        $fieldTypeModifiersByField = App::getState('field-type-modifiers-for-serialization');
        /** @var int|null */
        $currentFieldTypeModifiers = $fieldTypeModifiersByField[$field] ?? null;
        if ($currentFieldTypeModifiers !== null) {
            return $currentFieldTypeModifiers;
        }
        /** @var int */
        return $objectTypeResolver->getFieldTypeModifiers($field);
    }
}
