<?php

declare (strict_types=1);
namespace PoP\ComponentModel\FieldResolvers\ObjectType;

use PoP\ComponentModel\Feedback\ObjectTypeFieldResolutionFeedbackStore;
use PoP\ComponentModel\QueryResolution\FieldDataAccessorInterface;
use PoP\ComponentModel\TypeResolvers\ConcreteTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\InputTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface;
use PoP\GraphQLParser\Spec\Parser\Ast\AstInterface;
use PoP\GraphQLParser\Spec\Parser\Ast\FieldInterface;
use PoP\GraphQLParser\Spec\Parser\Ast\LeafField;
use PoP\GraphQLParser\Spec\Parser\Ast\RelationalField;
use PoP\GraphQLParser\Spec\Parser\RuntimeLocation;
use SplObjectStorage;
/**
 * Create an alias of a fieldName (or fieldNames), to use when:
 *
 * - the same fieldName is registered more than once (eg: by different plugins)
 * - want to rename the field (steps: alias the field, then remove access to the original)
 *
 * This trait, to be applied on a ObjectTypeFieldResolver class, uses the Proxy design pattern:
 * every function executed on the aliasing field executes the same function on the aliased field.
 *
 * The aliased ObjectTypeFieldResolver must indicate which specific `ObjectTypeFieldResolver` class
 * it is aliasing, because if the field is duplicated, then just using
 * the $fieldName to obtain the ObjectTypeFieldResolver is ambiguous.
 *
 * @author Leonardo Losoviz <leo@getpop.org>
 */
trait AliasSchemaObjectTypeFieldResolverTrait
{
    /** @var SplObjectStorage<FieldInterface,FieldInterface>|null */
    protected $aliasedFieldCache;
    /** @var SplObjectStorage<FieldDataAccessorInterface,FieldDataAccessorInterface>|null */
    protected $aliasedFieldDataAccessorCache;
    /**
     * The fieldName that is being aliased
     * @param string $fieldName
     */
    protected abstract function getAliasedFieldName($fieldName) : string;
    /**
     * The specific `ObjectTypeFieldResolver` class that is being aliased
     */
    protected abstract function getAliasedObjectTypeFieldResolver() : \PoP\ComponentModel\FieldResolvers\ObjectType\AbstractObjectTypeFieldResolver;
    /**
     * Proxy pattern: execute same function on the aliased ObjectTypeFieldResolver,
     * for the aliased $fieldName
     * @param \PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface $objectTypeResolver
     * @param string $fieldName
     */
    public function isGlobal($objectTypeResolver, $fieldName) : bool
    {
        $aliasedObjectTypeFieldResolver = $this->getAliasedObjectTypeFieldResolver();
        return $aliasedObjectTypeFieldResolver->isGlobal($objectTypeResolver, $this->getAliasedFieldName($fieldName));
    }
    /**
     * Proxy pattern: execute same function on the aliased ObjectTypeFieldResolver,
     * for the aliased $fieldName
     * @param \PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface $objectTypeResolver
     * @param \PoP\GraphQLParser\Spec\Parser\Ast\FieldInterface $field
     */
    public function resolveCanProcessField($objectTypeResolver, $field) : bool
    {
        $aliasedObjectTypeFieldResolver = $this->getAliasedObjectTypeFieldResolver();
        return $aliasedObjectTypeFieldResolver->resolveCanProcessField($objectTypeResolver, $this->getAliasedField($field));
    }
    /**
     * @param \PoP\GraphQLParser\Spec\Parser\Ast\FieldInterface $field
     */
    protected function getAliasedField($field) : FieldInterface
    {
        /** @var SplObjectStorage<FieldInterface,FieldInterface> */
        $this->aliasedFieldCache = $this->aliasedFieldCache ?? new SplObjectStorage();
        if (!$this->aliasedFieldCache->contains($field)) {
            $this->aliasedFieldCache[$field] = $field instanceof RelationalField ? new RelationalField($field->getName(), $this->getAliasedFieldName($field->getName()), $field->getArguments(), $field->getFieldsOrFragmentBonds(), $field->getDirectives(), new RuntimeLocation($field)) : new LeafField($field->getName(), $this->getAliasedFieldName($field->getName()), $field->getArguments(), $field->getDirectives(), new RuntimeLocation($field));
        }
        return $this->aliasedFieldCache[$field];
    }
    /**
     * @param \PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface $objectTypeResolver
     * @param \PoP\ComponentModel\QueryResolution\FieldDataAccessorInterface $fieldDataAccessor
     */
    protected function getAliasedFieldDataAccessor($objectTypeResolver, $fieldDataAccessor) : FieldDataAccessorInterface
    {
        /** @var SplObjectStorage<FieldInterface,FieldInterface> */
        $this->aliasedFieldDataAccessorCache = $this->aliasedFieldDataAccessorCache ?? new SplObjectStorage();
        if (!$this->aliasedFieldDataAccessorCache->contains($fieldDataAccessor)) {
            $this->aliasedFieldDataAccessorCache[$fieldDataAccessor] = $objectTypeResolver->createFieldDataAccessor($this->getAliasedField($fieldDataAccessor->getField()), $fieldDataAccessor->getFieldArgs());
        }
        return $this->aliasedFieldDataAccessorCache[$fieldDataAccessor];
    }
    /**
     * Proxy pattern: execute same function on the aliased ObjectTypeFieldResolver,
     * for the aliased $fieldName
     * @param \PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface $objectTypeResolver
     * @param \PoP\GraphQLParser\Spec\Parser\Ast\FieldInterface $field
     * @param \PoP\ComponentModel\Feedback\ObjectTypeFieldResolutionFeedbackStore $objectTypeFieldResolutionFeedbackStore
     */
    public function collectFieldValidationDeprecationMessages($objectTypeResolver, $field, $objectTypeFieldResolutionFeedbackStore) : void
    {
        $aliasedObjectTypeFieldResolver = $this->getAliasedObjectTypeFieldResolver();
        $aliasedObjectTypeFieldResolver->collectFieldValidationDeprecationMessages($objectTypeResolver, $this->getAliasedField($field), $objectTypeFieldResolutionFeedbackStore);
    }
    /**
     * Proxy pattern: execute same function on the aliased ObjectTypeFieldResolver,
     * for the aliased $fieldName
     * @param object $object
     * @param \PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface $objectTypeResolver
     * @param \PoP\ComponentModel\QueryResolution\FieldDataAccessorInterface $fieldDataAccessor
     * @param \PoP\ComponentModel\Feedback\ObjectTypeFieldResolutionFeedbackStore $objectTypeFieldResolutionFeedbackStore
     */
    public function validateFieldArgsForObject($objectTypeResolver, $object, $fieldDataAccessor, $objectTypeFieldResolutionFeedbackStore) : void
    {
        $aliasedObjectTypeFieldResolver = $this->getAliasedObjectTypeFieldResolver();
        $aliasedObjectTypeFieldResolver->validateFieldArgsForObject($objectTypeResolver, $object, $this->getAliasedFieldDataAccessor($objectTypeResolver, $fieldDataAccessor), $objectTypeFieldResolutionFeedbackStore);
    }
    /**
     * Proxy pattern: execute same function on the aliased ObjectTypeFieldResolver,
     * for the aliased $fieldName
     * @param \PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface $objectTypeResolver
     * @param string $fieldName
     */
    public function skipExposingFieldInSchema($objectTypeResolver, $fieldName) : bool
    {
        $aliasedObjectTypeFieldResolver = $this->getAliasedObjectTypeFieldResolver();
        return $aliasedObjectTypeFieldResolver->skipExposingFieldInSchema($objectTypeResolver, $this->getAliasedFieldName($fieldName));
    }
    /**
     * Proxy pattern: execute same function on the aliased ObjectTypeFieldResolver,
     * for the aliased $fieldName
     * @param \PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface $objectTypeResolver
     * @param string $fieldName
     */
    public function getFieldTypeModifiers($objectTypeResolver, $fieldName) : int
    {
        $aliasedObjectTypeFieldResolver = $this->getAliasedObjectTypeFieldResolver();
        return $aliasedObjectTypeFieldResolver->getFieldTypeModifiers($objectTypeResolver, $this->getAliasedFieldName($fieldName));
    }
    /**
     * Proxy pattern: execute same function on the aliased ObjectTypeFieldResolver,
     * for the aliased $fieldName
     *
     * @return array<string,InputTypeResolverInterface>
     * @param \PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface $objectTypeResolver
     * @param string $fieldName
     */
    public function getFieldArgNameTypeResolvers($objectTypeResolver, $fieldName) : array
    {
        $aliasedObjectTypeFieldResolver = $this->getAliasedObjectTypeFieldResolver();
        return $aliasedObjectTypeFieldResolver->getFieldArgNameTypeResolvers($objectTypeResolver, $this->getAliasedFieldName($fieldName));
    }
    /**
     * Proxy pattern: execute same function on the aliased ObjectTypeFieldResolver,
     * for the aliased $fieldName
     *
     * @return string[]
     * @param \PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface $objectTypeResolver
     * @param string $fieldName
     */
    public function getSensitiveFieldArgNames($objectTypeResolver, $fieldName) : array
    {
        $aliasedObjectTypeFieldResolver = $this->getAliasedObjectTypeFieldResolver();
        return $aliasedObjectTypeFieldResolver->getSensitiveFieldArgNames($objectTypeResolver, $this->getAliasedFieldName($fieldName));
    }
    /**
     * Proxy pattern: execute same function on the aliased ObjectTypeFieldResolver,
     * for the aliased $fieldName
     * @param \PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface $objectTypeResolver
     * @param string $fieldName
     * @param string $fieldArgName
     */
    public function getFieldArgDescription($objectTypeResolver, $fieldName, $fieldArgName) : ?string
    {
        $aliasedObjectTypeFieldResolver = $this->getAliasedObjectTypeFieldResolver();
        return $aliasedObjectTypeFieldResolver->getFieldArgDescription($objectTypeResolver, $this->getAliasedFieldName($fieldName), $fieldArgName);
    }
    /**
     * Proxy pattern: execute same function on the aliased ObjectTypeFieldResolver,
     * for the aliased $fieldName
     * @return mixed
     * @param \PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface $objectTypeResolver
     * @param string $fieldName
     * @param string $fieldArgName
     */
    public function getFieldArgDefaultValue($objectTypeResolver, $fieldName, $fieldArgName)
    {
        $aliasedObjectTypeFieldResolver = $this->getAliasedObjectTypeFieldResolver();
        return $aliasedObjectTypeFieldResolver->getFieldArgDefaultValue($objectTypeResolver, $this->getAliasedFieldName($fieldName), $fieldArgName);
    }
    /**
     * Proxy pattern: execute same function on the aliased ObjectTypeFieldResolver,
     * for the aliased $fieldName
     * @param \PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface $objectTypeResolver
     * @param string $fieldName
     * @param string $fieldArgName
     */
    public function getFieldArgTypeModifiers($objectTypeResolver, $fieldName, $fieldArgName) : int
    {
        $aliasedObjectTypeFieldResolver = $this->getAliasedObjectTypeFieldResolver();
        return $aliasedObjectTypeFieldResolver->getFieldArgTypeModifiers($objectTypeResolver, $this->getAliasedFieldName($fieldName), $fieldArgName);
    }
    /**
     * Proxy pattern: execute same function on the aliased ObjectTypeFieldResolver,
     * for the aliased $fieldName
     *
     * @return array<string,InputTypeResolverInterface>
     * @param \PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface $objectTypeResolver
     * @param string $fieldName
     */
    public function getConsolidatedFieldArgNameTypeResolvers($objectTypeResolver, $fieldName) : array
    {
        $aliasedObjectTypeFieldResolver = $this->getAliasedObjectTypeFieldResolver();
        return $aliasedObjectTypeFieldResolver->getConsolidatedFieldArgNameTypeResolvers($objectTypeResolver, $this->getAliasedFieldName($fieldName));
    }
    /**
     * Proxy pattern: execute same function on the aliased ObjectTypeFieldResolver,
     * for the aliased $fieldName
     *
     * @return string[]
     * @param \PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface $objectTypeResolver
     * @param string $fieldName
     */
    public function getConsolidatedSensitiveFieldArgNames($objectTypeResolver, $fieldName) : array
    {
        $aliasedObjectTypeFieldResolver = $this->getAliasedObjectTypeFieldResolver();
        return $aliasedObjectTypeFieldResolver->getConsolidatedSensitiveFieldArgNames($objectTypeResolver, $this->getAliasedFieldName($fieldName));
    }
    /**
     * Proxy pattern: execute same function on the aliased ObjectTypeFieldResolver,
     * for the aliased $fieldName
     * @param \PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface $objectTypeResolver
     * @param string $fieldName
     * @param string $fieldArgName
     */
    public function getConsolidatedFieldArgDescription($objectTypeResolver, $fieldName, $fieldArgName) : ?string
    {
        $aliasedObjectTypeFieldResolver = $this->getAliasedObjectTypeFieldResolver();
        return $aliasedObjectTypeFieldResolver->getConsolidatedFieldArgDescription($objectTypeResolver, $this->getAliasedFieldName($fieldName), $fieldArgName);
    }
    /**
     * Proxy pattern: execute same function on the aliased ObjectTypeFieldResolver,
     * for the aliased $fieldName
     * @return mixed
     * @param \PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface $objectTypeResolver
     * @param string $fieldName
     * @param string $fieldArgName
     */
    public function getConsolidatedFieldArgDefaultValue($objectTypeResolver, $fieldName, $fieldArgName)
    {
        $aliasedObjectTypeFieldResolver = $this->getAliasedObjectTypeFieldResolver();
        return $aliasedObjectTypeFieldResolver->getConsolidatedFieldArgDefaultValue($objectTypeResolver, $this->getAliasedFieldName($fieldName), $fieldArgName);
    }
    /**
     * Proxy pattern: execute same function on the aliased ObjectTypeFieldResolver,
     * for the aliased $fieldName
     * @param \PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface $objectTypeResolver
     * @param string $fieldName
     * @param string $fieldArgName
     */
    public function getConsolidatedFieldArgTypeModifiers($objectTypeResolver, $fieldName, $fieldArgName) : int
    {
        $aliasedObjectTypeFieldResolver = $this->getAliasedObjectTypeFieldResolver();
        return $aliasedObjectTypeFieldResolver->getConsolidatedFieldArgTypeModifiers($objectTypeResolver, $this->getAliasedFieldName($fieldName), $fieldArgName);
    }
    /**
     * Proxy pattern: execute same function on the aliased ObjectTypeFieldResolver,
     * for the aliased $fieldName
     * @param \PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface $objectTypeResolver
     * @param string $fieldName
     */
    public function getFieldDescription($objectTypeResolver, $fieldName) : ?string
    {
        $aliasedObjectTypeFieldResolver = $this->getAliasedObjectTypeFieldResolver();
        return $aliasedObjectTypeFieldResolver->getFieldDescription($objectTypeResolver, $this->getAliasedFieldName($fieldName));
    }
    /**
     * Proxy pattern: execute same function on the aliased ObjectTypeFieldResolver,
     * for the aliased $fieldName
     * @param \PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface $objectTypeResolver
     * @param string $fieldName
     */
    public function getFieldDeprecationMessage($objectTypeResolver, $fieldName) : ?string
    {
        $aliasedObjectTypeFieldResolver = $this->getAliasedObjectTypeFieldResolver();
        return $aliasedObjectTypeFieldResolver->getFieldDeprecationMessage($objectTypeResolver, $this->getAliasedFieldName($fieldName));
    }
    /**
     * Proxy pattern: execute same function on the aliased ObjectTypeFieldResolver,
     * for the aliased $fieldName
     * @param \PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface $objectTypeResolver
     * @param string $fieldName
     */
    public function getConsolidatedFieldDescription($objectTypeResolver, $fieldName) : ?string
    {
        $aliasedObjectTypeFieldResolver = $this->getAliasedObjectTypeFieldResolver();
        return $aliasedObjectTypeFieldResolver->getConsolidatedFieldDescription($objectTypeResolver, $this->getAliasedFieldName($fieldName));
    }
    /**
     * Proxy pattern: execute same function on the aliased ObjectTypeFieldResolver,
     * for the aliased $fieldName
     * @param \PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface $objectTypeResolver
     * @param string $fieldName
     */
    public function getConsolidatedFieldDeprecationMessage($objectTypeResolver, $fieldName) : ?string
    {
        $aliasedObjectTypeFieldResolver = $this->getAliasedObjectTypeFieldResolver();
        return $aliasedObjectTypeFieldResolver->getConsolidatedFieldDeprecationMessage($objectTypeResolver, $this->getAliasedFieldName($fieldName));
    }
    /**
     * Proxy pattern: execute same function on the aliased ObjectTypeFieldResolver,
     * for the aliased $fieldName
     * @param mixed $fieldArgValue
     * @param \PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface $objectTypeResolver
     * @param string $fieldName
     * @param string $fieldArgName
     * @param \PoP\GraphQLParser\Spec\Parser\Ast\AstInterface $astNode
     * @param \PoP\ComponentModel\Feedback\ObjectTypeFieldResolutionFeedbackStore $objectTypeFieldResolutionFeedbackStore
     */
    public function validateFieldArgValue($objectTypeResolver, $fieldName, $fieldArgName, $fieldArgValue, $astNode, $objectTypeFieldResolutionFeedbackStore) : void
    {
        $aliasedObjectTypeFieldResolver = $this->getAliasedObjectTypeFieldResolver();
        $aliasedObjectTypeFieldResolver->validateFieldArgValue($objectTypeResolver, $this->getAliasedFieldName($fieldName), $fieldArgName, $fieldArgValue, $astNode, $objectTypeFieldResolutionFeedbackStore);
    }
    /**
     * Proxy pattern: execute same function on the aliased ObjectTypeFieldResolver,
     * for the aliased $fieldName
     * @param \PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface $objectTypeResolver
     * @param string $fieldName
     */
    public function enableOrderedSchemaFieldArgs($objectTypeResolver, $fieldName) : bool
    {
        $aliasedObjectTypeFieldResolver = $this->getAliasedObjectTypeFieldResolver();
        return $aliasedObjectTypeFieldResolver->enableOrderedSchemaFieldArgs($objectTypeResolver, $this->getAliasedFieldName($fieldName));
    }
    /**
     * Proxy pattern: execute same function on the aliased ObjectTypeFieldResolver,
     * for the aliased $fieldName
     * @param \PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface $objectTypeResolver
     * @param string $fieldName
     */
    public function getFieldVersion($objectTypeResolver, $fieldName) : ?string
    {
        $aliasedObjectTypeFieldResolver = $this->getAliasedObjectTypeFieldResolver();
        return $aliasedObjectTypeFieldResolver->getFieldVersion($objectTypeResolver, $this->getAliasedFieldName($fieldName));
    }
    /**
     * Proxy pattern: execute same function on the aliased ObjectTypeFieldResolver,
     * for the aliased $fieldName
     * @return mixed
     * @param object $object
     * @param \PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface $objectTypeResolver
     * @param \PoP\ComponentModel\QueryResolution\FieldDataAccessorInterface $fieldDataAccessor
     * @param \PoP\ComponentModel\Feedback\ObjectTypeFieldResolutionFeedbackStore $objectTypeFieldResolutionFeedbackStore
     */
    public function resolveValue($objectTypeResolver, $object, $fieldDataAccessor, $objectTypeFieldResolutionFeedbackStore)
    {
        $aliasedObjectTypeFieldResolver = $this->getAliasedObjectTypeFieldResolver();
        return $aliasedObjectTypeFieldResolver->resolveValue($objectTypeResolver, $object, $this->getAliasedFieldDataAccessor($objectTypeResolver, $fieldDataAccessor), $objectTypeFieldResolutionFeedbackStore);
    }
    /**
     * Proxy pattern: execute same function on the aliased ObjectTypeFieldResolver,
     * for the aliased $fieldName
     * @param \PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface $objectTypeResolver
     * @param string $fieldName
     */
    public function getFieldTypeResolver($objectTypeResolver, $fieldName) : ConcreteTypeResolverInterface
    {
        $aliasedObjectTypeFieldResolver = $this->getAliasedObjectTypeFieldResolver();
        return $aliasedObjectTypeFieldResolver->getFieldTypeResolver($objectTypeResolver, $this->getAliasedFieldName($fieldName));
    }
}
