<?php

declare (strict_types=1);
namespace PoP\ComponentModel\FieldResolvers\InterfaceType;

use PoP\ComponentModel\Feedback\ObjectTypeFieldResolutionFeedbackStore;
use PoP\ComponentModel\TypeResolvers\ConcreteTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\InputTypeResolverInterface;
use PoP\GraphQLParser\Spec\Parser\Ast\AstInterface;
use PoP\GraphQLParser\Spec\Parser\Ast\FieldInterface;
interface InterfaceTypeFieldSchemaDefinitionResolverInterface
{
    /**
     * @return string[]
     */
    public function getFieldNamesToResolve() : array;
    /**
     * @param string $fieldName
     */
    public function getFieldTypeResolver($fieldName) : ConcreteTypeResolverInterface;
    /**
     * @param string $fieldName
     */
    public function getFieldDescription($fieldName) : ?string;
    /**
     * @param string $fieldName
     */
    public function getFieldTypeModifiers($fieldName) : int;
    /**
     * @param string $fieldName
     */
    public function getFieldDeprecationMessage($fieldName) : ?string;
    /**
     * @return array<string,InputTypeResolverInterface>
     * @param string $fieldName
     */
    public function getFieldArgNameTypeResolvers($fieldName) : array;
    /**
     * @return string[]
     * @param string $fieldName
     */
    public function getSensitiveFieldArgNames($fieldName) : array;
    /**
     * @param string $fieldName
     * @param string $fieldArgName
     */
    public function getFieldArgDescription($fieldName, $fieldArgName) : ?string;
    /**
     * @return mixed
     * @param string $fieldName
     * @param string $fieldArgName
     */
    public function getFieldArgDefaultValue($fieldName, $fieldArgName);
    /**
     * @param string $fieldName
     * @param string $fieldArgName
     */
    public function getFieldArgTypeModifiers($fieldName, $fieldArgName) : int;
    /**
     * @return array<string,InputTypeResolverInterface>
     * @param string $fieldName
     */
    public function getConsolidatedFieldArgNameTypeResolvers($fieldName) : array;
    /**
     * @return string[]
     * @param string $fieldName
     */
    public function getConsolidatedSensitiveFieldArgNames($fieldName) : array;
    /**
     * @param string $fieldName
     * @param string $fieldArgName
     */
    public function getConsolidatedFieldArgDescription($fieldName, $fieldArgName) : ?string;
    /**
     * @return mixed
     * @param string $fieldName
     * @param string $fieldArgName
     */
    public function getConsolidatedFieldArgDefaultValue($fieldName, $fieldArgName);
    /**
     * @param string $fieldName
     * @param string $fieldArgName
     */
    public function getConsolidatedFieldArgTypeModifiers($fieldName, $fieldArgName) : int;
    /**
     * @param \PoP\GraphQLParser\Spec\Parser\Ast\FieldInterface|string $fieldOrFieldName
     */
    public function isFieldGlobal($fieldOrFieldName) : bool;
    /**
     * @param \PoP\GraphQLParser\Spec\Parser\Ast\FieldInterface|string $fieldOrFieldName
     */
    public function isFieldAMutation($fieldOrFieldName) : bool;
    /**
     * Validate the constraints for a field argument
     * @param mixed $fieldArgValue
     * @param string $fieldName
     * @param string $fieldArgName
     * @param \PoP\GraphQLParser\Spec\Parser\Ast\AstInterface $astNode
     * @param \PoP\ComponentModel\Feedback\ObjectTypeFieldResolutionFeedbackStore $objectTypeFieldResolutionFeedbackStore
     */
    public function validateFieldArgValue($fieldName, $fieldArgName, $fieldArgValue, $astNode, $objectTypeFieldResolutionFeedbackStore) : void;
}
