<?php

declare (strict_types=1);
namespace PoP\ComponentModel\FieldResolvers;

use PoP\ComponentModel\TypeResolvers\TypeResolverInterface;
use PoP\ComponentModel\Facades\Instances\InstanceManagerFacade;
/**
 * Create an alias of a fieldName (or fieldNames), to use when:
 *
 * - the same fieldName is registered more than once (eg: by different plugins)
 * - want to rename the field (steps: alias the field, then remove access to the original)
 *
 * This trait, to be applied on a FieldResolver class, uses the Proxy design pattern:
 * every function executed on the aliasing field executes the same function on the aliased field.
 *
 * The aliased FieldResolver is chosen to be of class `AbstractSchemaFieldResolver`,
 * since this is the highest level comprising the base `AbstractFieldResolver`
 * and the interface `FieldSchemaDefinitionResolverInterface`.
 *
 * It must indicate which specific `FieldResolver` class it is aliasing,
 * because if the field is duplicated, then just using the $fieldName
 * to obtain the FieldResolver is ambiguous.
 *
 * @author Leonardo Losoviz <leo@getpop.org>
 */
trait AliasSchemaFieldResolverTrait
{
    /**
     * The fieldName that is being aliased
     */
    protected abstract function getAliasedFieldName(string $fieldName) : string;
    /**
     * The specific `FieldResolver` class that is being aliased
     */
    protected abstract function getAliasedFieldResolverClass() : string;
    /**
     * Aliased `FieldResolver` instance
     */
    protected function getAliasedFieldResolverInstance() : \PoP\ComponentModel\FieldResolvers\AbstractSchemaFieldResolver
    {
        $instanceManager = \PoP\ComponentModel\Facades\Instances\InstanceManagerFacade::getInstance();
        return $instanceManager->getInstance($this->getAliasedFieldResolverClass());
    }
    /**
     * Proxy pattern: execute same function on the aliased FieldResolver,
     * for the aliased $fieldName
     */
    public function isGlobal(\PoP\ComponentModel\TypeResolvers\TypeResolverInterface $typeResolver, string $fieldName) : bool
    {
        $aliasedFieldResolver = $this->getAliasedFieldResolverInstance();
        return $aliasedFieldResolver->isGlobal($typeResolver, $this->getAliasedFieldName($fieldName));
    }
    /**
     * Proxy pattern: execute same function on the aliased FieldResolver,
     * for the aliased $fieldName
     */
    public function decideCanProcessBasedOnVersionConstraint(\PoP\ComponentModel\TypeResolvers\TypeResolverInterface $typeResolver) : bool
    {
        $aliasedFieldResolver = $this->getAliasedFieldResolverInstance();
        return $aliasedFieldResolver->decideCanProcessBasedOnVersionConstraint($typeResolver);
    }
    /**
     * Proxy pattern: execute same function on the aliased FieldResolver,
     * for the aliased $fieldName
     */
    public function resolveCanProcess(\PoP\ComponentModel\TypeResolvers\TypeResolverInterface $typeResolver, string $fieldName, array $fieldArgs = []) : bool
    {
        $aliasedFieldResolver = $this->getAliasedFieldResolverInstance();
        return $aliasedFieldResolver->resolveCanProcess($typeResolver, $this->getAliasedFieldName($fieldName), $fieldArgs);
    }
    /**
     * Proxy pattern: execute same function on the aliased FieldResolver,
     * for the aliased $fieldName
     */
    public function resolveSchemaValidationErrorDescriptions(\PoP\ComponentModel\TypeResolvers\TypeResolverInterface $typeResolver, string $fieldName, array $fieldArgs = []) : ?array
    {
        $aliasedFieldResolver = $this->getAliasedFieldResolverInstance();
        return $aliasedFieldResolver->resolveSchemaValidationErrorDescriptions($typeResolver, $this->getAliasedFieldName($fieldName), $fieldArgs);
    }
    /**
     * Proxy pattern: execute same function on the aliased FieldResolver,
     * for the aliased $fieldName
     */
    public function resolveSchemaValidationDeprecationDescriptions(\PoP\ComponentModel\TypeResolvers\TypeResolverInterface $typeResolver, string $fieldName, array $fieldArgs = []) : ?array
    {
        $aliasedFieldResolver = $this->getAliasedFieldResolverInstance();
        return $aliasedFieldResolver->resolveSchemaValidationDeprecationDescriptions($typeResolver, $this->getAliasedFieldName($fieldName), $fieldArgs);
    }
    /**
     * Proxy pattern: execute same function on the aliased FieldResolver,
     * for the aliased $fieldName
     */
    public function resolveSchemaValidationWarningDescriptions(\PoP\ComponentModel\TypeResolvers\TypeResolverInterface $typeResolver, string $fieldName, array $fieldArgs = []) : ?array
    {
        $aliasedFieldResolver = $this->getAliasedFieldResolverInstance();
        return $aliasedFieldResolver->resolveSchemaValidationWarningDescriptions($typeResolver, $this->getAliasedFieldName($fieldName), $fieldArgs);
    }
    /**
     * Proxy pattern: execute same function on the aliased FieldResolver,
     * for the aliased $fieldName
     */
    /**
     * @param array<string, mixed> $fieldArgs
     * @param object $resultItem
     */
    public function resolveCanProcessResultItem(\PoP\ComponentModel\TypeResolvers\TypeResolverInterface $typeResolver, $resultItem, string $fieldName, array $fieldArgs = []) : bool
    {
        $aliasedFieldResolver = $this->getAliasedFieldResolverInstance();
        return $aliasedFieldResolver->resolveCanProcessResultItem($typeResolver, $resultItem, $this->getAliasedFieldName($fieldName), $fieldArgs);
    }
    /**
     * Proxy pattern: execute same function on the aliased FieldResolver,
     * for the aliased $fieldName
     *
     * @param array<string, mixed> $fieldArgs
     * @param object $resultItem
     */
    public function getValidationErrorDescriptions(\PoP\ComponentModel\TypeResolvers\TypeResolverInterface $typeResolver, $resultItem, string $fieldName, array $fieldArgs = []) : ?array
    {
        $aliasedFieldResolver = $this->getAliasedFieldResolverInstance();
        return $aliasedFieldResolver->getValidationErrorDescriptions($typeResolver, $resultItem, $this->getAliasedFieldName($fieldName), $fieldArgs);
    }
    /**
     * Proxy pattern: execute same function on the aliased FieldResolver,
     * for the aliased $fieldName
     */
    public function skipAddingToSchemaDefinition(\PoP\ComponentModel\TypeResolvers\TypeResolverInterface $typeResolver, string $fieldName) : bool
    {
        $aliasedFieldResolver = $this->getAliasedFieldResolverInstance();
        return $aliasedFieldResolver->skipAddingToSchemaDefinition($typeResolver, $this->getAliasedFieldName($fieldName));
    }
    /**
     * Proxy pattern: execute same function on the aliased FieldResolver,
     * for the aliased $fieldName
     */
    public function getSchemaFieldType(\PoP\ComponentModel\TypeResolvers\TypeResolverInterface $typeResolver, string $fieldName) : ?string
    {
        $aliasedFieldResolver = $this->getAliasedFieldResolverInstance();
        return $aliasedFieldResolver->getSchemaFieldType($typeResolver, $this->getAliasedFieldName($fieldName));
    }
    /**
     * Proxy pattern: execute same function on the aliased FieldResolver,
     * for the aliased $fieldName
     */
    public function isSchemaFieldResponseNonNullable(\PoP\ComponentModel\TypeResolvers\TypeResolverInterface $typeResolver, string $fieldName) : bool
    {
        $aliasedFieldResolver = $this->getAliasedFieldResolverInstance();
        return $aliasedFieldResolver->isSchemaFieldResponseNonNullable($typeResolver, $this->getAliasedFieldName($fieldName));
    }
    /**
     * Proxy pattern: execute same function on the aliased FieldResolver,
     * for the aliased $fieldName
     */
    public function getSchemaFieldDescription(\PoP\ComponentModel\TypeResolvers\TypeResolverInterface $typeResolver, string $fieldName) : ?string
    {
        $aliasedFieldResolver = $this->getAliasedFieldResolverInstance();
        return $aliasedFieldResolver->getSchemaFieldDescription($typeResolver, $this->getAliasedFieldName($fieldName));
    }
    /**
     * Proxy pattern: execute same function on the aliased FieldResolver,
     * for the aliased $fieldName
     */
    public function getSchemaFieldArgs(\PoP\ComponentModel\TypeResolvers\TypeResolverInterface $typeResolver, string $fieldName) : array
    {
        $aliasedFieldResolver = $this->getAliasedFieldResolverInstance();
        return $aliasedFieldResolver->getSchemaFieldArgs($typeResolver, $this->getAliasedFieldName($fieldName));
    }
    /**
     * Proxy pattern: execute same function on the aliased FieldResolver,
     * for the aliased $fieldName
     */
    public function getFilteredSchemaFieldArgs(\PoP\ComponentModel\TypeResolvers\TypeResolverInterface $typeResolver, string $fieldName) : array
    {
        $aliasedFieldResolver = $this->getAliasedFieldResolverInstance();
        return $aliasedFieldResolver->getFilteredSchemaFieldArgs($typeResolver, $this->getAliasedFieldName($fieldName));
    }
    /**
     * Proxy pattern: execute same function on the aliased FieldResolver,
     * for the aliased $fieldName
     */
    public function getSchemaFieldDeprecationDescription(\PoP\ComponentModel\TypeResolvers\TypeResolverInterface $typeResolver, string $fieldName, array $fieldArgs = []) : ?string
    {
        $aliasedFieldResolver = $this->getAliasedFieldResolverInstance();
        return $aliasedFieldResolver->getSchemaFieldDeprecationDescription($typeResolver, $this->getAliasedFieldName($fieldName), $fieldArgs);
    }
    /**
     * Proxy pattern: execute same function on the aliased FieldResolver,
     * for the aliased $fieldName
     */
    public function addSchemaDefinitionForField(array &$schemaDefinition, \PoP\ComponentModel\TypeResolvers\TypeResolverInterface $typeResolver, string $fieldName) : void
    {
        $aliasedFieldResolver = $this->getAliasedFieldResolverInstance();
        $aliasedFieldResolver->addSchemaDefinitionForField($schemaDefinition, $typeResolver, $this->getAliasedFieldName($fieldName));
    }
    /**
     * Proxy pattern: execute same function on the aliased FieldResolver,
     * for the aliased $fieldName
     */
    public function enableOrderedSchemaFieldArgs(\PoP\ComponentModel\TypeResolvers\TypeResolverInterface $typeResolver, string $fieldName) : bool
    {
        $aliasedFieldResolver = $this->getAliasedFieldResolverInstance();
        return $aliasedFieldResolver->enableOrderedSchemaFieldArgs($typeResolver, $this->getAliasedFieldName($fieldName));
    }
    /**
     * Proxy pattern: execute same function on the aliased FieldResolver,
     * for the aliased $fieldName
     */
    public function getSchemaFieldVersion(\PoP\ComponentModel\TypeResolvers\TypeResolverInterface $typeResolver, string $fieldName) : ?string
    {
        $aliasedFieldResolver = $this->getAliasedFieldResolverInstance();
        return $aliasedFieldResolver->getSchemaFieldVersion($typeResolver, $this->getAliasedFieldName($fieldName));
    }
    /**
     * Proxy pattern: execute same function on the aliased FieldResolver,
     * for the aliased $fieldName
     *
     * @return mixed
     */
    public function resolveValue(\PoP\ComponentModel\TypeResolvers\TypeResolverInterface $typeResolver, $resultItem, string $fieldName, array $fieldArgs = [], ?array $variables = null, ?array $expressions = null, array $options = [])
    {
        $aliasedFieldResolver = $this->getAliasedFieldResolverInstance();
        return $aliasedFieldResolver->resolveValue($typeResolver, $resultItem, $this->getAliasedFieldName($fieldName), $fieldArgs, $variables, $expressions, $options);
    }
    /**
     * Proxy pattern: execute same function on the aliased FieldResolver,
     * for the aliased $fieldName
     *
     * @param array<string, mixed> $fieldArgs
     */
    public function resolveFieldTypeResolverClass(\PoP\ComponentModel\TypeResolvers\TypeResolverInterface $typeResolver, string $fieldName) : ?string
    {
        $aliasedFieldResolver = $this->getAliasedFieldResolverInstance();
        return $aliasedFieldResolver->resolveFieldTypeResolverClass($typeResolver, $this->getAliasedFieldName($fieldName));
    }
}
