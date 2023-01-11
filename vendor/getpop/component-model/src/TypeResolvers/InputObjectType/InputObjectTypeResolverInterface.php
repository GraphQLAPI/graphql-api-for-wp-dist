<?php

declare (strict_types=1);
namespace PoP\ComponentModel\TypeResolvers\InputObjectType;

use PoP\ComponentModel\Feedback\ObjectTypeFieldResolutionFeedbackStore;
use PoP\ComponentModel\TypeResolvers\DeprecatableInputTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\InputTypeResolverInterface;
use PoP\GraphQLParser\Spec\Parser\Ast\AstInterface;
use stdClass;
/**
 * Based on GraphQL InputObject Type
 *
 * @see https://spec.graphql.org/draft/#sec-Input-Objects
 */
interface InputObjectTypeResolverInterface extends DeprecatableInputTypeResolverInterface
{
    /**
     * Define input fields
     *
     * @return array<string,InputTypeResolverInterface>
     */
    public function getInputFieldNameTypeResolvers() : array;
    /**
     * @return string[]
     */
    public function getSensitiveInputFieldNames() : array;
    /**
     * @param string $inputFieldName
     */
    public function getInputFieldDescription($inputFieldName) : ?string;
    /**
     * @return mixed
     * @param string $inputFieldName
     */
    public function getInputFieldDefaultValue($inputFieldName);
    /**
     * @param string $inputFieldName
     */
    public function getInputFieldTypeModifiers($inputFieldName) : int;
    /**
     * Consolidation of the schema inputs. Call this function to read the data
     * instead of the individual functions, since it applies hooks to override/extend.
     *
     * @return array<string,InputTypeResolverInterface>
     */
    public function getConsolidatedInputFieldNameTypeResolvers() : array;
    /**
     * @return string[]
     */
    public function getConsolidatedAdminInputFieldNames() : array;
    /**
     * @param string $inputFieldName
     */
    public function getConsolidatedInputFieldDescription($inputFieldName) : ?string;
    /**
     * @return mixed
     * @param string $inputFieldName
     */
    public function getConsolidatedInputFieldDefaultValue($inputFieldName);
    /**
     * @param string $inputFieldName
     */
    public function getConsolidatedInputFieldTypeModifiers($inputFieldName) : int;
    /**
     * Input fields may not be directly visible in the schema,
     * eg: because they are used only by the application, and must not
     * be exposed to the user
     * @param string $inputFieldName
     */
    public function skipExposingInputFieldInSchema($inputFieldName) : bool;
    /**
     * @return array<string,mixed>
     * @param string $inputFieldName
     */
    public function getInputFieldSchemaDefinition($inputFieldName) : array;
    /**
     * Validate constraints on the input's value
     * @param \stdClass $inputValue
     * @param \PoP\GraphQLParser\Spec\Parser\Ast\AstInterface $astNode
     * @param \PoP\ComponentModel\Feedback\ObjectTypeFieldResolutionFeedbackStore $objectTypeFieldResolutionFeedbackStore
     */
    public function validateInputValue($inputValue, $astNode, $objectTypeFieldResolutionFeedbackStore) : void;
    public function hasMandatoryWithNoDefaultValueInputFields() : bool;
}
