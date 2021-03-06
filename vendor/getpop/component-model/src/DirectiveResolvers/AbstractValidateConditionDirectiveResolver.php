<?php

declare (strict_types=1);
namespace PoP\ComponentModel\DirectiveResolvers;

use PoP\ComponentModel\Feedback\Tokens;
use PoP\Translation\Facades\TranslationAPIFacade;
use PoP\ComponentModel\TypeResolvers\PipelinePositions;
use PoP\ComponentModel\TypeResolvers\TypeResolverInterface;
use PoP\ComponentModel\DirectiveResolvers\AbstractValidateDirectiveResolver;
abstract class AbstractValidateConditionDirectiveResolver extends \PoP\ComponentModel\DirectiveResolvers\AbstractValidateDirectiveResolver
{
    // /**
    //  * Validations are naturally added through code and not through the query, so no need to expose them in the schema
    //  *
    //  * @return boolean
    //  */
    // public function skipAddingToSchemaDefinition(): bool {
    //     return true;
    // }
    /**
     * If validating a directive, place it after resolveAndMerge
     * Otherwise, before
     *
     * @return void
     */
    public function getPipelinePosition() : string
    {
        if ($this->isValidatingDirective()) {
            return \PoP\ComponentModel\TypeResolvers\PipelinePositions::AFTER_RESOLVE;
        }
        return \PoP\ComponentModel\TypeResolvers\PipelinePositions::AFTER_VALIDATE_BEFORE_RESOLVE;
    }
    /**
     * Validate a custom condition
     *
     * @param TypeResolverInterface $typeResolver
     * @param array $dataFields
     * @param array $schemaErrors
     * @param array $schemaWarnings
     * @param array $schemaDeprecations
     * @param array $variables
     * @return void
     */
    protected function validateFields(\PoP\ComponentModel\TypeResolvers\TypeResolverInterface $typeResolver, array $dataFields, array &$schemaErrors, array &$schemaWarnings, array &$schemaDeprecations, array &$variables, array &$failedDataFields) : void
    {
        if (!$this->validateCondition($typeResolver)) {
            // All fields failed
            $failedDataFields = \array_merge($failedDataFields, $dataFields);
            $schemaErrors[] = [\PoP\ComponentModel\Feedback\Tokens::PATH => $dataFields, \PoP\ComponentModel\Feedback\Tokens::MESSAGE => $this->getValidationFailedMessage($typeResolver, $dataFields)];
        }
    }
    /**
     * Condition to validate. Return `true` for success, `false` for failure
     *
     * @param TypeResolverInterface $typeResolver
     * @return boolean
     */
    protected abstract function validateCondition(\PoP\ComponentModel\TypeResolvers\TypeResolverInterface $typeResolver) : bool;
    /**
     * Show a different error message depending on if we are validating the whole field, or a directive
     * By default, validate the whole field
     *
     * @return boolean
     */
    protected function isValidatingDirective() : bool
    {
        return \false;
    }
    protected function getValidationFailedMessage(\PoP\ComponentModel\TypeResolvers\TypeResolverInterface $typeResolver, array $failedDataFields) : string
    {
        $translationAPI = \PoP\Translation\Facades\TranslationAPIFacade::getInstance();
        $errorMessage = $this->isValidatingDirective() ? $translationAPI->__('Validation failed for directives in fields \'%s\'', 'component-model') : $translationAPI->__('Validation failed for fields \'%s\'', 'component-model');
        return \sprintf($errorMessage, \implode($translationAPI->__('\', \''), $failedDataFields));
    }
}
