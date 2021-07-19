<?php

declare (strict_types=1);
namespace PoP\ComponentModel\ErrorHandling;

use PoP\ComponentModel\ErrorHandling\Error;
interface ErrorProviderInterface
{
    /**
     * @param array<string, mixed>|null $data
     * @param Error[]|null $nestedErrors
     */
    public function getError(string $fieldName, string $errorCode, string $errorMessage, ?array $data = null, ?array $nestedErrors = null) : Error;
    /**
     * Return an error to indicate that no fieldResolver processes this field,
     * which is different than returning a null value.
     * Needed for compatibility with CustomPostUnionTypeResolver,
     * so that data-fields aimed for another post_type are not retrieved
     * @param string|int $resultItemID
     */
    public function getNoFieldError($resultItemID, string $fieldName, string $typeName) : Error;
    /**
     * Return an error to indicate that a non-nullable field is returning a `null` value
     *
     * @param string $fieldName
     * @return Error
     */
    public function getNonNullableFieldError(string $fieldName) : Error;
    public function getMustNotBeArrayFieldError(string $fieldName, array $value) : Error;
    /**
     * @param mixed $value
     */
    public function getMustBeArrayFieldError(string $fieldName, $value) : Error;
    public function getArrayMustNotHaveNullItemsFieldError(string $fieldName, array $value) : Error;
    /**
     * @param mixed $value
     */
    public function getMustBeArrayOfArraysFieldError(string $fieldName, $value) : Error;
    /**
     * @param mixed $value
     */
    public function getMustNotBeArrayOfArraysFieldError(string $fieldName, $value) : Error;
    public function getArrayOfArraysMustNotHaveNullItemsFieldError(string $fieldName, array $value) : Error;
    /**
     * Return an error to indicate that no fieldResolver processes this field,
     * which is different than returning a null value.
     * Needed for compatibility with CustomPostUnionTypeResolver
     * (so that data-fields aimed for another post_type are not retrieved)
     */
    public function getValidationFailedError(string $fieldName, array $fieldArgs, array $validationDescriptions) : Error;
    /**
     * @param string|int $resultItemID
     */
    public function getNoFieldResolverProcessesFieldError($resultItemID, string $fieldName, array $fieldArgs) : Error;
    /**
     * @param string[] $schemaErrors
     */
    public function getNestedSchemaErrorsFieldError(array $schemaErrors, string $fieldName) : Error;
    /**
     * @param string[] $dbErrors
     */
    public function getNestedDBErrorsFieldError(array $dbErrors, string $fieldName) : Error;
    /**
     * @param Error[] $nestedErrors
     */
    public function getNestedErrorsFieldError(array $nestedErrors, string $fieldName) : Error;
}
