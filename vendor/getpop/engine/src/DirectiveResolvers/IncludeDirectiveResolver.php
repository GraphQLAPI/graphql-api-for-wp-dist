<?php

declare (strict_types=1);
namespace PoP\Engine\DirectiveResolvers;

use PoP\ComponentModel\Schema\SchemaDefinition;
use PoP\Translation\Facades\TranslationAPIFacade;
use PoP\ComponentModel\TypeResolvers\PipelinePositions;
use PoP\ComponentModel\TypeResolvers\TypeResolverInterface;
use PoP\ComponentModel\DirectiveResolvers\AbstractGlobalDirectiveResolver;
class IncludeDirectiveResolver extends \PoP\ComponentModel\DirectiveResolvers\AbstractGlobalDirectiveResolver
{
    use FilterIDsSatisfyingConditionDirectiveResolverTrait;
    const DIRECTIVE_NAME = 'include';
    public static function getDirectiveName() : string
    {
        return self::DIRECTIVE_NAME;
    }
    /**
     * Place it after the validation and before it's added to $dbItems in the resolveAndMerge directive
     *
     * @return void
     */
    public function getPipelinePosition() : string
    {
        return \PoP\ComponentModel\TypeResolvers\PipelinePositions::AFTER_VALIDATE_BEFORE_RESOLVE;
    }
    public function resolveDirective(\PoP\ComponentModel\TypeResolvers\TypeResolverInterface $typeResolver, array &$idsDataFields, array &$succeedingPipelineIDsDataFields, array &$succeedingPipelineDirectiveResolverInstances, array &$resultIDItems, array &$unionDBKeyIDs, array &$dbItems, array &$previousDBItems, array &$variables, array &$messages, array &$dbErrors, array &$dbWarnings, array &$dbDeprecations, array &$dbNotices, array &$dbTraces, array &$schemaErrors, array &$schemaWarnings, array &$schemaDeprecations, array &$schemaNotices, array &$schemaTraces) : void
    {
        // Check the condition field. If it is satisfied, then keep those fields, otherwise remove them from the $idsDataFields in the upcoming stages of the pipeline
        $includeDataFieldsForIds = $this->getIdsSatisfyingCondition($typeResolver, $resultIDItems, $idsDataFields, $variables, $messages, $dbErrors, $dbWarnings, $dbDeprecations);
        $idsToRemove = \array_diff(\array_keys($idsDataFields), $includeDataFieldsForIds);
        $this->removeDataFieldsForIDs($idsDataFields, $idsToRemove, $succeedingPipelineIDsDataFields);
    }
    public function getSchemaDirectiveDescription(\PoP\ComponentModel\TypeResolvers\TypeResolverInterface $typeResolver) : ?string
    {
        $translationAPI = \PoP\Translation\Facades\TranslationAPIFacade::getInstance();
        return $translationAPI->__('Include the field value in the output only if the argument \'if\' evals to `true`', 'api');
    }
    public function getSchemaDirectiveArgs(\PoP\ComponentModel\TypeResolvers\TypeResolverInterface $typeResolver) : array
    {
        $translationAPI = \PoP\Translation\Facades\TranslationAPIFacade::getInstance();
        return [[\PoP\ComponentModel\Schema\SchemaDefinition::ARGNAME_NAME => 'if', \PoP\ComponentModel\Schema\SchemaDefinition::ARGNAME_TYPE => \PoP\ComponentModel\Schema\SchemaDefinition::TYPE_BOOL, \PoP\ComponentModel\Schema\SchemaDefinition::ARGNAME_DESCRIPTION => $translationAPI->__('Argument that must evaluate to `true` to include the field value in the output', 'api'), \PoP\ComponentModel\Schema\SchemaDefinition::ARGNAME_MANDATORY => \true]];
    }
}
