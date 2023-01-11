<?php

declare (strict_types=1);
namespace PoP\ComponentModel\Resolvers;

use PoP\ComponentModel\TypeResolvers\InputTypeResolverInterface;
use PoP\ComponentModel\Feedback\ObjectTypeFieldResolutionFeedbackStore;
use PoP\ComponentModel\FieldResolvers\InterfaceType\InterfaceTypeFieldSchemaDefinitionResolverInterface;
use PoP\ComponentModel\FieldResolvers\ObjectType\ObjectTypeFieldSchemaDefinitionResolverInterface;
use PoP\ComponentModel\TypeResolvers\ConcreteTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface;
use PoP\GraphQLParser\Spec\Parser\Ast\AstInterface;
/**
 * A TypeResolver may be useful when retrieving the schema from a ObjectTypeFieldResolver,
 * but it cannot be used with a InterfaceTypeFieldResolver.
 * Hence, this adapter receives function calls to resolve the schema
 * containing a TypeResolver, strips this param, and then calls
 * the corresponding InterfaceTypeFieldResolver.
 */
class InterfaceSchemaDefinitionResolverAdapter implements ObjectTypeFieldSchemaDefinitionResolverInterface
{
    /**
     * @var \PoP\ComponentModel\FieldResolvers\InterfaceType\InterfaceTypeFieldSchemaDefinitionResolverInterface
     */
    protected $interfaceTypeFieldSchemaDefinitionResolver;
    public function __construct(InterfaceTypeFieldSchemaDefinitionResolverInterface $interfaceTypeFieldSchemaDefinitionResolver)
    {
        $this->interfaceTypeFieldSchemaDefinitionResolver = $interfaceTypeFieldSchemaDefinitionResolver;
    }
    /**
     * This function will never be called for the Adapter,
     * but must be implemented to satisfy the interface
     *
     * @return string[]
     */
    public function getFieldNamesToResolve() : array
    {
        return [];
    }
    /**
     * This function will never be called for the Adapter,
     * but must be implemented to satisfy the interface
     *
     * @return string[]
     */
    public function getSensitiveFieldNames() : array
    {
        return [];
    }
    /**
     * @param \PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface $objectTypeResolver
     * @param string $fieldName
     */
    public function getFieldTypeModifiers($objectTypeResolver, $fieldName) : int
    {
        return $this->interfaceTypeFieldSchemaDefinitionResolver->getFieldTypeModifiers($fieldName);
    }
    /**
     * @param \PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface $objectTypeResolver
     * @param string $fieldName
     */
    public function getFieldDescription($objectTypeResolver, $fieldName) : ?string
    {
        return $this->interfaceTypeFieldSchemaDefinitionResolver->getFieldDescription($fieldName);
    }
    /**
     * @return array<string,InputTypeResolverInterface>
     * @param \PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface $objectTypeResolver
     * @param string $fieldName
     */
    public function getFieldArgNameTypeResolvers($objectTypeResolver, $fieldName) : array
    {
        return $this->interfaceTypeFieldSchemaDefinitionResolver->getFieldArgNameTypeResolvers($fieldName);
    }
    /**
     * @return string[]
     * @param \PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface $objectTypeResolver
     * @param string $fieldName
     */
    public function getSensitiveFieldArgNames($objectTypeResolver, $fieldName) : array
    {
        return $this->interfaceTypeFieldSchemaDefinitionResolver->getSensitiveFieldArgNames($fieldName);
    }
    /**
     * @param \PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface $objectTypeResolver
     * @param string $fieldName
     * @param string $fieldArgName
     */
    public function getFieldArgDescription($objectTypeResolver, $fieldName, $fieldArgName) : ?string
    {
        return $this->interfaceTypeFieldSchemaDefinitionResolver->getFieldArgDescription($fieldName, $fieldArgName);
    }
    /**
     * @return mixed
     * @param \PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface $objectTypeResolver
     * @param string $fieldName
     * @param string $fieldArgName
     */
    public function getFieldArgDefaultValue($objectTypeResolver, $fieldName, $fieldArgName)
    {
        return $this->interfaceTypeFieldSchemaDefinitionResolver->getFieldArgDefaultValue($fieldName, $fieldArgName);
    }
    /**
     * @param \PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface $objectTypeResolver
     * @param string $fieldName
     * @param string $fieldArgName
     */
    public function getFieldArgTypeModifiers($objectTypeResolver, $fieldName, $fieldArgName) : int
    {
        return $this->interfaceTypeFieldSchemaDefinitionResolver->getFieldArgTypeModifiers($fieldName, $fieldArgName);
    }
    /**
     * @return array<string,InputTypeResolverInterface>
     * @param \PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface $objectTypeResolver
     * @param string $fieldName
     */
    public function getConsolidatedFieldArgNameTypeResolvers($objectTypeResolver, $fieldName) : array
    {
        return $this->interfaceTypeFieldSchemaDefinitionResolver->getConsolidatedFieldArgNameTypeResolvers($fieldName);
    }
    /**
     * @return string[]
     * @param \PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface $objectTypeResolver
     * @param string $fieldName
     */
    public function getConsolidatedSensitiveFieldArgNames($objectTypeResolver, $fieldName) : array
    {
        return $this->interfaceTypeFieldSchemaDefinitionResolver->getConsolidatedSensitiveFieldArgNames($fieldName);
    }
    /**
     * @param \PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface $objectTypeResolver
     * @param string $fieldName
     * @param string $fieldArgName
     */
    public function getConsolidatedFieldArgDescription($objectTypeResolver, $fieldName, $fieldArgName) : ?string
    {
        return $this->interfaceTypeFieldSchemaDefinitionResolver->getConsolidatedFieldArgDescription($fieldName, $fieldArgName);
    }
    /**
     * @return mixed
     * @param \PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface $objectTypeResolver
     * @param string $fieldName
     * @param string $fieldArgName
     */
    public function getConsolidatedFieldArgDefaultValue($objectTypeResolver, $fieldName, $fieldArgName)
    {
        return $this->interfaceTypeFieldSchemaDefinitionResolver->getConsolidatedFieldArgDefaultValue($fieldName, $fieldArgName);
    }
    /**
     * @param \PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface $objectTypeResolver
     * @param string $fieldName
     * @param string $fieldArgName
     */
    public function getConsolidatedFieldArgTypeModifiers($objectTypeResolver, $fieldName, $fieldArgName) : int
    {
        return $this->interfaceTypeFieldSchemaDefinitionResolver->getConsolidatedFieldArgTypeModifiers($fieldName, $fieldArgName);
    }
    /**
     * @param \PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface $objectTypeResolver
     * @param string $fieldName
     */
    public function getFieldDeprecationMessage($objectTypeResolver, $fieldName) : ?string
    {
        return $this->interfaceTypeFieldSchemaDefinitionResolver->getFieldDeprecationMessage($fieldName);
    }
    /**
     * @param \PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface $objectTypeResolver
     * @param string $fieldName
     */
    public function getFieldTypeResolver($objectTypeResolver, $fieldName) : ConcreteTypeResolverInterface
    {
        return $this->interfaceTypeFieldSchemaDefinitionResolver->getFieldTypeResolver($fieldName);
    }
    /**
     * Validate the constraints for a field argument
     * @param mixed $fieldArgValue
     * @param \PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface $objectTypeResolver
     * @param string $fieldName
     * @param string $fieldArgName
     * @param \PoP\GraphQLParser\Spec\Parser\Ast\AstInterface $astNode
     * @param \PoP\ComponentModel\Feedback\ObjectTypeFieldResolutionFeedbackStore $objectTypeFieldResolutionFeedbackStore
     */
    public function validateFieldArgValue($objectTypeResolver, $fieldName, $fieldArgName, $fieldArgValue, $astNode, $objectTypeFieldResolutionFeedbackStore) : void
    {
        $this->interfaceTypeFieldSchemaDefinitionResolver->validateFieldArgValue($fieldName, $fieldArgName, $fieldArgValue, $astNode, $objectTypeFieldResolutionFeedbackStore);
    }
}
