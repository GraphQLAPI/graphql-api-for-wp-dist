<?php

declare (strict_types=1);
namespace PoP\Engine\DirectiveResolvers;

use PoP\FieldQuery\QuerySyntax;
use PoP\FieldQuery\QueryHelpers;
use PoP\ComponentModel\Misc\GeneralUtils;
use PoP\Engine\Dataloading\Expressions;
use PoP\ComponentModel\Schema\SchemaDefinition;
use PoP\ComponentModel\Schema\TypeCastingHelpers;
use PoP\Translation\Facades\TranslationAPIFacade;
use PoP\ComponentModel\TypeResolvers\AbstractTypeResolver;
use PoP\ComponentModel\TypeResolvers\TypeResolverInterface;
use PoP\ComponentModel\Facades\Schema\FeedbackMessageStoreFacade;
use PoP\ComponentModel\Facades\Schema\FieldQueryInterpreterFacade;
use PoP\ComponentModel\DirectiveResolvers\AbstractGlobalDirectiveResolver;
use PoP\ComponentModel\Feedback\Tokens;
abstract class AbstractApplyNestedDirectivesOnArrayItemsDirectiveResolver extends \PoP\ComponentModel\DirectiveResolvers\AbstractGlobalDirectiveResolver
{
    /**
     * Use a value that can't be part of a fieldName, that's legible, and that conveys the meaning of sublevel. The value "." is adequate
     */
    public const PROPERTY_SEPARATOR = '.';
    public function getSchemaDirectiveArgs(\PoP\ComponentModel\TypeResolvers\TypeResolverInterface $typeResolver) : array
    {
        $translationAPI = \PoP\Translation\Facades\TranslationAPIFacade::getInstance();
        return [[\PoP\ComponentModel\Schema\SchemaDefinition::ARGNAME_NAME => 'addExpressions', \PoP\ComponentModel\Schema\SchemaDefinition::ARGNAME_TYPE => \PoP\ComponentModel\Schema\TypeCastingHelpers::makeArray(\PoP\ComponentModel\Schema\SchemaDefinition::TYPE_MIXED), \PoP\ComponentModel\Schema\SchemaDefinition::ARGNAME_DESCRIPTION => \sprintf($translationAPI->__('Expressions to inject to the composed directive. The value of the affected field can be provided under special expression `%s`', 'component-model'), \PoP\FieldQuery\QueryHelpers::getExpressionQuery(\PoP\Engine\Dataloading\Expressions::NAME_VALUE))], [\PoP\ComponentModel\Schema\SchemaDefinition::ARGNAME_NAME => 'appendExpressions', \PoP\ComponentModel\Schema\SchemaDefinition::ARGNAME_TYPE => \PoP\ComponentModel\Schema\TypeCastingHelpers::makeArray(\PoP\ComponentModel\Schema\SchemaDefinition::TYPE_MIXED), \PoP\ComponentModel\Schema\SchemaDefinition::ARGNAME_DESCRIPTION => \sprintf($translationAPI->__('Append a value to an expression which must be an array, to inject to the composed directive. If the array has not been set, it is initialized as an empty array. The value of the affected field can be provided under special expression `%s`', 'component-model'), \PoP\FieldQuery\QueryHelpers::getExpressionQuery(\PoP\Engine\Dataloading\Expressions::NAME_VALUE))]];
    }
    public function getSchemaDirectiveExpressions(\PoP\ComponentModel\TypeResolvers\TypeResolverInterface $typeResolver) : array
    {
        $translationAPI = \PoP\Translation\Facades\TranslationAPIFacade::getInstance();
        return [\PoP\Engine\Dataloading\Expressions::NAME_VALUE => \sprintf($translationAPI->__('Value of the array element from the current iteration, available for params \'%s\' and \'%s\'', 'component-model'), 'addExpressions', 'appendExpressions')];
    }
    /**
     * Execute directive <transformProperty> to each of the elements on the affected field, which must be an array
     * This is achieved by executing the following logic:
     * 1. Unpack the elements of the array into a temporary property for each, in the current object
     * 2. Execute <transformProperty> on each property
     * 3. Pack into the array, once again, and remove all temporary properties
     *
     * @param TypeResolverInterface $typeResolver
     * @param array $resultIDItems
     * @param array $idsDataFields
     * @param array $dbItems
     * @param array $dbErrors
     * @param array $dbWarnings
     * @param array $schemaErrors
     * @param array $schemaWarnings
     * @param array $schemaDeprecations
     * @param array $previousDBItems
     * @param array $variables
     * @param array $messages
     * @return void
     */
    public function resolveDirective(\PoP\ComponentModel\TypeResolvers\TypeResolverInterface $typeResolver, array &$idsDataFields, array &$succeedingPipelineIDsDataFields, array &$succeedingPipelineDirectiveResolverInstances, array &$resultIDItems, array &$unionDBKeyIDs, array &$dbItems, array &$previousDBItems, array &$variables, array &$messages, array &$dbErrors, array &$dbWarnings, array &$dbDeprecations, array &$dbNotices, array &$dbTraces, array &$schemaErrors, array &$schemaWarnings, array &$schemaDeprecations, array &$schemaNotices, array &$schemaTraces) : void
    {
        $translationAPI = \PoP\Translation\Facades\TranslationAPIFacade::getInstance();
        // If there are no composed directives to execute, then nothing to do
        if (!$this->nestedDirectivePipelineData) {
            $schemaWarnings[] = [\PoP\ComponentModel\Feedback\Tokens::PATH => [$this->directive], \PoP\ComponentModel\Feedback\Tokens::MESSAGE => $translationAPI->__('No composed directives were provided, so nothing to do for this directive', 'component-model')];
            return;
        }
        /**
         * Collect all ID => dataFields for the arrayItems
         */
        $arrayItemIdsProperties = [];
        $fieldQueryInterpreter = \PoP\ComponentModel\Facades\Schema\FieldQueryInterpreterFacade::getInstance();
        $dbKey = $typeResolver->getTypeOutputName();
        /**
         * Execute composed directive only if the validations do not fail
         */
        $execute = \false;
        // 1. Unpack all elements of the array into a property for each
        // By making the property "propertyName:key", the "key" can be extracted and passed under expression `%key%` to the function
        foreach ($idsDataFields as $id => $dataFields) {
            foreach ($dataFields['direct'] as $field) {
                $fieldOutputKey = $fieldQueryInterpreter->getFieldOutputKey($field);
                // Validate that the property exists
                $isValueInDBItems = \array_key_exists($fieldOutputKey, $dbItems[(string) $id] ?? []);
                if (!$isValueInDBItems && !\array_key_exists($fieldOutputKey, $previousDBItems[$dbKey][(string) $id] ?? [])) {
                    if ($fieldOutputKey != $field) {
                        $dbErrors[(string) $id][] = [\PoP\ComponentModel\Feedback\Tokens::PATH => [$this->directive], \PoP\ComponentModel\Feedback\Tokens::MESSAGE => \sprintf($translationAPI->__('Field \'%s\' (under property \'%s\') hadn\'t been set for object with ID \'%s\', so it can\'t be transformed', 'component-model'), $field, $fieldOutputKey, $id)];
                    } else {
                        $dbErrors[(string) $id][] = [\PoP\ComponentModel\Feedback\Tokens::PATH => [$this->directive], \PoP\ComponentModel\Feedback\Tokens::MESSAGE => \sprintf($translationAPI->__('Field \'%s\' hadn\'t been set for object with ID \'%s\', so it can\'t be transformed', 'component-model'), $fieldOutputKey, $id)];
                    }
                    continue;
                }
                $value = $isValueInDBItems ? $dbItems[(string) $id][$fieldOutputKey] : $previousDBItems[$dbKey][(string) $id][$fieldOutputKey];
                // If the array is null or empty, nothing to do
                if (!$value) {
                    continue;
                }
                // Validate that the value is an array
                if (!\is_array($value)) {
                    if ($fieldOutputKey != $field) {
                        $dbErrors[(string) $id][] = [\PoP\ComponentModel\Feedback\Tokens::PATH => [$this->directive], \PoP\ComponentModel\Feedback\Tokens::MESSAGE => \sprintf($translationAPI->__('The value for field \'%s\' (under property \'%s\') is not an array, so execution of this directive can\'t continue', 'component-model'), $field, $fieldOutputKey, $id)];
                    } else {
                        $dbErrors[(string) $id][] = [\PoP\ComponentModel\Feedback\Tokens::PATH => [$this->directive], \PoP\ComponentModel\Feedback\Tokens::MESSAGE => \sprintf($translationAPI->__('The value for field \'%s\' is not an array, so execution of this directive can\'t continue', 'component-model'), $fieldOutputKey, $id)];
                    }
                    continue;
                }
                // Obtain the elements composing the field, to re-create a new field for each arrayItem
                $fieldParts = $fieldQueryInterpreter->listField($field);
                $fieldName = $fieldParts[0];
                $fieldArgs = $fieldParts[1];
                $fieldAlias = $fieldParts[2];
                $fieldSkipOutputIfNull = $fieldParts[3];
                $fieldDirectives = $fieldParts[4];
                // The value is an array. Unpack all the elements into their own property
                $array = $value;
                if ($arrayItems = $this->getArrayItems($array, $id, $field, $typeResolver, $resultIDItems, $dbItems, $previousDBItems, $variables, $messages, $dbErrors, $dbWarnings, $dbDeprecations)) {
                    $execute = \true;
                    foreach ($arrayItems as $key => &$value) {
                        // Add into the $idsDataFields object for the array items
                        // Watch out: function `regenerateAndExecuteFunction` receives `$idsDataFields` and not `$idsDataFieldOutputKeys`, so then re-create the "field" assigning a new alias
                        // If it has an alias, use it. If not, use the fieldName
                        $arrayItemAlias = $this->createPropertyForArrayItem($fieldAlias ? $fieldAlias : \PoP\FieldQuery\QuerySyntax::SYMBOL_FIELDALIAS_PREFIX . $fieldName, $key);
                        $arrayItemProperty = $fieldQueryInterpreter->composeField($fieldName, $fieldArgs, $arrayItemAlias, $fieldSkipOutputIfNull, $fieldDirectives);
                        $arrayItemPropertyOutputKey = $fieldQueryInterpreter->getFieldOutputKey($arrayItemProperty);
                        // Place into the current object
                        $dbItems[(string) $id][$arrayItemPropertyOutputKey] = $value;
                        // Place it into list of fields to process
                        $arrayItemIdsProperties[(string) $id]['direct'][] = $arrayItemProperty;
                    }
                    $arrayItemIdsProperties[(string) $id]['conditional'] = [];
                    $this->addExpressionsForResultItem($typeResolver, $id, $field, $resultIDItems, $dbItems, $previousDBItems, $variables, $messages, $dbErrors, $dbWarnings, $dbDeprecations, $schemaErrors, $schemaWarnings, $schemaDeprecations);
                }
            }
        }
        if ($execute) {
            // Build the directive pipeline
            $directiveResolverInstances = \array_map(function ($pipelineStageData) {
                return $pipelineStageData['instance'];
            }, $this->nestedDirectivePipelineData);
            $nestedDirectivePipeline = $typeResolver->getDirectivePipeline($directiveResolverInstances);
            // Fill the idsDataFields for each directive in the pipeline
            $pipelineArrayItemIdsProperties = [];
            for ($i = 0; $i < \count($directiveResolverInstances); $i++) {
                $pipelineArrayItemIdsProperties[] = $arrayItemIdsProperties;
            }
            // 2. Execute the composed directive pipeline on all arrayItems
            $nestedDirectivePipeline->resolveDirectivePipeline(
                $typeResolver,
                $pipelineArrayItemIdsProperties,
                // Here we pass the properties to the array elements!
                $directiveResolverInstances,
                $resultIDItems,
                $unionDBKeyIDs,
                $dbItems,
                $previousDBItems,
                $variables,
                $messages,
                $dbErrors,
                $dbWarnings,
                $dbDeprecations,
                $dbNotices,
                $dbTraces,
                $schemaErrors,
                $schemaWarnings,
                $schemaDeprecations,
                $schemaNotices,
                $schemaTraces
            );
            // 3. Compose the array from the results for each array item
            foreach ($idsDataFields as $id => $dataFields) {
                foreach ($dataFields['direct'] as $field) {
                    $fieldOutputKey = $fieldQueryInterpreter->getFieldOutputKey($field);
                    $isValueInDBItems = \array_key_exists($fieldOutputKey, $dbItems[(string) $id] ?? []);
                    $value = $isValueInDBItems ? $dbItems[(string) $id][$fieldOutputKey] : $previousDBItems[$dbKey][(string) $id][$fieldOutputKey];
                    // If the array is null or empty, nothing to do
                    if (!$value) {
                        continue;
                    }
                    if (!\is_array($value)) {
                        continue;
                    }
                    // Obtain the elements composing the field, to re-create a new field for each arrayItem
                    $fieldParts = $fieldQueryInterpreter->listField($field);
                    $fieldName = $fieldParts[0];
                    $fieldArgs = $fieldParts[1];
                    $fieldAlias = $fieldParts[2];
                    $fieldSkipOutputIfNull = $fieldParts[3];
                    $fieldDirectives = $fieldParts[4];
                    // If there are errors, it will return null. Don't add the errors again
                    $arrayItemDBErrors = $arrayItemDBWarnings = $arrayItemDBDeprecations = [];
                    $array = $value;
                    $arrayItems = $this->getArrayItems($array, $id, $field, $typeResolver, $resultIDItems, $dbItems, $previousDBItems, $variables, $messages, $arrayItemDBErrors, $arrayItemDBWarnings, $arrayItemDBDeprecations);
                    // The value is an array. Unpack all the elements into their own property
                    foreach ($arrayItems as $key => &$value) {
                        $arrayItemAlias = $this->createPropertyForArrayItem($fieldAlias ? $fieldAlias : \PoP\FieldQuery\QuerySyntax::SYMBOL_FIELDALIAS_PREFIX . $fieldName, $key);
                        $arrayItemProperty = $fieldQueryInterpreter->composeField($fieldName, $fieldArgs, $arrayItemAlias, $fieldSkipOutputIfNull, $fieldDirectives);
                        // Place the result of executing the function on the array item
                        $arrayItemPropertyOutputKey = $fieldQueryInterpreter->getFieldOutputKey($arrayItemProperty);
                        $arrayItemValue = $dbItems[(string) $id][$arrayItemPropertyOutputKey];
                        // Remove this temporary property from $dbItems
                        unset($dbItems[(string) $id][$arrayItemPropertyOutputKey]);
                        // Validate it's not an error
                        if (\PoP\ComponentModel\Misc\GeneralUtils::isError($arrayItemValue)) {
                            $error = $arrayItemValue;
                            $dbErrors[(string) $id][] = [\PoP\ComponentModel\Feedback\Tokens::PATH => [$this->directive], \PoP\ComponentModel\Feedback\Tokens::MESSAGE => \sprintf($translationAPI->__('Transformation of element with key \'%s\' on array from property \'%s\' on object with ID \'%s\' failed due to error: %s', 'component-model'), $key, $fieldOutputKey, $id, $error->getErrorMessage())];
                            continue;
                        }
                        // Place the result for the array in the original property
                        $this->addProcessedItemBackToDBItems($typeResolver, $dbItems, $dbErrors, $dbWarnings, $dbDeprecations, $dbNotices, $dbTraces, $id, $fieldOutputKey, $key, $arrayItemValue);
                    }
                }
            }
        }
    }
    /**
     * Place the result for the array in the original property
     * @param int|string $arrayItemKey
     */
    protected function addProcessedItemBackToDBItems(\PoP\ComponentModel\TypeResolvers\TypeResolverInterface $typeResolver, array &$dbItems, array &$dbErrors, array &$dbWarnings, array &$dbDeprecations, array &$dbNotices, array &$dbTraces, $id, string $fieldOutputKey, $arrayItemKey, $arrayItemValue) : void
    {
        $dbItems[(string) $id][$fieldOutputKey][$arrayItemKey] = $arrayItemValue;
    }
    /**
     * Return the items to iterate on
     *
     * @param array $value
     * @return void
     */
    protected abstract function getArrayItems(array &$array, $id, string $field, \PoP\ComponentModel\TypeResolvers\TypeResolverInterface $typeResolver, array &$resultIDItems, array &$dbItems, array &$previousDBItems, array &$variables, array &$messages, array &$dbErrors, array &$dbWarnings, array &$dbDeprecations) : ?array;
    /**
     * Create a property for storing the array item in the current object
     *
     * @param string $fieldOutputKey
     * @param [type] $key
     * @return void
     */
    protected function createPropertyForArrayItem(string $fieldAliasOrName, $key) : string
    {
        return \implode(self::PROPERTY_SEPARATOR, [$fieldAliasOrName, $key]);
    }
    // protected function extractElementsFromArrayItemProperty(string $arrayItemProperty): array
    // {
    //     // Notice that we may be nesting several directives, such as <forEach<forEach<transform>>
    //     // Then, the property will contain several instances of unpacking arrayItems
    //     // For this reason, when extracting the property, obtain the right-side value from the last instance of the separator
    //     // $pos = QueryUtils::findLastSymbolPosition($arrayItemProperty, self::PROPERTY_SEPARATOR);
    //     return explode(self::PROPERTY_SEPARATOR, $arrayItemProperty);
    // }
    /**
     * Add the $key in addition to the $value
     *
     * @param TypeResolverInterface $typeResolver
     * @param [type] $id
     * @param string $field
     * @param array $resultIDItems
     * @param array $dbItems
     * @param array $dbErrors
     * @param array $dbWarnings
     * @param array $schemaErrors
     * @param array $schemaWarnings
     * @param array $schemaDeprecations
     * @param array $previousDBItems
     * @param array $variables
     * @param array $messages
     * @return void
     */
    protected function addExpressionsForResultItem(\PoP\ComponentModel\TypeResolvers\TypeResolverInterface $typeResolver, $id, string $field, array &$resultIDItems, array &$dbItems, array &$previousDBItems, array &$variables, array &$messages, array &$dbErrors, array &$dbWarnings, array &$dbDeprecations, array &$schemaErrors, array &$schemaWarnings, array &$schemaDeprecations)
    {
        $translationAPI = \PoP\Translation\Facades\TranslationAPIFacade::getInstance();
        // Enable the query to provide variables to pass down
        $addExpressions = $this->directiveArgsForSchema['addExpressions'] ?? [];
        $appendExpressions = $this->directiveArgsForSchema['appendExpressions'] ?? [];
        if ($addExpressions || $appendExpressions) {
            // The expressions may need `$value`, so add it
            $fieldQueryInterpreter = \PoP\ComponentModel\Facades\Schema\FieldQueryInterpreterFacade::getInstance();
            $fieldOutputKey = $fieldQueryInterpreter->getFieldOutputKey($field);
            $isValueInDBItems = \array_key_exists($fieldOutputKey, $dbItems[(string) $id] ?? []);
            $dbKey = $typeResolver->getTypeOutputName();
            $value = $isValueInDBItems ? $dbItems[(string) $id][$fieldOutputKey] : $previousDBItems[$dbKey][(string) $id][$fieldOutputKey];
            $this->addExpressionForResultItem($id, \PoP\Engine\Dataloading\Expressions::NAME_VALUE, $value, $messages);
            $expressions = $this->getExpressionsForResultItem($id, $variables, $messages);
            $options = [\PoP\ComponentModel\TypeResolvers\AbstractTypeResolver::OPTION_VALIDATE_SCHEMA_ON_RESULT_ITEM => \true];
            foreach ($addExpressions as $key => $value) {
                // Evaluate the $value, since it may be a function
                if ($fieldQueryInterpreter->isFieldArgumentValueAField($value)) {
                    $resolvedValue = $typeResolver->resolveValue($resultIDItems[(string) $id], $value, $variables, $expressions, $options);
                    // Merge the dbWarnings, if any
                    $feedbackMessageStore = \PoP\ComponentModel\Facades\Schema\FeedbackMessageStoreFacade::getInstance();
                    if ($resultItemDBWarnings = $feedbackMessageStore->retrieveAndClearResultItemDBWarnings($id)) {
                        $dbWarnings[$id] = \array_merge($dbWarnings[$id] ?? [], $resultItemDBWarnings);
                    }
                    if (\PoP\ComponentModel\Misc\GeneralUtils::isError($resolvedValue)) {
                        // Show the error message, and return nothing
                        $error = $resolvedValue;
                        $dbErrors[(string) $id][] = [\PoP\ComponentModel\Feedback\Tokens::PATH => [$this->directive], \PoP\ComponentModel\Feedback\Tokens::MESSAGE => \sprintf($translationAPI->__('Executing field \'%s\' on object with ID \'%s\' produced error: %s. Setting expression \'%s\' was ignored', 'pop-component-model'), $value, $id, $error->getErrorMessage(), $key)];
                        continue;
                    }
                    $value = $resolvedValue;
                }
                $this->addExpressionForResultItem($id, $key, $resolvedValue, $messages);
            }
            foreach ($appendExpressions as $key => $value) {
                $existingValue = $this->getExpressionForResultItem($id, $key, $messages) ?? [];
                // Evaluate the $value, since it may be a function
                if ($fieldQueryInterpreter->isFieldArgumentValueAField($value)) {
                    $resolvedValue = $typeResolver->resolveValue($resultIDItems[(string) $id], $value, $variables, $expressions, $options);
                    // Merge the dbWarnings, if any
                    $feedbackMessageStore = \PoP\ComponentModel\Facades\Schema\FeedbackMessageStoreFacade::getInstance();
                    if ($resultItemDBWarnings = $feedbackMessageStore->retrieveAndClearResultItemDBWarnings($id)) {
                        $dbWarnings[$id] = \array_merge($dbWarnings[$id] ?? [], $resultItemDBWarnings);
                    }
                    if (\PoP\ComponentModel\Misc\GeneralUtils::isError($resolvedValue)) {
                        // Show the error message, and return nothing
                        $error = $resolvedValue;
                        $dbErrors[(string) $id][] = [\PoP\ComponentModel\Feedback\Tokens::PATH => [$this->directive], \PoP\ComponentModel\Feedback\Tokens::MESSAGE => \sprintf($translationAPI->__('Executing field \'%s\' on object with ID \'%s\' produced error: %s. Setting expression \'%s\' was ignored', 'pop-component-model'), $value, $id, $error->getErrorMessage(), $key)];
                        continue;
                    }
                    $existingValue[] = $resolvedValue;
                }
                $this->addExpressionForResultItem($id, $key, $existingValue, $messages);
            }
        }
    }
}
