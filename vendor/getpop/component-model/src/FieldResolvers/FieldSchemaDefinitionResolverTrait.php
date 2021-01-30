<?php

declare (strict_types=1);
namespace PoP\ComponentModel\FieldResolvers;

use PoP\ComponentModel\TypeResolvers\TypeResolverInterface;
use PoP\ComponentModel\FieldResolvers\FieldSchemaDefinitionResolverInterface;
use PoP\ComponentModel\Resolvers\WithVersionConstraintFieldOrDirectiveResolverTrait;
trait FieldSchemaDefinitionResolverTrait
{
    use WithVersionConstraintFieldOrDirectiveResolverTrait;
    /**
     * Return the object implementing the schema definition for this fieldResolver
     *
     * @return void
     */
    public function getSchemaDefinitionResolver(\PoP\ComponentModel\TypeResolvers\TypeResolverInterface $typeResolver) : ?\PoP\ComponentModel\FieldResolvers\FieldSchemaDefinitionResolverInterface
    {
        return null;
    }
    public function getSchemaFieldType(\PoP\ComponentModel\TypeResolvers\TypeResolverInterface $typeResolver, string $fieldName) : ?string
    {
        if ($schemaDefinitionResolver = $this->getSchemaDefinitionResolver($typeResolver)) {
            return $schemaDefinitionResolver->getSchemaFieldType($typeResolver, $fieldName);
        }
        return null;
    }
    public function isSchemaFieldResponseNonNullable(\PoP\ComponentModel\TypeResolvers\TypeResolverInterface $typeResolver, string $fieldName) : bool
    {
        if ($schemaDefinitionResolver = $this->getSchemaDefinitionResolver($typeResolver)) {
            return $schemaDefinitionResolver->isSchemaFieldResponseNonNullable($typeResolver, $fieldName);
        }
        return \false;
    }
    public function getSchemaFieldDescription(\PoP\ComponentModel\TypeResolvers\TypeResolverInterface $typeResolver, string $fieldName) : ?string
    {
        if ($schemaDefinitionResolver = $this->getSchemaDefinitionResolver($typeResolver)) {
            return $schemaDefinitionResolver->getSchemaFieldDescription($typeResolver, $fieldName);
        }
        return null;
    }
    public function getSchemaFieldArgs(\PoP\ComponentModel\TypeResolvers\TypeResolverInterface $typeResolver, string $fieldName) : array
    {
        if ($schemaDefinitionResolver = $this->getSchemaDefinitionResolver($typeResolver)) {
            return $schemaDefinitionResolver->getSchemaFieldArgs($typeResolver, $fieldName);
        }
        return [];
    }
    protected abstract function hasSchemaFieldVersion(\PoP\ComponentModel\TypeResolvers\TypeResolverInterface $typeResolver, string $fieldName) : bool;
    public function getFilteredSchemaFieldArgs(\PoP\ComponentModel\TypeResolvers\TypeResolverInterface $typeResolver, string $fieldName) : array
    {
        if ($schemaDefinitionResolver = $this->getSchemaDefinitionResolver($typeResolver)) {
            $schemaFieldArgs = $schemaDefinitionResolver->getSchemaFieldArgs($typeResolver, $fieldName);
        } else {
            $schemaFieldArgs = [];
        }
        /**
         * Add the "versionConstraint" param. Add it at the end, so it doesn't affect the order of params for "orderedSchemaFieldArgs"
         */
        $this->maybeAddVersionConstraintSchemaFieldOrDirectiveArg($schemaFieldArgs, $this->hasSchemaFieldVersion($typeResolver, $fieldName));
        return $schemaFieldArgs;
    }
    public function getSchemaFieldDeprecationDescription(\PoP\ComponentModel\TypeResolvers\TypeResolverInterface $typeResolver, string $fieldName, array $fieldArgs = []) : ?string
    {
        if ($schemaDefinitionResolver = $this->getSchemaDefinitionResolver($typeResolver)) {
            return $schemaDefinitionResolver->getSchemaFieldDeprecationDescription($typeResolver, $fieldName, $fieldArgs);
        }
        return null;
    }
    public function addSchemaDefinitionForField(array &$schemaDefinition, \PoP\ComponentModel\TypeResolvers\TypeResolverInterface $typeResolver, string $fieldName) : void
    {
        if ($schemaDefinitionResolver = $this->getSchemaDefinitionResolver($typeResolver)) {
            $schemaDefinitionResolver->addSchemaDefinitionForField($schemaDefinition, $typeResolver, $fieldName);
        }
    }
}
