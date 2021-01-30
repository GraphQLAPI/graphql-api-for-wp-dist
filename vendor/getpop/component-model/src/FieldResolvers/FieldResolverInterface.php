<?php

declare (strict_types=1);
namespace PoP\ComponentModel\FieldResolvers;

use PoP\ComponentModel\TypeResolvers\TypeResolverInterface;
use PoP\ComponentModel\FieldResolvers\FieldSchemaDefinitionResolverInterface;
interface FieldResolverInterface
{
    /**
     * Get an array with the fieldNames that this fieldResolver resolves
     *
     * @return array
     */
    public static function getFieldNamesToResolve() : array;
    /**
     * A list of classes of all the (GraphQL-style) interfaces the fieldResolver implements
     *
     * @return array
     */
    public static function getImplementedInterfaceClasses() : array;
    /**
     * Obtain the fieldNames from all implemented interfaces
     *
     * @return array
     */
    public static function getFieldNamesFromInterfaces() : array;
    /**
     * Get an instance of the object defining the schema for this fieldResolver
     *
     * @param TypeResolverInterface $typeResolver
     * @param string $fieldName
     * @param array<string, mixed> $fieldArgs
     * @return void
     */
    public function getSchemaDefinitionResolver(\PoP\ComponentModel\TypeResolvers\TypeResolverInterface $typeResolver) : ?\PoP\ComponentModel\FieldResolvers\FieldSchemaDefinitionResolverInterface;
    /**
     * Fields may not be directly visible in the schema,
     * eg: because they are used only by the application, and must not
     * be exposed to the user (eg: "accessControlLists")
     *
     * @param TypeResolverInterface $typeResolver
     * @param string $fieldName
     * @return boolean
     */
    public function skipAddingToSchemaDefinition(\PoP\ComponentModel\TypeResolvers\TypeResolverInterface $typeResolver, string $fieldName) : bool;
    public function getSchemaDefinitionForField(\PoP\ComponentModel\TypeResolvers\TypeResolverInterface $typeResolver, string $fieldName, array $fieldArgs = []) : array;
    public function getSchemaFieldVersion(\PoP\ComponentModel\TypeResolvers\TypeResolverInterface $typeResolver, string $fieldName) : ?string;
    /**
     * Indicate if the fields are global (i.e. they apply to all typeResolvers)
     *
     * @param TypeResolverInterface $typeResolver
     * @param string $fieldName
     * @return boolean
     */
    public function isGlobal(\PoP\ComponentModel\TypeResolvers\TypeResolverInterface $typeResolver, string $fieldName) : bool;
    /**
     * Indicates if the fieldResolver can process this combination of fieldName and fieldArgs
     * It is required to support a multiverse of fields: different fieldResolvers can resolve the field, based on the required version (passed through $fieldArgs['branch'])
     *
     * @param string $fieldName
     * @param array<string, mixed> $fieldArgs
     * @return boolean
     */
    public function resolveCanProcess(\PoP\ComponentModel\TypeResolvers\TypeResolverInterface $typeResolver, string $fieldName, array $fieldArgs = []) : bool;
    public function resolveSchemaValidationErrorDescriptions(\PoP\ComponentModel\TypeResolvers\TypeResolverInterface $typeResolver, string $fieldName, array $fieldArgs = []) : ?array;
    public function resolveSchemaValidationDeprecationDescriptions(\PoP\ComponentModel\TypeResolvers\TypeResolverInterface $typeResolver, string $fieldName, array $fieldArgs = []) : ?array;
    /**
     * @param array<string, mixed> $fieldArgs
     * @param array<string, mixed>|null $variables
     * @param array<string, mixed>|null $expressions
     * @param array<string, mixed> $options
     * @return mixed
     * @param object $resultItem
     */
    public function resolveValue(\PoP\ComponentModel\TypeResolvers\TypeResolverInterface $typeResolver, $resultItem, string $fieldName, array $fieldArgs = [], ?array $variables = null, ?array $expressions = null, array $options = []);
    /**
     * The mutation can be validated either on the schema (`false`)
     * on on the resultItem (`true`)
     */
    public function validateMutationOnResultItem(\PoP\ComponentModel\TypeResolvers\TypeResolverInterface $typeResolver, string $fieldName) : bool;
    public function resolveFieldTypeResolverClass(\PoP\ComponentModel\TypeResolvers\TypeResolverInterface $typeResolver, string $fieldName) : ?string;
    public function resolveFieldMutationResolverClass(\PoP\ComponentModel\TypeResolvers\TypeResolverInterface $typeResolver, string $fieldName) : ?string;
    public function resolveSchemaValidationWarningDescriptions(\PoP\ComponentModel\TypeResolvers\TypeResolverInterface $typeResolver, string $fieldName, array $fieldArgs = []) : ?array;
    /**
     * @param array<string, mixed> $fieldArgs
     * @param object $resultItem
     */
    public function resolveCanProcessResultItem(\PoP\ComponentModel\TypeResolvers\TypeResolverInterface $typeResolver, $resultItem, string $fieldName, array $fieldArgs = []) : bool;
    public function enableOrderedSchemaFieldArgs(\PoP\ComponentModel\TypeResolvers\TypeResolverInterface $typeResolver, string $fieldName) : bool;
    /**
     * @param array<string, mixed> $fieldArgs
     * @param object $resultItem
     */
    public function getValidationErrorDescriptions(\PoP\ComponentModel\TypeResolvers\TypeResolverInterface $typeResolver, $resultItem, string $fieldName, array $fieldArgs = []) : ?array;
    /**
     * Define if to use the version to decide if to process the field or not
     *
     * @param TypeResolverInterface $typeResolver
     * @return boolean
     */
    public function decideCanProcessBasedOnVersionConstraint(\PoP\ComponentModel\TypeResolvers\TypeResolverInterface $typeResolver) : bool;
}
