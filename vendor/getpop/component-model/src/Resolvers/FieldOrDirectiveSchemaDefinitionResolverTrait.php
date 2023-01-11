<?php

declare (strict_types=1);
namespace PoP\ComponentModel\Resolvers;

use PoP\ComponentModel\TypeResolvers\ConcreteTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\InputTypeResolverInterface;
trait FieldOrDirectiveSchemaDefinitionResolverTrait
{
    use \PoP\ComponentModel\Resolvers\TypeSchemaDefinitionResolverTrait;
    /**
     * @return array<string,mixed>
     * @param mixed $argDefaultValue
     * @param string $argName
     * @param \PoP\ComponentModel\TypeResolvers\InputTypeResolverInterface $argInputTypeResolver
     * @param string|null $argDescription
     * @param int $argTypeModifiers
     */
    public final function getFieldOrDirectiveArgTypeSchemaDefinition($argName, $argInputTypeResolver, $argDescription, $argDefaultValue, $argTypeModifiers) : array
    {
        return $this->getTypeSchemaDefinition($argName, $argInputTypeResolver, $argDescription, $argDefaultValue, $argTypeModifiers);
    }
    /**
     * @return array<string,mixed>
     * @param string $fieldName
     * @param \PoP\ComponentModel\TypeResolvers\ConcreteTypeResolverInterface $fieldTypeResolver
     * @param string|null $fieldDescription
     * @param int $fieldTypeModifiers
     * @param string|null $fieldDeprecationMessage
     */
    public final function getFieldTypeSchemaDefinition($fieldName, $fieldTypeResolver, $fieldDescription, $fieldTypeModifiers, $fieldDeprecationMessage) : array
    {
        return $this->getTypeSchemaDefinition($fieldName, $fieldTypeResolver, $fieldDescription, null, $fieldTypeModifiers, $fieldDeprecationMessage);
    }
}
