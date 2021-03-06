<?php

declare (strict_types=1);
namespace PoP\API\DirectiveResolvers;

use PoP\ComponentModel\Feedback\Tokens;
use PoP\ComponentModel\Schema\SchemaDefinition;
use PoP\ComponentModel\Directives\DirectiveTypes;
use PoP\ComponentModel\Schema\TypeCastingHelpers;
use PoP\Translation\Facades\TranslationAPIFacade;
use PoP\ComponentModel\TypeResolvers\UnionTypeHelpers;
use PoP\ComponentModel\TypeResolvers\TypeResolverInterface;
use PoP\ComponentModel\Facades\Instances\InstanceManagerFacade;
use PoP\ComponentModel\Facades\Schema\FieldQueryInterpreterFacade;
use PoP\ComponentModel\DirectiveResolvers\AbstractGlobalDirectiveResolver;
class CopyRelationalResultsDirectiveResolver extends \PoP\ComponentModel\DirectiveResolvers\AbstractGlobalDirectiveResolver
{
    const DIRECTIVE_NAME = 'copyRelationalResults';
    public static function getDirectiveName() : string
    {
        return self::DIRECTIVE_NAME;
    }
    /**
     * This is a "Scripting" type directive
     *
     * @return string
     */
    public function getDirectiveType() : string
    {
        return \PoP\ComponentModel\Directives\DirectiveTypes::SCRIPTING;
    }
    /**
     * Do not allow dynamic fields
     *
     * @return bool
     */
    protected function disableDynamicFieldsFromDirectiveArgs() : bool
    {
        return \true;
    }
    public function getSchemaDirectiveDescription(\PoP\ComponentModel\TypeResolvers\TypeResolverInterface $typeResolver) : ?string
    {
        $translationAPI = \PoP\Translation\Facades\TranslationAPIFacade::getInstance();
        return $translationAPI->__('Copy the data from a relational object (which is one level below) to the current object', 'component-model');
    }
    public function getSchemaDirectiveArgs(\PoP\ComponentModel\TypeResolvers\TypeResolverInterface $typeResolver) : array
    {
        $translationAPI = \PoP\Translation\Facades\TranslationAPIFacade::getInstance();
        return [[\PoP\ComponentModel\Schema\SchemaDefinition::ARGNAME_NAME => 'copyFromFields', \PoP\ComponentModel\Schema\SchemaDefinition::ARGNAME_TYPE => \PoP\ComponentModel\Schema\TypeCastingHelpers::makeArray(\PoP\ComponentModel\Schema\SchemaDefinition::TYPE_STRING), \PoP\ComponentModel\Schema\SchemaDefinition::ARGNAME_DESCRIPTION => $translationAPI->__('The fields in the relational object from which to copy the data', 'component-model'), \PoP\ComponentModel\Schema\SchemaDefinition::ARGNAME_MANDATORY => \true], [\PoP\ComponentModel\Schema\SchemaDefinition::ARGNAME_NAME => 'copyToFields', \PoP\ComponentModel\Schema\SchemaDefinition::ARGNAME_TYPE => \PoP\ComponentModel\Schema\TypeCastingHelpers::makeArray(\PoP\ComponentModel\Schema\SchemaDefinition::TYPE_STRING), \PoP\ComponentModel\Schema\SchemaDefinition::ARGNAME_DESCRIPTION => $translationAPI->__('The fields in the current object to which copy the data. Default value: Same fields provided through \'copyFromFields\' argument', 'component-model')], [\PoP\ComponentModel\Schema\SchemaDefinition::ARGNAME_NAME => 'keepRelationalIDs', \PoP\ComponentModel\Schema\SchemaDefinition::ARGNAME_TYPE => \PoP\ComponentModel\Schema\SchemaDefinition::TYPE_BOOL, \PoP\ComponentModel\Schema\SchemaDefinition::ARGNAME_DESCRIPTION => $translationAPI->__('Indicate if the properties are placed under the relational ID as keys (`true`) or as a one-dimensional array (`false`)', 'component-model'), \PoP\ComponentModel\Schema\SchemaDefinition::ARGNAME_DEFAULT_VALUE => \false]];
    }
    /**
     * Validate that the number of elements in the fields `copyToFields` and `copyFromFields` match one another
     *
     * @param TypeResolverInterface $typeResolver
     * @param array $directiveArgs
     * @param array $schemaErrors
     * @param array $schemaWarnings
     * @param array $schemaDeprecations
     * @return array
     */
    public function validateDirectiveArgumentsForSchema(\PoP\ComponentModel\TypeResolvers\TypeResolverInterface $typeResolver, string $directiveName, array $directiveArgs, array &$schemaErrors, array &$schemaWarnings, array &$schemaDeprecations) : array
    {
        $directiveArgs = parent::validateDirectiveArgumentsForSchema($typeResolver, $directiveName, $directiveArgs, $schemaErrors, $schemaWarnings, $schemaDeprecations);
        $translationAPI = \PoP\Translation\Facades\TranslationAPIFacade::getInstance();
        if (isset($directiveArgs['copyToFields'])) {
            $copyToFields = $directiveArgs['copyToFields'];
            $copyFromFields = $directiveArgs['copyFromFields'];
            $copyToFieldsCount = \count($copyToFields);
            $copyFromFieldsCount = \count($copyFromFields);
            // Validate that both arrays have the same number of elements
            if ($copyToFieldsCount > $copyFromFieldsCount) {
                $schemaWarnings[] = [\PoP\ComponentModel\Feedback\Tokens::PATH => [$this->directive], \PoP\ComponentModel\Feedback\Tokens::MESSAGE => \sprintf($translationAPI->__('Argument \'copyToFields\' has more elements than argument \'copyFromFields\', so the following fields have been ignored: \'%s\'', 'component-model'), \implode($translationAPI->__('\', \''), \array_slice($copyToFields, $copyFromFieldsCount)))];
            } elseif ($copyToFieldsCount < $copyFromFieldsCount) {
                $schemaWarnings[] = [\PoP\ComponentModel\Feedback\Tokens::PATH => [$this->directive], \PoP\ComponentModel\Feedback\Tokens::MESSAGE => \sprintf($translationAPI->__('Argument \'copyFromFields\' has more elements than argument \'copyToFields\', so the following fields will be copied to the destination object under their same field name: \'%s\'', 'component-model'), \implode($translationAPI->__('\', \''), \array_slice($copyFromFields, $copyToFieldsCount)))];
            }
        }
        return $directiveArgs;
    }
    /**
     * Copy the data under the relational object into the current object
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
     * @return void
     */
    public function resolveDirective(\PoP\ComponentModel\TypeResolvers\TypeResolverInterface $typeResolver, array &$idsDataFields, array &$succeedingPipelineIDsDataFields, array &$succeedingPipelineDirectiveResolverInstances, array &$resultIDItems, array &$unionDBKeyIDs, array &$dbItems, array &$previousDBItems, array &$variables, array &$messages, array &$dbErrors, array &$dbWarnings, array &$dbDeprecations, array &$dbNotices, array &$dbTraces, array &$schemaErrors, array &$schemaWarnings, array &$schemaDeprecations, array &$schemaNotices, array &$schemaTraces) : void
    {
        $translationAPI = \PoP\Translation\Facades\TranslationAPIFacade::getInstance();
        $instanceManager = \PoP\ComponentModel\Facades\Instances\InstanceManagerFacade::getInstance();
        $fieldQueryInterpreter = \PoP\ComponentModel\Facades\Schema\FieldQueryInterpreterFacade::getInstance();
        $copyFromFields = $this->directiveArgsForSchema['copyFromFields'];
        $copyToFields = $this->directiveArgsForSchema['copyToFields'] ?? $copyFromFields;
        $keepRelationalIDs = $this->directiveArgsForSchema['keepRelationalIDs'];
        // From the typeResolver, obtain under what type the data for the current object is stored
        $dbKey = $typeResolver->getTypeOutputName();
        // Copy the data from each of the relational object fields to the current object
        for ($i = 0; $i < \count($copyFromFields); $i++) {
            $copyFromField = $copyFromFields[$i];
            $copyToField = $copyToFields[$i] ?? $copyFromFields[$i];
            foreach ($idsDataFields as $id => $dataFields) {
                foreach ($dataFields['direct'] as $relationalField) {
                    // The data is stored under the field's output key
                    $relationalFieldOutputKey = $fieldQueryInterpreter->getFieldOutputKey($relationalField);
                    // Validate that the current object has `relationalField` property set
                    // Since we are fetching from a relational object (placed one level below in the iteration stack), the value could've been set only in a previous iteration
                    // Then it must be in $previousDBItems (it can't be in $dbItems unless set by chance, because the same IDs were involved for a possibly different query)
                    if (!\array_key_exists($relationalFieldOutputKey, $previousDBItems[$dbKey][(string) $id] ?? [])) {
                        if ($relationalFieldOutputKey != $relationalField) {
                            $dbErrors[(string) $id][] = [\PoP\ComponentModel\Feedback\Tokens::PATH => [$this->directive], \PoP\ComponentModel\Feedback\Tokens::MESSAGE => \sprintf($translationAPI->__('Field \'%s\' (under property \'%s\') hadn\'t been set for object with ID \'%s\', so no data can be copied', 'component-model'), $relationalField, $relationalFieldOutputKey, $id)];
                        } else {
                            $dbErrors[(string) $id][] = [\PoP\ComponentModel\Feedback\Tokens::PATH => [$this->directive], \PoP\ComponentModel\Feedback\Tokens::MESSAGE => \sprintf($translationAPI->__('Field \'%s\' hadn\'t been set for object with ID \'%s\', so no data can be copied', 'component-model'), $relationalField, $id)];
                        }
                        continue;
                    }
                    // If the destination field already exists, warn that it will be overriden
                    $isTargetValueInDBItems = \array_key_exists($copyToField, $dbItems[(string) $id] ?? []);
                    if ($isTargetValueInDBItems || \array_key_exists($copyToField, $previousDBItems[$dbKey][(string) $id] ?? [])) {
                        $dbWarnings[(string) $id][] = [\PoP\ComponentModel\Feedback\Tokens::PATH => [$this->directive], \PoP\ComponentModel\Feedback\Tokens::MESSAGE => \sprintf($translationAPI->__('The existing value for field \'%s\' from object with ID \'%s\' has been overriden: \'%s\'', 'component-model'), $copyToField, $id, $isTargetValueInDBItems ? $dbItems[(string) $id][$copyToField] : $previousDBItems[$dbKey][(string) $id][$copyToField])];
                    }
                    // Copy the properties into the array
                    $dbItems[(string) $id][$copyToField] = [];
                    // Obtain the DBKey under which the relationalField is stored in the database
                    $relationalTypeResolverClass = $typeResolver->resolveFieldTypeResolverClass($relationalField);
                    $relationalTypeResolver = $instanceManager->getInstance((string) $relationalTypeResolverClass);
                    $relationalDBKey = $relationalTypeResolver->getTypeOutputName();
                    $isUnionRelationalDBKey = \PoP\ComponentModel\TypeResolvers\UnionTypeHelpers::isUnionType($relationalDBKey);
                    if ($isUnionRelationalDBKey) {
                        // If the relational type data resolver is union, we must use the corresponding IDs from $unionDBKeyIDs, which contain the type in addition to the ID
                        $relationalIDs = $unionDBKeyIDs[$dbKey][(string) $id][$relationalFieldOutputKey];
                    } else {
                        // Otherwise, directly use the IDs from the object
                        $relationalIDs = $previousDBItems[$dbKey][(string) $id][$relationalFieldOutputKey];
                    }
                    // $relationalIDs can be an array of IDs, or a single item. In the latter case, copy the property directly. In the former one, copy it under an array,
                    // either with the ID of relational object as key, or as a normal one-dimension array using no particular keys
                    $copyStraight = \false;
                    if (!\is_array($relationalIDs)) {
                        $relationalIDs = [$relationalIDs];
                        $copyStraight = \true;
                    }
                    foreach ($relationalIDs as $relationalID) {
                        // Validate that the source field has been set.
                        if (!\array_key_exists($copyFromField, $previousDBItems[$relationalDBKey][(string) $relationalID] ?? [])) {
                            $dbErrors[(string) $id][] = [\PoP\ComponentModel\Feedback\Tokens::PATH => [$this->directive], \PoP\ComponentModel\Feedback\Tokens::MESSAGE => \sprintf($translationAPI->__('Field \'%s\' hadn\'t been set for object of entity \'%s\' and ID \'%s\', so no data can be copied', 'component-model'), $copyFromField, $relationalDBKey, $relationalID)];
                            continue;
                        }
                        if ($copyStraight) {
                            $dbItems[(string) $id][$copyToField] = $previousDBItems[$relationalDBKey][(string) $relationalID][$copyFromField];
                        } elseif ($keepRelationalIDs) {
                            $dbItems[(string) $id][$copyToField][(string) $relationalID] = $previousDBItems[$relationalDBKey][(string) $relationalID][$copyFromField];
                        } else {
                            $dbItems[(string) $id][$copyToField][] = $previousDBItems[$relationalDBKey][(string) $relationalID][$copyFromField];
                        }
                    }
                }
            }
        }
    }
}
