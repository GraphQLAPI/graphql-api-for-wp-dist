<?php

declare (strict_types=1);
namespace PoPSchema\BasicDirectives\DirectiveResolvers;

use PoP\ComponentModel\Schema\SchemaHelpers;
use PoP\ComponentModel\Schema\SchemaDefinition;
use PoP\Translation\Facades\TranslationAPIFacade;
use PoPSchema\BasicDirectives\Enums\DefaultConditionEnum;
use PoP\ComponentModel\TypeResolvers\TypeResolverInterface;
use PoP\ComponentModel\Facades\Instances\InstanceManagerFacade;
use PoP\ComponentModel\Facades\Schema\FieldQueryInterpreterFacade;
use PoP\ComponentModel\DirectiveResolvers\AbstractSchemaDirectiveResolver;
abstract class AbstractUseDefaultValueIfConditionDirectiveResolver extends \PoP\ComponentModel\DirectiveResolvers\AbstractSchemaDirectiveResolver
{
    protected function getDefaultValue()
    {
        return null;
    }
    public function resolveDirective(\PoP\ComponentModel\TypeResolvers\TypeResolverInterface $typeResolver, array &$idsDataFields, array &$succeedingPipelineIDsDataFields, array &$succeedingPipelineDirectiveResolverInstances, array &$resultIDItems, array &$unionDBKeyIDs, array &$dbItems, array &$previousDBItems, array &$variables, array &$messages, array &$dbErrors, array &$dbWarnings, array &$dbDeprecations, array &$dbNotices, array &$dbTraces, array &$schemaErrors, array &$schemaWarnings, array &$schemaDeprecations, array &$schemaNotices, array &$schemaTraces) : void
    {
        // Replace all the NULL results with the default value
        $fieldQueryInterpreter = \PoP\ComponentModel\Facades\Schema\FieldQueryInterpreterFacade::getInstance();
        $fieldOutputKeyCache = [];
        foreach ($idsDataFields as $id => $dataFields) {
            // Use either the default value passed under param "value" or, if this is NULL, use a predefined value
            $expressions = $this->getExpressionsForResultItem($id, $variables, $messages);
            $resultItem = $resultIDItems[$id];
            list($resultItemValidDirective, $resultItemDirectiveName, $resultItemDirectiveArgs) = $this->dissectAndValidateDirectiveForResultItem($typeResolver, $resultItem, $variables, $expressions, $dbErrors, $dbWarnings, $dbDeprecations);
            // Check that the directive is valid. If it is not, $dbErrors will have the error already added
            if (\is_null($resultItemValidDirective)) {
                continue;
            }
            // Take the default value from the directiveArgs
            $defaultValue = $resultItemDirectiveArgs['value'];
            $condition = $resultItemDirectiveArgs['condition'];
            if (!\is_null($defaultValue)) {
                foreach ($dataFields['direct'] as $field) {
                    // Get the fieldOutputKey from the cache, or calculate it
                    if (!isset($fieldOutputKeyCache[$field])) {
                        $fieldOutputKeyCache[$field] = $fieldQueryInterpreter->getFieldOutputKey($field);
                    }
                    $fieldOutputKey = $fieldOutputKeyCache[$field];
                    // If it is null, replace it with the default value
                    if ($this->matchesCondition($condition, $dbItems[$id][$fieldOutputKey])) {
                        $dbItems[$id][$fieldOutputKey] = $defaultValue;
                    }
                }
            }
        }
    }
    /**
     * Indicate if the value matches the condition under which to inject the default value
     *
     * @param mixed $value
     * @return boolean
     */
    protected function matchesCondition(string $condition, $value) : bool
    {
        switch ($condition) {
            case \PoPSchema\BasicDirectives\Enums\DefaultConditionEnum::IS_NULL:
                return \is_null($value);
            case \PoPSchema\BasicDirectives\Enums\DefaultConditionEnum::IS_EMPTY:
                return empty($value);
        }
        return \false;
    }
    public function getSchemaDirectiveDescription(\PoP\ComponentModel\TypeResolvers\TypeResolverInterface $typeResolver) : ?string
    {
        $translationAPI = \PoP\Translation\Facades\TranslationAPIFacade::getInstance();
        $defaultValue = $this->getDefaultValue();
        if (\is_null($defaultValue)) {
            return $translationAPI->__('If the value of the field is `NULL` (or empty), replace it with the value provided under argument \'value\'', 'basic-directives');
        }
        return $translationAPI->__('If the value of the field is `NULL` (or empty), replace it with either the value provided under argument \'value\', or with a default value configured in the directive resolver', 'basic-directives');
    }
    public function getSchemaDirectiveArgs(\PoP\ComponentModel\TypeResolvers\TypeResolverInterface $typeResolver) : array
    {
        $translationAPI = \PoP\Translation\Facades\TranslationAPIFacade::getInstance();
        $instanceManager = \PoP\ComponentModel\Facades\Instances\InstanceManagerFacade::getInstance();
        /**
         * @var DefaultConditionEnum
         */
        $defaultConditionEnum = $instanceManager->getInstance(\PoPSchema\BasicDirectives\Enums\DefaultConditionEnum::class);
        $schemaDirectiveArg = [\PoP\ComponentModel\Schema\SchemaDefinition::ARGNAME_NAME => 'value', \PoP\ComponentModel\Schema\SchemaDefinition::ARGNAME_TYPE => \PoP\ComponentModel\Schema\SchemaDefinition::TYPE_MIXED, \PoP\ComponentModel\Schema\SchemaDefinition::ARGNAME_DESCRIPTION => $translationAPI->__('If the value of the field is `NULL`, replace it with the value from this argument', 'basic-directives')];
        $defaultValue = $this->getDefaultValue();
        if (\is_null($defaultValue)) {
            $schemaDirectiveArg[\PoP\ComponentModel\Schema\SchemaDefinition::ARGNAME_MANDATORY] = \true;
        } else {
            $schemaDirectiveArg[\PoP\ComponentModel\Schema\SchemaDefinition::ARGNAME_DEFAULT_VALUE] = $defaultValue;
        }
        return [$schemaDirectiveArg, [\PoP\ComponentModel\Schema\SchemaDefinition::ARGNAME_NAME => 'condition', \PoP\ComponentModel\Schema\SchemaDefinition::ARGNAME_TYPE => \PoP\ComponentModel\Schema\SchemaDefinition::TYPE_ENUM, \PoP\ComponentModel\Schema\SchemaDefinition::ARGNAME_DESCRIPTION => $translationAPI->__('Condition under which using the default value kicks in', 'basic-directives'), \PoP\ComponentModel\Schema\SchemaDefinition::ARGNAME_ENUM_NAME => $defaultConditionEnum->getName(), \PoP\ComponentModel\Schema\SchemaDefinition::ARGNAME_ENUM_VALUES => \PoP\ComponentModel\Schema\SchemaHelpers::convertToSchemaFieldArgEnumValueDefinitions($defaultConditionEnum->getValues()), \PoP\ComponentModel\Schema\SchemaDefinition::ARGNAME_DEFAULT_VALUE => $this->getDefaultCondition()]];
    }
    protected function getDefaultCondition() : string
    {
        return \PoPSchema\BasicDirectives\Enums\DefaultConditionEnum::IS_NULL;
    }
}
