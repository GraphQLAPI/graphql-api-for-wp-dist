<?php

declare (strict_types=1);
namespace PoP\ComponentModel\DirectiveResolvers;

use PoP\ComponentModel\Container\ServiceTags\MandatoryDirectiveServiceTagInterface;
use PoP\ComponentModel\Directives\DirectiveTypes;
use PoP\ComponentModel\Facades\Schema\FeedbackMessageStoreFacade;
use PoP\ComponentModel\Facades\Schema\FieldQueryInterpreterFacade;
use PoP\ComponentModel\Feedback\Tokens;
use PoP\ComponentModel\Misc\GeneralUtils;
use PoP\ComponentModel\TypeResolvers\PipelinePositions;
use PoP\ComponentModel\TypeResolvers\TypeResolverInterface;
use PoP\Translation\Facades\TranslationAPIFacade;
final class ResolveValueAndMergeDirectiveResolver extends \PoP\ComponentModel\DirectiveResolvers\AbstractGlobalDirectiveResolver implements \PoP\ComponentModel\Container\ServiceTags\MandatoryDirectiveServiceTagInterface
{
    public const DIRECTIVE_NAME = 'resolveValueAndMerge';
    public static function getDirectiveName() : string
    {
        return self::DIRECTIVE_NAME;
    }
    /**
     * This is a system directive
     *
     * @return string
     */
    public function getDirectiveType() : string
    {
        return \PoP\ComponentModel\Directives\DirectiveTypes::SYSTEM;
    }
    /**
     * This directive must be the first one of its group
     *
     * @return void
     */
    public function getPipelinePosition() : string
    {
        return \PoP\ComponentModel\TypeResolvers\PipelinePositions::AFTER_RESOLVE;
    }
    public function resolveDirective(\PoP\ComponentModel\TypeResolvers\TypeResolverInterface $typeResolver, array &$idsDataFields, array &$succeedingPipelineIDsDataFields, array &$succeedingPipelineDirectiveResolverInstances, array &$resultIDItems, array &$unionDBKeyIDs, array &$dbItems, array &$previousDBItems, array &$variables, array &$messages, array &$dbErrors, array &$dbWarnings, array &$dbDeprecations, array &$dbNotices, array &$dbTraces, array &$schemaErrors, array &$schemaWarnings, array &$schemaDeprecations, array &$schemaNotices, array &$schemaTraces) : void
    {
        // Iterate data, extract into final results
        if ($resultIDItems) {
            $this->resolveValueForResultItems($typeResolver, $resultIDItems, $idsDataFields, $dbItems, $previousDBItems, $variables, $messages, $dbErrors, $dbWarnings, $dbDeprecations, $schemaErrors, $schemaWarnings, $schemaDeprecations);
        }
    }
    protected function resolveValueForResultItems(\PoP\ComponentModel\TypeResolvers\TypeResolverInterface $typeResolver, array &$resultIDItems, array &$idsDataFields, array &$dbItems, array &$previousDBItems, array &$variables, array &$messages, array &$dbErrors, array &$dbWarnings, array &$dbDeprecations, array &$schemaErrors, array &$schemaWarnings, array &$schemaDeprecations)
    {
        $translationAPI = \PoP\Translation\Facades\TranslationAPIFacade::getInstance();
        $fieldQueryInterpreter = \PoP\ComponentModel\Facades\Schema\FieldQueryInterpreterFacade::getInstance();
        $enqueueFillingResultItemsFromIDs = [];
        foreach (\array_keys($idsDataFields) as $id) {
            // Obtain its ID and the required data-fields for that ID
            $resultItem = $resultIDItems[$id];
            // It could be that the object is NULL. For instance: a post has a location stored a meta value, and the corresponding location object was deleted, so the ID is pointing to a non-existing object
            // In that case, simply return a dbError, and set the result as an empty array
            if (\is_null($resultItem)) {
                $dbErrors[(string) $id][] = [\PoP\ComponentModel\Feedback\Tokens::PATH => ['id'], \PoP\ComponentModel\Feedback\Tokens::MESSAGE => \sprintf($translationAPI->__('Corrupted data: Object with ID \'%s\' doesn\'t exist', 'component-model'), $id)];
                // This is currently pointing to NULL and returning this entry in the database. Remove it
                // (this will also avoid errors in the Engine, which expects this result to be an array and can't be null)
                unset($dbItems[(string) $id]);
                continue;
            }
            $expressions = $this->getExpressionsForResultItem($id, $variables, $messages);
            $this->resolveValuesForResultItem($typeResolver, $id, $resultItem, $idsDataFields[(string) $id]['direct'], $dbItems, $previousDBItems, $variables, $expressions, $dbErrors, $dbWarnings, $dbDeprecations);
            // Add the conditional data fields
            // If the conditionalDataFields are empty, we already reached the end of the tree. Nothing else to do
            foreach (\array_filter($idsDataFields[$id]['conditional']) as $conditionDataField => $conditionalDataFields) {
                // Check if the condition field has value `true`
                // All 'conditional' fields must have their own key as 'direct', then simply look for this element on $dbItems
                $conditionFieldOutputKey = $fieldQueryInterpreter->getFieldOutputKey($conditionDataField);
                if (isset($dbItems[$id]) && \array_key_exists($conditionFieldOutputKey, $dbItems[$id])) {
                    $conditionSatisfied = (bool) $dbItems[$id][$conditionFieldOutputKey];
                } else {
                    $conditionSatisfied = \false;
                }
                if ($conditionSatisfied) {
                    $enqueueFillingResultItemsFromIDs[(string) $id]['direct'] = \array_unique(\array_merge($enqueueFillingResultItemsFromIDs[(string) $id]['direct'] ?? [], \array_keys($conditionalDataFields)));
                    foreach ($conditionalDataFields as $nextConditionDataField => $nextConditionalDataFields) {
                        $enqueueFillingResultItemsFromIDs[(string) $id]['conditional'][$nextConditionDataField] = \array_merge_recursive($enqueueFillingResultItemsFromIDs[(string) $id]['conditional'][$nextConditionDataField] ?? [], $nextConditionalDataFields);
                    }
                }
            }
        }
        // Enqueue items for the next iteration
        if ($enqueueFillingResultItemsFromIDs) {
            $typeResolver->enqueueFillingResultItemsFromIDs($enqueueFillingResultItemsFromIDs);
        }
    }
    /**
     * @param object $resultItem
     */
    protected function resolveValuesForResultItem(\PoP\ComponentModel\TypeResolvers\TypeResolverInterface $typeResolver, $id, $resultItem, array $dataFields, array &$dbItems, array &$previousDBItems, array &$variables, array &$expressions, array &$dbErrors, array &$dbWarnings, array &$dbDeprecations)
    {
        foreach ($dataFields as $field) {
            $this->resolveValueForResultItem($typeResolver, $id, $resultItem, $field, $dbItems, $previousDBItems, $variables, $expressions, $dbErrors, $dbWarnings, $dbDeprecations);
        }
    }
    /**
     * @param object $resultItem
     */
    protected function resolveValueForResultItem(\PoP\ComponentModel\TypeResolvers\TypeResolverInterface $typeResolver, $id, $resultItem, string $field, array &$dbItems, array &$previousDBItems, array &$variables, array &$expressions, array &$dbErrors, array &$dbWarnings, array &$dbDeprecations)
    {
        // Get the value, and add it to the database
        $value = $this->resolveFieldValue($typeResolver, $id, $resultItem, $field, $previousDBItems, $variables, $expressions, $dbWarnings, $dbDeprecations);
        $this->addValueForResultItem($typeResolver, $id, $field, $value, $dbItems, $dbErrors);
    }
    /**
     * @param object $resultItem
     */
    protected function resolveFieldValue(\PoP\ComponentModel\TypeResolvers\TypeResolverInterface $typeResolver, $id, $resultItem, string $field, array &$previousDBItems, array &$variables, array &$expressions, array &$dbWarnings, array &$dbDeprecations)
    {
        $value = $typeResolver->resolveValue($resultItem, $field, $variables, $expressions);
        // Merge the dbWarnings and dbDeprecations, if any
        $feedbackMessageStore = \PoP\ComponentModel\Facades\Schema\FeedbackMessageStoreFacade::getInstance();
        if ($resultItemDBWarnings = $feedbackMessageStore->retrieveAndClearResultItemDBWarnings($id)) {
            $dbWarnings[$id] = \array_merge($dbWarnings[$id] ?? [], $resultItemDBWarnings);
        }
        if ($resultItemDBDeprecations = $feedbackMessageStore->retrieveAndClearResultItemDBDeprecations($id)) {
            $dbDeprecations[$id] = \array_merge($dbDeprecations[$id] ?? [], $resultItemDBDeprecations);
        }
        return $value;
    }
    protected function addValueForResultItem(\PoP\ComponentModel\TypeResolvers\TypeResolverInterface $typeResolver, $id, string $field, $value, array &$dbItems, array &$dbErrors)
    {
        // The dataitem can contain both rightful values and also errors (eg: when the field doesn't exist, or the field validation fails)
        // Extract the errors and add them on the other array
        if (\PoP\ComponentModel\Misc\GeneralUtils::isError($value)) {
            // Extract the error message
            $error = $value;
            foreach ($error->getErrorMessages() as $errorMessage) {
                $dbErrors[(string) $id][] = [\PoP\ComponentModel\Feedback\Tokens::PATH => [$field], \PoP\ComponentModel\Feedback\Tokens::MESSAGE => $errorMessage];
            }
        } else {
            // If there is an alias, store the results under this. Otherwise, on the fieldName+fieldArgs
            $fieldOutputKey = \PoP\ComponentModel\Facades\Schema\FieldQueryInterpreterFacade::getInstance()->getFieldOutputKey($field);
            $dbItems[(string) $id][$fieldOutputKey] = $value;
        }
    }
    public function getSchemaDirectiveDescription(\PoP\ComponentModel\TypeResolvers\TypeResolverInterface $typeResolver) : ?string
    {
        $translationAPI = \PoP\Translation\Facades\TranslationAPIFacade::getInstance();
        return $translationAPI->__('Resolve the value of the field and merge it into results. This directive is already included by the engine, since its execution is mandatory', 'component-model');
    }
}
