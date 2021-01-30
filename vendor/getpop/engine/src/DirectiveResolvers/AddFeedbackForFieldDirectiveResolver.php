<?php

declare (strict_types=1);
namespace PoP\Engine\DirectiveResolvers;

use PoP\ComponentModel\Feedback\Tokens;
use PoP\Engine\Enums\FieldFeedbackTypeEnum;
use PoP\ComponentModel\Schema\SchemaHelpers;
use PoP\Engine\Enums\FieldFeedbackTargetEnum;
use PoP\ComponentModel\Schema\SchemaDefinition;
use PoP\ComponentModel\Directives\DirectiveTypes;
use PoP\Translation\Facades\TranslationAPIFacade;
use PoP\ComponentModel\TypeResolvers\TypeResolverInterface;
use PoP\ComponentModel\Facades\Instances\InstanceManagerFacade;
use PoP\ComponentModel\DirectiveResolvers\AbstractGlobalDirectiveResolver;
class AddFeedbackForFieldDirectiveResolver extends \PoP\ComponentModel\DirectiveResolvers\AbstractGlobalDirectiveResolver
{
    const DIRECTIVE_NAME = 'addFeedbackForField';
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
    public function isRepeatable() : bool
    {
        return \true;
    }
    /**
     * Execute always, even if validation is false
     *
     * @return void
     */
    public function needsIDsDataFieldsToExecute() : bool
    {
        return \false;
    }
    /**
     * Execute the directive
     *
     * @param TypeResolverInterface $typeResolver
     * @param array $idsDataFields
     * @param array $succeedingPipelineIDsDataFields
     * @param array $succeedingPipelineDirectiveResolverInstances
     * @param array $resultIDItems
     * @param array $unionDBKeyIDs
     * @param array $dbItems
     * @param array $previousDBItems
     * @param array $variables
     * @param array $messages
     * @param array $dbErrors
     * @param array $dbWarnings
     * @param array $dbDeprecations
     * @param array $schemaErrors
     * @param array $schemaWarnings
     * @param array $schemaDeprecations
     * @return void
     */
    public function resolveDirective(\PoP\ComponentModel\TypeResolvers\TypeResolverInterface $typeResolver, array &$idsDataFields, array &$succeedingPipelineIDsDataFields, array &$succeedingPipelineDirectiveResolverInstances, array &$resultIDItems, array &$unionDBKeyIDs, array &$dbItems, array &$previousDBItems, array &$variables, array &$messages, array &$dbErrors, array &$dbWarnings, array &$dbDeprecations, array &$dbNotices, array &$dbTraces, array &$schemaErrors, array &$schemaWarnings, array &$schemaDeprecations, array &$schemaNotices, array &$schemaTraces) : void
    {
        $type = $this->directiveArgsForSchema['type'];
        $target = $this->directiveArgsForSchema['target'];
        if ($target == \PoP\Engine\Enums\FieldFeedbackTargetEnum::DB) {
            $translationAPI = \PoP\Translation\Facades\TranslationAPIFacade::getInstance();
            foreach (\array_keys($idsDataFields) as $id) {
                // Use either the default value passed under param "value" or, if this is NULL, use a predefined value
                $expressions = $this->getExpressionsForResultItem($id, $variables, $messages);
                $resultItem = $resultIDItems[$id];
                list($resultItemValidDirective, $resultItemDirectiveName, $resultItemDirectiveArgs) = $this->dissectAndValidateDirectiveForResultItem($typeResolver, $resultItem, $variables, $expressions, $dbErrors, $dbWarnings, $dbDeprecations);
                // Check that the directive is valid. If it is not, $dbErrors will have the error already added
                if (\is_null($resultItemValidDirective)) {
                    continue;
                }
                // Take the default value from the directiveArgs
                $message = $resultItemDirectiveArgs['message'];
                // Check that the message was composed properly (eg: it didn't fail).
                // If it is not, $dbErrors will have the error already added
                if (\is_null($message)) {
                    $dbErrors[(string) $id][] = [\PoP\ComponentModel\Feedback\Tokens::PATH => [$this->directive], \PoP\ComponentModel\Feedback\Tokens::MESSAGE => $translationAPI->__('The message could not be composed. Check previous errors', 'engine')];
                    continue;
                }
                $feedbackMessageEntry = $this->getFeedbackMessageEntry($message);
                if ($type == \PoP\Engine\Enums\FieldFeedbackTypeEnum::WARNING) {
                    $dbWarnings[(string) $id][] = $feedbackMessageEntry;
                } elseif ($type == \PoP\Engine\Enums\FieldFeedbackTypeEnum::DEPRECATION) {
                    $dbDeprecations[(string) $id][] = $feedbackMessageEntry;
                } elseif ($type == \PoP\Engine\Enums\FieldFeedbackTypeEnum::NOTICE) {
                    $dbNotices[(string) $id][] = $feedbackMessageEntry;
                }
            }
        } elseif ($target == \PoP\Engine\Enums\FieldFeedbackTargetEnum::SCHEMA) {
            $message = $this->directiveArgsForSchema['message'];
            $feedbackMessageEntry = $this->getFeedbackMessageEntry($message);
            if ($type == \PoP\Engine\Enums\FieldFeedbackTypeEnum::WARNING) {
                $schemaWarnings[] = $feedbackMessageEntry;
            } elseif ($type == \PoP\Engine\Enums\FieldFeedbackTypeEnum::DEPRECATION) {
                $schemaDeprecations[] = $feedbackMessageEntry;
            } elseif ($type == \PoP\Engine\Enums\FieldFeedbackTypeEnum::NOTICE) {
                $schemaNotices[] = $feedbackMessageEntry;
            }
        }
    }
    protected function getFeedbackMessageEntry(string $message) : array
    {
        return [\PoP\ComponentModel\Feedback\Tokens::PATH => [$this->directive], \PoP\ComponentModel\Feedback\Tokens::MESSAGE => $message];
    }
    public function getSchemaDirectiveDescription(\PoP\ComponentModel\TypeResolvers\TypeResolverInterface $typeResolver) : ?string
    {
        $translationAPI = \PoP\Translation\Facades\TranslationAPIFacade::getInstance();
        return $translationAPI->__('Whenever a field is queried, add a feedback message to the response, of either type "warning", "deprecation" or "log"', 'engine');
    }
    public function getSchemaDirectiveArgs(\PoP\ComponentModel\TypeResolvers\TypeResolverInterface $typeResolver) : array
    {
        $translationAPI = \PoP\Translation\Facades\TranslationAPIFacade::getInstance();
        $instanceManager = \PoP\ComponentModel\Facades\Instances\InstanceManagerFacade::getInstance();
        /**
         * @var FieldFeedbackTypeEnum
         */
        $fieldFeedbackTypeEnum = $instanceManager->getInstance(\PoP\Engine\Enums\FieldFeedbackTypeEnum::class);
        /**
         * @var FieldFeedbackTargetEnum
         */
        $fieldFeedbackTargetEnum = $instanceManager->getInstance(\PoP\Engine\Enums\FieldFeedbackTargetEnum::class);
        return [[\PoP\ComponentModel\Schema\SchemaDefinition::ARGNAME_NAME => 'message', \PoP\ComponentModel\Schema\SchemaDefinition::ARGNAME_TYPE => \PoP\ComponentModel\Schema\SchemaDefinition::TYPE_STRING, \PoP\ComponentModel\Schema\SchemaDefinition::ARGNAME_DESCRIPTION => $translationAPI->__('The feedback message', 'engine'), \PoP\ComponentModel\Schema\SchemaDefinition::ARGNAME_MANDATORY => \true], [\PoP\ComponentModel\Schema\SchemaDefinition::ARGNAME_NAME => 'type', \PoP\ComponentModel\Schema\SchemaDefinition::ARGNAME_TYPE => \PoP\ComponentModel\Schema\SchemaDefinition::TYPE_ENUM, \PoP\ComponentModel\Schema\SchemaDefinition::ARGNAME_DESCRIPTION => $translationAPI->__('The type of feedback', 'engine'), \PoP\ComponentModel\Schema\SchemaDefinition::ARGNAME_ENUM_NAME => $fieldFeedbackTypeEnum->getName(), \PoP\ComponentModel\Schema\SchemaDefinition::ARGNAME_ENUM_VALUES => \PoP\ComponentModel\Schema\SchemaHelpers::convertToSchemaFieldArgEnumValueDefinitions($fieldFeedbackTypeEnum->getValues()), \PoP\ComponentModel\Schema\SchemaDefinition::ARGNAME_DEFAULT_VALUE => $this->getDefaultFeedbackType()], [\PoP\ComponentModel\Schema\SchemaDefinition::ARGNAME_NAME => 'target', \PoP\ComponentModel\Schema\SchemaDefinition::ARGNAME_TYPE => \PoP\ComponentModel\Schema\SchemaDefinition::TYPE_ENUM, \PoP\ComponentModel\Schema\SchemaDefinition::ARGNAME_DESCRIPTION => $translationAPI->__('The target for the feedback', 'engine'), \PoP\ComponentModel\Schema\SchemaDefinition::ARGNAME_ENUM_NAME => $fieldFeedbackTargetEnum->getName(), \PoP\ComponentModel\Schema\SchemaDefinition::ARGNAME_ENUM_VALUES => \PoP\ComponentModel\Schema\SchemaHelpers::convertToSchemaFieldArgEnumValueDefinitions($fieldFeedbackTargetEnum->getValues()), \PoP\ComponentModel\Schema\SchemaDefinition::ARGNAME_DEFAULT_VALUE => $this->getDefaultFeedbackTarget()]];
    }
    protected function getDefaultFeedbackType() : string
    {
        return \PoP\Engine\Enums\FieldFeedbackTypeEnum::NOTICE;
    }
    protected function getDefaultFeedbackTarget() : string
    {
        return \PoP\Engine\Enums\FieldFeedbackTargetEnum::SCHEMA;
    }
}
