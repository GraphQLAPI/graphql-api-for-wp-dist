<?php

declare (strict_types=1);
namespace PoP\ComponentModel\TypeResolvers;

use PoP\ComponentModel\ErrorHandling\Error;
use PoP\FieldQuery\QueryUtils;
use PoP\FieldQuery\QuerySyntax;
use PoP\FieldQuery\QueryHelpers;
use PoP\ComponentModel\ErrorUtils;
use PoP\ComponentModel\Environment;
use PrefixedByPoP\League\Pipeline\PipelineBuilder;
use PoP\Hooks\Facades\HooksAPIFacade;
use PoP\ComponentModel\Feedback\Tokens;
use PoP\ComponentModel\Schema\SchemaHelpers;
use PoP\ComponentModel\Schema\FieldQueryUtils;
use PoP\ComponentModel\State\ApplicationState;
use PoP\ComponentModel\Schema\SchemaDefinition;
use PoP\Translation\Facades\TranslationAPIFacade;
use PoP\ComponentModel\TypeResolvers\FieldHelpers;
use PoP\ComponentModel\TypeResolvers\UnionTypeHelpers;
use PoP\ComponentModel\TypeResolvers\TypeResolverInterface;
use PoP\ComponentModel\FieldResolvers\FieldResolverInterface;
use PoP\ComponentModel\Facades\Engine\DataloadingEngineFacade;
use PoP\ComponentModel\Facades\Instances\InstanceManagerFacade;
use PoP\ComponentModel\Facades\Schema\FeedbackMessageStoreFacade;
use PoP\ComponentModel\Facades\Schema\FieldQueryInterpreterFacade;
use PoP\ComponentModel\DirectivePipeline\DirectivePipelineDecorator;
use PoP\ComponentModel\Facades\Schema\SchemaDefinitionServiceFacade;
use PoP\ComponentModel\DirectiveResolvers\DirectiveResolverInterface;
use PoP\ComponentModel\AttachableExtensions\AttachableExtensionGroups;
use PoP\ComponentModel\Facades\AttachableExtensions\AttachableExtensionManagerFacade;
abstract class AbstractTypeResolver implements \PoP\ComponentModel\TypeResolvers\TypeResolverInterface
{
    public const OPTION_VALIDATE_SCHEMA_ON_RESULT_ITEM = 'validateSchemaOnResultItem';
    /**
     * Cache of which fieldResolvers will process the given field
     *
     * @var FieldResolverInterface[]
     */
    protected $fieldResolvers = [];
    /**
     * @var array<string, array>
     */
    protected $schemaDefinition = null;
    /**
     * @var array<string, array>|null
     */
    protected $directiveNameClasses = null;
    /**
     * @var array<string, FieldResolverInterface>|null
     */
    protected $schemaFieldResolvers = null;
    /**
     * @var string[]|null
     */
    protected $typeResolverDecoratorClasses = null;
    /**
     * @var array<string, array>|null
     */
    protected $mandatoryDirectivesForFields = null;
    /**
     * @var array<string, array>|null
     */
    protected $precedingMandatoryDirectivesForDirectives = null;
    /**
     * @var array<string, array>|null
     */
    protected $succeedingMandatoryDirectivesForDirectives = null;
    /**
     * @var string[]|null
     */
    protected $interfaceClasses = null;
    /**
     * @var array<FieldInterfaceResolverInterface>|null
     */
    protected $interfaceResolverInstances = null;
    /**
     * @var array<string, array>
     */
    private $fieldDirectiveIDFields = [];
    /**
     * @var array<string, array>
     */
    private $fieldDirectivesFromFieldCache = [];
    /**
     * @var array<string, array>
     */
    private $dissectedFieldForSchemaCache = [];
    /**
     * @var array<string, array>
     */
    private $directiveResolverInstanceCache = [];
    /**
     * @var array<string, array>
     */
    private $fieldNamesResolvedByFieldResolver = [];
    public function getNamespace() : string
    {
        return \PoP\ComponentModel\Schema\SchemaHelpers::getSchemaNamespace(\get_called_class());
    }
    public final function getNamespacedTypeName() : string
    {
        return \PoP\ComponentModel\Schema\SchemaHelpers::getSchemaNamespacedName($this->getNamespace(), $this->getTypeName());
    }
    public final function getMaybeNamespacedTypeName() : string
    {
        $vars = \PoP\ComponentModel\State\ApplicationState::getVars();
        return $vars['namespace-types-and-interfaces'] ? $this->getNamespacedTypeName() : $this->getTypeName();
    }
    public function getTypeOutputName() : string
    {
        // Do not make the first letter lowercase, or namespaced names look bad
        return $this->getMaybeNamespacedTypeName();
    }
    public function getSchemaTypeDescription() : ?string
    {
        return null;
    }
    public function getDirectiveNameClasses() : array
    {
        if (\is_null($this->directiveNameClasses)) {
            $this->directiveNameClasses = $this->calculateFieldDirectiveNameClasses();
        }
        return $this->directiveNameClasses;
    }
    public function getIdFieldTypeResolverClass() : string
    {
        return \get_called_class();
    }
    public function getQualifiedDBObjectIDOrIDs($dbObjectIDOrIDs)
    {
        // Add the type before the ID
        $dbObjectIDs = \is_array($dbObjectIDOrIDs) ? $dbObjectIDOrIDs : [$dbObjectIDOrIDs];
        $qualifiedDBObjectIDs = \array_map(function ($id) {
            return \PoP\ComponentModel\TypeResolvers\UnionTypeHelpers::getDBObjectComposedTypeAndID($this, $id);
        }, $dbObjectIDs);
        return \is_array($dbObjectIDOrIDs) ? $qualifiedDBObjectIDs : $qualifiedDBObjectIDs[0];
    }
    public function qualifyDBObjectIDsToRemoveFromErrors() : bool
    {
        return \false;
    }
    /**
     * By default, the pipeline must always have directives:
     * 1. Validate: to validate that the schema, fieldNames, etc are supported, and filter them out if not
     * 2. ResolveAndMerge: to resolve the field and place the data into the DB object
     * Additionally to these 2, we can add other mandatory directives, such as:
     * setSelfAsExpression, cacheControl
     * Because it may be more convenient to add the directive or the class, there are 2 methods
     */
    protected function getMandatoryDirectives()
    {
        $fieldQueryInterpreter = \PoP\ComponentModel\Facades\Schema\FieldQueryInterpreterFacade::getInstance();
        $dataloadingEngine = \PoP\ComponentModel\Facades\Engine\DataloadingEngineFacade::getInstance();
        return \array_map(function ($directiveResolver) use($fieldQueryInterpreter) {
            return $fieldQueryInterpreter->listFieldDirective($directiveResolver::getDirectiveName());
        }, $dataloadingEngine->getMandatoryDirectiveResolvers());
    }
    /**
     * Validate and resolve the fieldDirectives into an array, each item containing:
     * 1. the directiveResolverInstance
     * 2. its fieldDirective
     * 3. the fields it affects
     *
     * @param array $fieldDirectives
     * @param array $fieldDirectiveFields
     * @param array $schemaErrors
     * @param array $schemaWarnings
     * @param array $schemaDeprecations
     * @param array $schemaNotices
     * @param array $schemaTraces
     * @return array
     */
    public function resolveDirectivesIntoPipelineData(array $fieldDirectives, array &$fieldDirectiveFields, bool $areNestedDirectives, array &$variables, array &$schemaErrors, array &$schemaWarnings, array &$schemaDeprecations, array &$schemaNotices, array &$schemaTraces) : array
    {
        $translationAPI = \PoP\Translation\Facades\TranslationAPIFacade::getInstance();
        /**
         * All directives are placed somewhere in the pipeline. There are 5 positions:
         * 1. At the beginning
         * 2. Before Validate directive
         * 3. Between the Validate and Resolve directives
         * 4. After the ResolveAndMerge directive
         * 4. At the end
         */
        $directiveInstancesByPosition = $fieldDirectivesByPosition = $directiveFieldsByPosition = [\PoP\ComponentModel\TypeResolvers\PipelinePositions::BEGINNING => [], \PoP\ComponentModel\TypeResolvers\PipelinePositions::BEFORE_VALIDATE => [], \PoP\ComponentModel\TypeResolvers\PipelinePositions::AFTER_VALIDATE_BEFORE_RESOLVE => [], \PoP\ComponentModel\TypeResolvers\PipelinePositions::AFTER_RESOLVE => [], \PoP\ComponentModel\TypeResolvers\PipelinePositions::END => []];
        // Resolve from directive into their actual object instance.
        $directiveSchemaErrors = $directiveSchemaWarnings = $directiveSchemaDeprecations = $directiveSchemaNotices = $directiveSchemaTraces = [];
        $directiveResolverInstanceData = $this->validateAndResolveInstances($fieldDirectives, $fieldDirectiveFields, $variables, $directiveSchemaErrors, $directiveSchemaWarnings, $directiveSchemaDeprecations, $directiveSchemaNotices, $directiveSchemaTraces);
        // If it is a root directives, then add the fields where they appear into the errors/warnings/deprecations
        if (!$areNestedDirectives) {
            // In the case of an error, Maybe prepend the field(s) containing the directive.
            // Eg: when the directive name doesn't exist: /?query=id<skipanga>
            foreach ($directiveSchemaErrors as $directiveSchemaError) {
                $directive = $directiveSchemaError[\PoP\ComponentModel\Feedback\Tokens::PATH][0];
                if ($directiveFields = $fieldDirectiveFields[$directive] ?? null) {
                    $fields = \implode($translationAPI->__(', '), $directiveFields);
                    $schemaErrors[] = [\PoP\ComponentModel\Feedback\Tokens::PATH => \array_merge([$fields], $directiveSchemaError[\PoP\ComponentModel\Feedback\Tokens::PATH]), \PoP\ComponentModel\Feedback\Tokens::MESSAGE => $directiveSchemaError[\PoP\ComponentModel\Feedback\Tokens::MESSAGE]];
                } else {
                    $schemaErrors[] = $directiveSchemaError;
                }
            }
            foreach ($directiveSchemaWarnings as $directiveSchemaWarning) {
                $directive = $directiveSchemaWarning[\PoP\ComponentModel\Feedback\Tokens::PATH][0];
                if ($directiveFields = $fieldDirectiveFields[$directive] ?? null) {
                    $fields = \implode($translationAPI->__(', '), $directiveFields);
                    $schemaWarnings[] = [\PoP\ComponentModel\Feedback\Tokens::PATH => \array_merge([$fields], $directiveSchemaWarning[\PoP\ComponentModel\Feedback\Tokens::PATH]), \PoP\ComponentModel\Feedback\Tokens::MESSAGE => $directiveSchemaWarning[\PoP\ComponentModel\Feedback\Tokens::MESSAGE]];
                } else {
                    $schemaWarnings[] = $directiveSchemaWarning;
                }
            }
            foreach ($directiveSchemaDeprecations as $directiveSchemaDeprecation) {
                $directive = $directiveSchemaDeprecation[\PoP\ComponentModel\Feedback\Tokens::PATH][0];
                if ($directiveFields = $fieldDirectiveFields[$directive] ?? null) {
                    $fields = \implode($translationAPI->__(', '), $directiveFields);
                    $schemaDeprecations[] = [\PoP\ComponentModel\Feedback\Tokens::PATH => \array_merge([$fields], $directiveSchemaDeprecation[\PoP\ComponentModel\Feedback\Tokens::PATH]), \PoP\ComponentModel\Feedback\Tokens::MESSAGE => $directiveSchemaDeprecation[\PoP\ComponentModel\Feedback\Tokens::MESSAGE]];
                } else {
                    $schemaDeprecations[] = $directiveSchemaDeprecation;
                }
            }
            foreach ($directiveSchemaNotices as $directiveSchemaNotice) {
                $directive = $directiveSchemaNotice[\PoP\ComponentModel\Feedback\Tokens::PATH][0];
                if ($directiveFields = $fieldDirectiveFields[$directive] ?? null) {
                    $fields = \implode($translationAPI->__(', '), $directiveFields);
                    $schemaNotices[] = [\PoP\ComponentModel\Feedback\Tokens::PATH => \array_merge([$fields], $directiveSchemaNotice[\PoP\ComponentModel\Feedback\Tokens::PATH]), \PoP\ComponentModel\Feedback\Tokens::MESSAGE => $directiveSchemaNotice[\PoP\ComponentModel\Feedback\Tokens::MESSAGE]];
                } else {
                    $schemaNotices[] = $directiveSchemaNotice;
                }
            }
            foreach ($directiveSchemaTraces as $directiveSchemaTrace) {
                $directive = $directiveSchemaTrace[\PoP\ComponentModel\Feedback\Tokens::PATH][0];
                if ($directiveFields = $fieldDirectiveFields[$directive] ?? null) {
                    $fields = \implode($translationAPI->__(', '), $directiveFields);
                    $schemaTraces[] = [\PoP\ComponentModel\Feedback\Tokens::PATH => \array_merge([$fields], $directiveSchemaTrace[\PoP\ComponentModel\Feedback\Tokens::PATH]), \PoP\ComponentModel\Feedback\Tokens::MESSAGE => $directiveSchemaTrace[\PoP\ComponentModel\Feedback\Tokens::MESSAGE]];
                } else {
                    $schemaTraces[] = $directiveSchemaTrace;
                }
            }
        } else {
            $schemaErrors = \array_merge($schemaErrors, $directiveSchemaErrors);
            $schemaWarnings = \array_merge($schemaWarnings, $directiveSchemaWarnings);
            $schemaDeprecations = \array_merge($schemaDeprecations, $directiveSchemaDeprecations);
            $schemaNotices = \array_merge($schemaNotices, $directiveSchemaNotices);
            $schemaTraces = \array_merge($schemaTraces, $directiveSchemaTraces);
        }
        // Create an array with the dataFields affected by each directive, in order in which they will be invoked
        foreach ($directiveResolverInstanceData as $instanceID => $directiveResolverInstanceData) {
            // Add the directive in its required position in the pipeline, and retrieve what fields it will process
            $directiveResolverInstance = $directiveResolverInstanceData['instance'];
            $pipelinePosition = $directiveResolverInstance->getPipelinePosition();
            $directiveInstancesByPosition[$pipelinePosition][] = $directiveResolverInstance;
            $fieldDirectivesByPosition[$pipelinePosition][] = $directiveResolverInstanceData['fieldDirective'];
            $directiveFieldsByPosition[$pipelinePosition][] = $directiveResolverInstanceData['fields'];
        }
        // Once we have them ordered, we can simply discard the positions, keep only the values
        // Each item has 3 elements: the directiveResolverInstance, its fieldDirective, and the fields it affects
        $pipelineData = [];
        foreach ($directiveInstancesByPosition as $position => $directiveResolverInstances) {
            for ($i = 0; $i < \count($directiveResolverInstances); $i++) {
                $pipelineData[] = ['instance' => $directiveResolverInstances[$i], 'fieldDirective' => $fieldDirectivesByPosition[$position][$i], 'fields' => $directiveFieldsByPosition[$position][$i]];
            }
        }
        return $pipelineData;
    }
    public function getDirectivePipeline(array $directiveResolverInstances) : \PoP\ComponentModel\DirectivePipeline\DirectivePipelineDecorator
    {
        // From the ordered directives, create the pipeline
        $pipelineBuilder = new \PrefixedByPoP\League\Pipeline\PipelineBuilder();
        foreach ($directiveResolverInstances as $directiveResolverInstance) {
            $pipelineBuilder->add($directiveResolverInstance);
        }
        $directivePipeline = new \PoP\ComponentModel\DirectivePipeline\DirectivePipelineDecorator($pipelineBuilder->build());
        return $directivePipeline;
    }
    protected function validateAndResolveInstances(array $fieldDirectives, array $fieldDirectiveFields, array &$variables, array &$schemaErrors, array &$schemaWarnings, array &$schemaDeprecations, array &$schemaNotices, array &$schemaTraces) : array
    {
        $translationAPI = \PoP\Translation\Facades\TranslationAPIFacade::getInstance();
        $fieldQueryInterpreter = \PoP\ComponentModel\Facades\Schema\FieldQueryInterpreterFacade::getInstance();
        // Check if, once a directive fails, the continuing directives must execute or not
        $stopDirectivePipelineExecutionIfDirectiveFailed = \PoP\ComponentModel\Environment::stopDirectivePipelineExecutionIfDirectiveFailed();
        if ($stopDirectivePipelineExecutionIfDirectiveFailed) {
            $stopDirectivePipelineExecutionPlaceholder = $translationAPI->__('Because directive \'%s\' failed, the succeeding directives in the pipeline have not been executed', 'pop-component-model');
        }
        $instances = [];
        // Count how many times each directive is added
        $directiveFieldTrack = [];
        $directiveResolverInstanceFields = [];
        for ($i = 0; $i < \count($fieldDirectives); $i++) {
            // Because directives can be repeated inside a field (eg: <resize(50%),resize(50%)>),
            // then we deal with 2 variables:
            // 1. $fieldDirective: the actual directive
            // 2. $enqueuedFieldDirective: how it was added to the array
            // For retrieving the idsDataFields for the directive, we'll use $enqueuedFieldDirective, since under this entry we stored all the data in the previous functions
            // For everything else, we use $fieldDirective
            $enqueuedFieldDirective = $fieldDirectives[$i];
            // Check if it is a repeated directive: if it has the "|" symbol
            $counterSeparatorPos = \PoP\FieldQuery\QueryUtils::findLastSymbolPosition($enqueuedFieldDirective, \PoP\ComponentModel\TypeResolvers\FieldSymbols::REPEATED_DIRECTIVE_COUNTER_SEPARATOR, [\PoP\FieldQuery\QuerySyntax::SYMBOL_FIELDARGS_OPENING, \PoP\FieldQuery\QuerySyntax::SYMBOL_FIELDDIRECTIVE_OPENING], [\PoP\FieldQuery\QuerySyntax::SYMBOL_FIELDARGS_CLOSING, \PoP\FieldQuery\QuerySyntax::SYMBOL_FIELDDIRECTIVE_CLOSING], \PoP\FieldQuery\QuerySyntax::SYMBOL_FIELDARGS_ARGVALUESTRING_OPENING, \PoP\FieldQuery\QuerySyntax::SYMBOL_FIELDARGS_ARGVALUESTRING_CLOSING);
            $isRepeatedFieldDirective = $counterSeparatorPos !== \false;
            if ($isRepeatedFieldDirective) {
                // Remove the "|counter" bit from the fieldDirective
                $fieldDirective = \substr($enqueuedFieldDirective, 0, $counterSeparatorPos);
            } else {
                $fieldDirective = $enqueuedFieldDirective;
            }
            $fieldDirectiveResolverInstances = $this->getDirectiveResolverInstanceForDirective($fieldDirective, $fieldDirectiveFields[$enqueuedFieldDirective], $variables);
            $directiveName = $fieldQueryInterpreter->getFieldDirectiveName($fieldDirective);
            // If there is no directive with this name, show an error and skip it
            if (\is_null($fieldDirectiveResolverInstances)) {
                $schemaErrors[] = [\PoP\ComponentModel\Feedback\Tokens::PATH => [$fieldDirective], \PoP\ComponentModel\Feedback\Tokens::MESSAGE => \sprintf($translationAPI->__('No DirectiveResolver resolves directive with name \'%s\'', 'pop-component-model'), $directiveName)];
                if ($stopDirectivePipelineExecutionIfDirectiveFailed) {
                    $schemaErrors[] = [\PoP\ComponentModel\Feedback\Tokens::PATH => [$fieldDirective], \PoP\ComponentModel\Feedback\Tokens::MESSAGE => \sprintf($stopDirectivePipelineExecutionPlaceholder, $fieldDirective)];
                    break;
                }
                continue;
            }
            $directiveArgs = $fieldQueryInterpreter->extractStaticDirectiveArguments($fieldDirective);
            if (empty($fieldDirectiveResolverInstances)) {
                $schemaErrors[] = [\PoP\ComponentModel\Feedback\Tokens::PATH => [$fieldDirective], \PoP\ComponentModel\Feedback\Tokens::MESSAGE => \sprintf($translationAPI->__('No DirectiveResolver processes directive with name \'%s\' and arguments \'%s\' in field(s) \'%s\'', 'pop-component-model'), $directiveName, \json_encode($directiveArgs), \implode($translationAPI->__('\', \'', 'pop-component-model'), $fieldDirectiveFields[$fieldDirective]))];
                if ($stopDirectivePipelineExecutionIfDirectiveFailed) {
                    $schemaErrors[] = [\PoP\ComponentModel\Feedback\Tokens::PATH => [$fieldDirective], \PoP\ComponentModel\Feedback\Tokens::MESSAGE => \sprintf($stopDirectivePipelineExecutionPlaceholder, $fieldDirective)];
                    break;
                }
                continue;
            }
            foreach ($fieldDirectiveFields[$enqueuedFieldDirective] as $field) {
                $directiveResolverInstance = $fieldDirectiveResolverInstances[$field];
                if (\is_null($directiveResolverInstance)) {
                    $schemaErrors[] = [\PoP\ComponentModel\Feedback\Tokens::PATH => [$fieldDirective], \PoP\ComponentModel\Feedback\Tokens::MESSAGE => \sprintf($translationAPI->__('No DirectiveResolver processes directive with name \'%s\' and arguments \'%s\' in field \'%s\'', 'pop-component-model'), $directiveName, \json_encode($directiveArgs), $field)];
                    if ($stopDirectivePipelineExecutionIfDirectiveFailed) {
                        $schemaErrors[] = [\PoP\ComponentModel\Feedback\Tokens::PATH => [$fieldDirective], \PoP\ComponentModel\Feedback\Tokens::MESSAGE => \sprintf($stopDirectivePipelineExecutionPlaceholder, $fieldDirective)];
                        break;
                    }
                    continue;
                }
                // Consolidate the same directiveResolverInstances for different fields,
                // as to do the validation only once on each of them
                $instanceID = \get_class($directiveResolverInstance) . $enqueuedFieldDirective;
                if (!isset($directiveResolverInstanceFields[$instanceID])) {
                    $directiveResolverInstanceFields[$instanceID]['fieldDirective'] = $fieldDirective;
                    $directiveResolverInstanceFields[$instanceID]['enqueuedFieldDirective'] = $enqueuedFieldDirective;
                    $directiveResolverInstanceFields[$instanceID]['instance'] = $directiveResolverInstance;
                }
                $directiveResolverInstanceFields[$instanceID]['fields'][] = $field;
            }
        }
        // Validate all the directiveResolvers in the field
        foreach ($directiveResolverInstanceFields as $instanceID => $instanceData) {
            $fieldDirective = $instanceData['fieldDirective'];
            $enqueuedFieldDirective = $instanceData['enqueuedFieldDirective'];
            $directiveResolverInstance = $instanceData['instance'];
            $directiveResolverFields = $instanceData['fields'];
            // If the enqueued and the fieldDirective are different, it's because it is a repeated one
            $isRepeatedFieldDirective = $fieldDirective != $enqueuedFieldDirective;
            // If it is a repeated directive, no need to do the validation again
            if ($isRepeatedFieldDirective) {
                // If there is an existing error, then skip adding this resolver to the pipeline
                if (!empty(\array_filter($schemaErrors, function ($schemaError) use($fieldDirective) {
                    return $schemaError[\PoP\ComponentModel\Feedback\Tokens::PATH][0] == $fieldDirective;
                }))) {
                    continue;
                }
            } else {
                // Validate schema (eg of error in schema: ?query=posts<include(if:this-field-doesnt-exist())>)
                $fieldSchemaErrors = [];
                list($validFieldDirective, $directiveName, $directiveArgs, ) = $directiveResolverInstance->dissectAndValidateDirectiveForSchema($this, $fieldDirectiveFields, $variables, $fieldSchemaErrors, $schemaWarnings, $schemaDeprecations, $schemaNotices, $schemaTraces);
                if ($fieldSchemaErrors) {
                    $schemaErrors = \array_merge($schemaErrors, $fieldSchemaErrors);
                    // Because there were schema errors, skip this directive
                    if ($stopDirectivePipelineExecutionIfDirectiveFailed) {
                        $schemaErrors[] = [\PoP\ComponentModel\Feedback\Tokens::PATH => [$fieldDirective], \PoP\ComponentModel\Feedback\Tokens::MESSAGE => \sprintf($stopDirectivePipelineExecutionPlaceholder, $fieldDirective)];
                        break;
                    }
                    continue;
                }
                // Validate against the directiveResolver
                if ($maybeErrors = $directiveResolverInstance->resolveSchemaValidationErrorDescriptions($this, $directiveName, $directiveArgs)) {
                    foreach ($maybeErrors as $error) {
                        $schemaErrors[] = [\PoP\ComponentModel\Feedback\Tokens::PATH => [$fieldDirective], \PoP\ComponentModel\Feedback\Tokens::MESSAGE => $error];
                    }
                    if ($stopDirectivePipelineExecutionIfDirectiveFailed) {
                        $schemaErrors[] = [\PoP\ComponentModel\Feedback\Tokens::PATH => [$fieldDirective], \PoP\ComponentModel\Feedback\Tokens::MESSAGE => \sprintf($stopDirectivePipelineExecutionPlaceholder, $fieldDirective)];
                        break;
                    }
                    continue;
                }
                // Check for warnings
                if ($warningDescription = $directiveResolverInstance->resolveSchemaDirectiveWarningDescription($this)) {
                    $schemaWarnings[] = [\PoP\ComponentModel\Feedback\Tokens::PATH => [$fieldDirective], \PoP\ComponentModel\Feedback\Tokens::MESSAGE => $warningDescription];
                }
                // Check for deprecations
                if ($deprecationDescription = $directiveResolverInstance->getSchemaDirectiveDeprecationDescription($this)) {
                    $schemaDeprecations[] = [\PoP\ComponentModel\Feedback\Tokens::PATH => [$fieldDirective], \PoP\ComponentModel\Feedback\Tokens::MESSAGE => $deprecationDescription];
                }
            }
            // Validate if the directive can be executed multiple times on each field
            if (!$directiveResolverInstance->isRepeatable()) {
                // Check if the directive is already processing any of the fields
                $directiveName = $fieldQueryInterpreter->getFieldDirectiveName($fieldDirective);
                $alreadyProcessingFields = \array_intersect($directiveFieldTrack[$directiveName] ?? [], $directiveResolverFields);
                $directiveFieldTrack[$directiveName] = \array_unique(\array_merge($directiveFieldTrack[$directiveName] ?? [], $directiveResolverFields));
                if ($alreadyProcessingFields) {
                    // Remove the fields from this iteration, and add an error
                    $directiveResolverFields = \array_diff($directiveResolverFields, $alreadyProcessingFields);
                    $schemaErrors[] = [\PoP\ComponentModel\Feedback\Tokens::PATH => [$fieldDirective], \PoP\ComponentModel\Feedback\Tokens::MESSAGE => \sprintf($translationAPI->__('Directive \'%s\' can be executed only once for field(s) \'%s\'', 'component-model'), $fieldDirective, \implode('\', \'', $alreadyProcessingFields))];
                    if ($stopDirectivePipelineExecutionIfDirectiveFailed) {
                        $schemaErrors[] = [\PoP\ComponentModel\Feedback\Tokens::PATH => [$fieldDirective], \PoP\ComponentModel\Feedback\Tokens::MESSAGE => \sprintf($stopDirectivePipelineExecutionPlaceholder, $fieldDirective)];
                        break;
                    }
                    // If after removing the duplicated fields there are still others, process them
                    // Otherwise, skip
                    if (!$directiveResolverFields) {
                        continue;
                    }
                }
            }
            // Directive is valid. Add it under its instanceID, which enables to add fields under the same directiveResolverInstance
            $instances[$instanceID]['instance'] = $directiveResolverInstance;
            $instances[$instanceID]['fieldDirective'] = $fieldDirective;
            $instances[$instanceID]['fields'] = $directiveResolverFields;
        }
        return $instances;
    }
    public function getDirectiveResolverInstanceForDirective(string $fieldDirective, array $fieldDirectiveFields, array &$variables) : ?array
    {
        $fieldQueryInterpreter = \PoP\ComponentModel\Facades\Schema\FieldQueryInterpreterFacade::getInstance();
        $directiveName = $fieldQueryInterpreter->getFieldDirectiveName($fieldDirective);
        $directiveArgs = $fieldQueryInterpreter->extractStaticDirectiveArguments($fieldDirective);
        $directiveNameClasses = $this->getDirectiveNameClasses();
        $directiveClasses = $directiveNameClasses[$directiveName];
        if (\is_null($directiveClasses)) {
            return null;
        }
        // Calculate directives per field
        $fieldDirectiveResolverInstances = [];
        foreach ($fieldDirectiveFields as $field) {
            // Check that at least one class which deals with this directiveName can satisfy the directive (for instance, validating that a required directiveArg is present)
            $fieldName = $fieldQueryInterpreter->getFieldName($field);
            foreach ($directiveClasses as $directiveClass) {
                $directiveSupportedFieldNames = $directiveClass::getFieldNamesToApplyTo();
                // If this field is not supported by the directive, skip
                if ($directiveSupportedFieldNames && !\in_array($fieldName, $directiveSupportedFieldNames)) {
                    continue;
                }
                // Get the instance from the cache if it exists, or create it if not
                if (!isset($this->directiveResolverInstanceCache[$directiveClass][$fieldDirective])) {
                    $this->directiveResolverInstanceCache[$directiveClass][$fieldDirective] = new $directiveClass($fieldDirective);
                }
                $maybeDirectiveResolverInstance = $this->directiveResolverInstanceCache[$directiveClass][$fieldDirective];
                // Check if this instance can process the directive
                if ($maybeDirectiveResolverInstance->resolveCanProcess($this, $directiveName, $directiveArgs, $field, $variables)) {
                    $fieldDirectiveResolverInstances[$field] = $maybeDirectiveResolverInstance;
                    break;
                }
            }
        }
        return $fieldDirectiveResolverInstances;
    }
    /**
     * By default, do nothing
     *
     * @param string $field
     * @param array<string, mixed> $fieldArgs
     * @param array $schemaErrors
     * @param array $schemaWarnings
     * @param array $schemaDeprecations
     * @return array
     */
    public function validateFieldArgumentsForSchema(string $field, array $fieldArgs, array &$schemaErrors, array &$schemaWarnings, array &$schemaDeprecations) : array
    {
        return $fieldArgs;
    }
    protected function getIDsToQuery(array $ids_data_fields)
    {
        return \array_keys($ids_data_fields);
    }
    protected function getUnresolvedResultItemIDError($resultItemID)
    {
        $translationAPI = \PoP\Translation\Facades\TranslationAPIFacade::getInstance();
        return new \PoP\ComponentModel\ErrorHandling\Error('unresolved-resultitem-id', \sprintf($translationAPI->__('The DataLoader can\'t load data for object of type \'%s\' with ID \'%s\'', 'pop-component-model'), $this->getTypeOutputName(), $resultItemID));
    }
    public function fillResultItems(array $ids_data_fields, array &$unionDBKeyIDs, array &$dbItems, array &$previousDBItems, array &$variables, array &$messages, array &$dbErrors, array &$dbWarnings, array &$dbDeprecations, array &$dbNotices, array &$dbTraces, array &$schemaErrors, array &$schemaWarnings, array &$schemaDeprecations, array &$schemaNotices, array &$schemaTraces) : array
    {
        $instanceManager = \PoP\ComponentModel\Facades\Instances\InstanceManagerFacade::getInstance();
        $translationAPI = \PoP\Translation\Facades\TranslationAPIFacade::getInstance();
        // Obtain the data for the required object IDs
        $resultIDItems = [];
        $ids = $this->getIDsToQuery($ids_data_fields);
        $typeDataLoaderClass = $this->getTypeDataLoaderClass();
        $typeDataLoader = $instanceManager->getInstance($typeDataLoaderClass);
        foreach ($typeDataLoader->getObjects($ids) as $resultItem) {
            $resultItemID = $this->getID($resultItem);
            // If the UnionTypeResolver doesn't have a TypeResolver to process this element, the ID will be null, and an error will be show below
            if (\is_null($resultItemID)) {
                continue;
            }
            $resultIDItems[$resultItemID] = $resultItem;
        }
        // Show an error for all resultItems that couldn't be processed
        $resolvedResultItemIDs = $this->getIDsToQuery($resultIDItems);
        $unresolvedResultItemIDs = [];
        foreach (\array_diff($ids, $resolvedResultItemIDs) as $unresolvedResultItemID) {
            $error = $this->getUnresolvedResultItemIDError($unresolvedResultItemID);
            // If a UnionTypeResolver fails to load an object, the fields will be NULL
            $failedFields = $ids_data_fields[$unresolvedResultItemID]['direct'] ?? [];
            // Add in $schemaErrors instead of $dbErrors because in the latter one it will attempt to fetch the ID from the object, which it can't do
            $schemaErrors[] = [\PoP\ComponentModel\Feedback\Tokens::PATH => [\implode($translationAPI->__('\', \''), $failedFields)], \PoP\ComponentModel\Feedback\Tokens::MESSAGE => $error->getErrorMessage()];
            // Indicate that this ID must be removed from the results
            $unresolvedResultItemIDs[] = $unresolvedResultItemID;
        }
        // Remove all the IDs that failed from the elements to process, so it doesn't show a "Corrupted Data" error
        // Because these are IDs (eg: 223) and $ids_data_fields contains qualified or typed IDs (eg: post/223), we must convert them first
        if ($unresolvedResultItemIDs) {
            if ($this->qualifyDBObjectIDsToRemoveFromErrors()) {
                $unresolvedResultItemIDs = $this->getQualifiedDBObjectIDOrIDs($unresolvedResultItemIDs);
            }
            $ids_data_fields = \array_filter($ids_data_fields, function ($id) use($unresolvedResultItemIDs) {
                return !\in_array($id, $unresolvedResultItemIDs);
            }, \ARRAY_FILTER_USE_KEY);
        }
        // Enqueue the items
        $this->enqueueFillingResultItemsFromIDs($ids_data_fields);
        // Process them
        $this->processFillingResultItemsFromIDs($resultIDItems, $unionDBKeyIDs, $dbItems, $previousDBItems, $variables, $messages, $dbErrors, $dbWarnings, $dbDeprecations, $dbNotices, $dbTraces, $schemaErrors, $schemaWarnings, $schemaDeprecations, $schemaNotices, $schemaTraces);
        return $resultIDItems;
    }
    /**
     * Given an array of directives, attach, before and after each of them, their own mandatory directives
     * Eg: a directive `@validateDoesUserHaveCapability` must be preceded by a directive `@validateIsUserLoggedIn`
     *
     * The process is recursive: mandatory directives can have their own mandatory directives, and these are added too
     *
     * @param array $directives
     * @return array
     */
    protected function addMandatoryDirectivesForDirectives(array $directives) : array
    {
        $fieldQueryInterpreter = \PoP\ComponentModel\Facades\Schema\FieldQueryInterpreterFacade::getInstance();
        $precedingMandatoryDirectivesForDirectives = $this->getAllPrecedingMandatoryDirectivesForDirectives();
        $succeedingMandatoryDirectivesForDirectives = $this->getAllSucceedingMandatoryDirectivesForDirectives();
        $allDirectives = [];
        foreach ($directives as $directive) {
            $directiveName = $fieldQueryInterpreter->getDirectiveName($directive);
            // Add preceding mandatory directives
            if ($mandatoryDirectivesForDirective = \array_merge($precedingMandatoryDirectivesForDirectives[\PoP\ComponentModel\TypeResolvers\FieldSymbols::ANY_FIELD] ?? [], $precedingMandatoryDirectivesForDirectives[$directiveName] ?? [])) {
                $allDirectives = \array_merge($allDirectives, $this->addMandatoryDirectivesForDirectives($mandatoryDirectivesForDirective));
            }
            // Add the directive
            $allDirectives[] = $directive;
            // Add succeeding mandatory directives
            if ($mandatoryDirectivesForDirective = \array_merge($succeedingMandatoryDirectivesForDirectives[\PoP\ComponentModel\TypeResolvers\FieldSymbols::ANY_FIELD] ?? [], $succeedingMandatoryDirectivesForDirectives[$directiveName] ?? [])) {
                $allDirectives = \array_merge($allDirectives, $this->addMandatoryDirectivesForDirectives($mandatoryDirectivesForDirective));
            }
        }
        return $allDirectives;
    }
    /**
     * Execute a hook to allow to disable directives (eg: to implement a private schema)
     *
     * @param array $directiveNameClasses
     * @return array
     */
    protected function filterDirectiveNameClasses(array $directiveNameClasses) : array
    {
        // Execute a hook, allowing to filter them out (eg: removing fieldNames from a private schema)
        $hooksAPI = \PoP\Hooks\Facades\HooksAPIFacade::getInstance();
        $instanceManager = \PoP\ComponentModel\Facades\Instances\InstanceManagerFacade::getInstance();
        return \array_filter($directiveNameClasses, function ($directiveName) use($hooksAPI, $directiveNameClasses, $instanceManager) {
            $directiveResolverClasses = $directiveNameClasses[$directiveName];
            foreach ($directiveResolverClasses as $directiveResolverClass) {
                /** @var DirectiveResolverInterface */
                $directiveResolver = $instanceManager->getInstance($directiveResolverClass);
                // Execute 2 filters: a generic one, and a specific one
                if ($hooksAPI->applyFilters(\PoP\ComponentModel\TypeResolvers\HookHelpers::getHookNameToFilterDirective(), \true, $this, $directiveResolver, $directiveName)) {
                    return $hooksAPI->applyFilters(\PoP\ComponentModel\TypeResolvers\HookHelpers::getHookNameToFilterDirective($directiveName), \true, $this, $directiveResolver, $directiveName);
                }
                return \false;
            }
            return \true;
        }, \ARRAY_FILTER_USE_KEY);
    }
    /**
     * Is this a Union Type? By default it is not
     *
     * @return bool
     */
    public function isUnionType() : bool
    {
        return \false;
    }
    /**
     * Collect all directives for all fields, and then build a single directive pipeline for all fields,
     * including all directives, even if they don't apply to all fields
     * Eg: id|title<skip>|excerpt<translate> will produce a pipeline [Skip, Translate] where they apply
     * to different fields. After producing the pipeline, add the mandatory items
     *
     * @param array $ids_data_fields
     * @param array $resultIDItems
     * @return void
     */
    public function enqueueFillingResultItemsFromIDs(array $ids_data_fields)
    {
        $fieldQueryInterpreter = \PoP\ComponentModel\Facades\Schema\FieldQueryInterpreterFacade::getInstance();
        $instanceManager = \PoP\ComponentModel\Facades\Instances\InstanceManagerFacade::getInstance();
        // Watch out! The UnionType must obtain the mandatoryDirectivesForFields
        // from each of its target types!
        // This is mandatory, because the UnionType doesn't have fields by itself.
        // Otherwise, TypeResolverDecorators can't have their defined ACL rules
        // work when querying a union type (eg: "customPosts")
        if ($this->isUnionType()) {
            $targetTypeResolverClassMandatoryDirectivesForFields = [];
            $targetTypeResolverClasses = $this->getTargetTypeResolverClasses();
            foreach ($targetTypeResolverClasses as $targetTypeResolverClass) {
                $targetTypeResolver = $instanceManager->getInstance($targetTypeResolverClass);
                $targetTypeResolverClassMandatoryDirectivesForFields[$targetTypeResolverClass] = $targetTypeResolver->getAllMandatoryDirectivesForFields();
            }
            // If the type data resolver is union, the dbKey where the value is stored
            // is contained in the ID itself, with format dbKey/ID.
            // Remove this information, and get purely the ID
            $resultItemIDs = \array_map(function ($composedID) {
                list($dbKey, $id) = \PoP\ComponentModel\TypeResolvers\UnionTypeHelpers::extractDBObjectTypeAndID($composedID);
                return $id;
            }, \array_keys($ids_data_fields));
            $resultItemIDTargetTypeResolvers = $this->getResultItemIDTargetTypeResolvers($resultItemIDs);
        } else {
            $mandatoryDirectivesForFields = $this->getAllMandatoryDirectivesForFields();
        }
        $mandatorySystemDirectives = $this->getMandatoryDirectives();
        $fieldDirectiveCounter = [];
        foreach ($ids_data_fields as $id => $data_fields) {
            $fields = $data_fields['direct'];
            // Watch out: If there are conditional fields, these will be processed by this directive too
            // Hence, collect all these fields, and add them as if they were direct
            $conditionalFields = \PoP\ComponentModel\TypeResolvers\FieldHelpers::extractConditionalFields($data_fields);
            $fields = \array_unique(\array_merge($fields, $conditionalFields));
            if ($this->isUnionType()) {
                list($dbKey, $resultItemID) = \PoP\ComponentModel\TypeResolvers\UnionTypeHelpers::extractDBObjectTypeAndID($id);
                $resultItemIDTargetTypeResolver = $resultItemIDTargetTypeResolvers[$resultItemID];
                $mandatoryDirectivesForFields = $targetTypeResolverClassMandatoryDirectivesForFields[\get_class($resultItemIDTargetTypeResolver)];
            }
            foreach ($fields as $field) {
                if (!isset($this->fieldDirectivesFromFieldCache[$field])) {
                    // Get the directives from the field
                    $directives = $fieldQueryInterpreter->getDirectives($field);
                    // Add the mandatory directives defined for this field or for any field in this typeResolver
                    $fieldName = $fieldQueryInterpreter->getFieldName($field);
                    if ($mandatoryDirectivesForField = \array_merge($mandatoryDirectivesForFields[\PoP\ComponentModel\TypeResolvers\FieldSymbols::ANY_FIELD] ?? [], $mandatoryDirectivesForFields[$fieldName] ?? [])) {
                        // The mandatory directives must be placed first!
                        $directives = \array_merge($mandatoryDirectivesForField, $directives);
                    }
                    // Place the mandatory "system" directives at the beginning of the list, then they will be added to their needed position in the pipeline
                    $directives = \array_merge($mandatorySystemDirectives, $directives);
                    // If the directives must be preceded by other directives, add them now
                    $directives = $this->addMandatoryDirectivesForDirectives($directives);
                    // Convert from directive to fieldDirective
                    $fieldDirectives = \implode(\PoP\FieldQuery\QuerySyntax::SYMBOL_FIELDDIRECTIVE_SEPARATOR, \array_map([$fieldQueryInterpreter, 'convertDirectiveToFieldDirective'], $directives));
                    // Assign in the cache
                    $this->fieldDirectivesFromFieldCache[$field] = $fieldDirectives;
                }
                // Extract all the directives, and store which fields they process
                foreach (\PoP\FieldQuery\QueryHelpers::splitFieldDirectives($this->fieldDirectivesFromFieldCache[$field]) as $fieldDirective) {
                    // Watch out! Directives can be repeated, and then they must be executed multiple times
                    // Eg: resizing a pic to 25%: <resize(50%),resize(50%)>
                    // However, because we are adding the $idsDataFields under key $fieldDirective, when the 2nd occurrence of the directive is found,
                    // adding data would just override the previous entry, and we can't keep track that it's another iteration
                    // Then, as solution, change the name of the $fieldDirective, adding "|counter". This is an artificial construction,
                    // in which the "|" symbol could not be part of the field naturally
                    if (isset($fieldDirectiveCounter[$field][(string) $id][$fieldDirective])) {
                        // Increase counter and add to $fieldDirective
                        $fieldDirective .= \PoP\ComponentModel\TypeResolvers\FieldSymbols::REPEATED_DIRECTIVE_COUNTER_SEPARATOR . ++$fieldDirectiveCounter[$field][(string) $id][$fieldDirective];
                    } else {
                        $fieldDirectiveCounter[$field][(string) $id][$fieldDirective] = 0;
                    }
                    // Store which ID/field this directive must process
                    if (\in_array($field, $data_fields['direct'])) {
                        $this->fieldDirectiveIDFields[$fieldDirective][(string) $id]['direct'][] = $field;
                    }
                    if ($conditionalFields = $data_fields['conditional'][$field] ?? null) {
                        $this->fieldDirectiveIDFields[$fieldDirective][(string) $id]['conditional'][$field] = \array_merge_recursive($this->fieldDirectiveIDFields[$fieldDirective][(string) $id]['conditional'][$field] ?? [], $conditionalFields);
                    }
                }
            }
        }
    }
    protected function processFillingResultItemsFromIDs(array &$resultIDItems, array &$unionDBKeyIDs, array &$dbItems, array &$previousDBItems, array &$variables, array &$messages, array &$dbErrors, array &$dbWarnings, array &$dbDeprecations, array &$dbNotices, array &$dbTraces, array &$schemaErrors, array &$schemaWarnings, array &$schemaDeprecations, array &$schemaNotices, array &$schemaTraces) : void
    {
        // Iterate while there are directives with data to be processed
        while (!empty($this->fieldDirectiveIDFields)) {
            $fieldDirectiveIDFields = $this->fieldDirectiveIDFields;
            // Now that we have all data, remove all entries from the inner stack.
            // It may be filled again with composed directives, when resolving the pipeline
            $this->fieldDirectiveIDFields = [];
            // Calculate the fieldDirectives
            $fieldDirectives = \array_keys($fieldDirectiveIDFields);
            // Calculate all the fields on which the directive will be applied.
            $fieldDirectiveFields = $fieldDirectiveFieldIDs = [];
            $fieldDirectiveDirectFields = [];
            foreach ($fieldDirectives as $fieldDirective) {
                foreach ($fieldDirectiveIDFields[$fieldDirective] as $id => $dataFields) {
                    $fieldDirectiveDirectFields = \array_merge($fieldDirectiveDirectFields, $dataFields['direct']);
                    $conditionalFields = \PoP\ComponentModel\TypeResolvers\FieldHelpers::extractConditionalFields($dataFields);
                    $idFieldDirectiveIDFields = \array_merge($dataFields['direct'], $conditionalFields);
                    $fieldDirectiveFields[$fieldDirective] = \array_merge($fieldDirectiveFields[$fieldDirective] ?? [], $idFieldDirectiveIDFields);
                    // Also transpose the array to match field to IDs later on
                    foreach ($idFieldDirectiveIDFields as $field) {
                        $fieldDirectiveFieldIDs[$fieldDirective][$field][] = $id;
                    }
                }
                $fieldDirectiveFields[$fieldDirective] = \array_unique($fieldDirectiveFields[$fieldDirective]);
            }
            $fieldDirectiveDirectFields = \array_unique($fieldDirectiveDirectFields);
            $idFieldDirectiveIDFields = \array_unique($idFieldDirectiveIDFields);
            // Validate and resolve the directives into instances and fields they operate on
            $directivePipelineData = $this->resolveDirectivesIntoPipelineData($fieldDirectives, $fieldDirectiveFields, \false, $variables, $schemaErrors, $schemaWarnings, $schemaDeprecations, $schemaNotices, $schemaTraces);
            // From the fields, reconstitute the $idsDataFields for each directive,
            // and build the array to pass to the pipeline, for each directive (stage)
            $directiveResolverInstances = $pipelineIDsDataFields = [];
            foreach ($directivePipelineData as $pipelineStageData) {
                $directiveResolverInstance = $pipelineStageData['instance'];
                $fieldDirective = $pipelineStageData['fieldDirective'];
                $directiveFields = $pipelineStageData['fields'];
                // Only process the direct fields
                $directiveDirectFields = \array_intersect($directiveFields, $fieldDirectiveDirectFields);
                // From the fields, reconstitute the $idsDataFields for each directive, and build the array to pass to the pipeline, for each directive (stage)
                $directiveIDFields = [];
                foreach ($directiveDirectFields as $field) {
                    $ids = $fieldDirectiveFieldIDs[$fieldDirective][$field];
                    foreach ($ids as $id) {
                        $directiveIDFields[$id]['direct'][] = $field;
                        if ($fieldConditionalFields = $fieldDirectiveIDFields[$fieldDirective][$id]['conditional'][$field] ?? null) {
                            $directiveIDFields[$id]['conditional'][$field] = $fieldConditionalFields;
                        } else {
                            $directiveIDFields[$id]['conditional'] = $directiveIDFields[$id]['conditional'] ?? [];
                        }
                    }
                }
                $pipelineIDsDataFields[] = $directiveIDFields;
                $directiveResolverInstances[] = $directiveResolverInstance;
            }
            // We can finally resolve the pipeline, passing along an array with the ID and fields for each directive
            $directivePipeline = $this->getDirectivePipeline($directiveResolverInstances);
            $directivePipeline->resolveDirectivePipeline($this, $pipelineIDsDataFields, $directiveResolverInstances, $resultIDItems, $unionDBKeyIDs, $dbItems, $previousDBItems, $variables, $messages, $dbErrors, $dbWarnings, $dbDeprecations, $dbNotices, $dbTraces, $schemaErrors, $schemaWarnings, $schemaDeprecations, $schemaNotices, $schemaTraces);
        }
    }
    protected function dissectFieldForSchema(string $field) : ?array
    {
        if (!isset($this->dissectedFieldForSchemaCache[$field])) {
            $this->dissectedFieldForSchemaCache[$field] = $this->doDissectFieldForSchema($field);
        }
        return $this->dissectedFieldForSchemaCache[$field];
    }
    protected function doDissectFieldForSchema(string $field) : ?array
    {
        $fieldQueryInterpreter = \PoP\ComponentModel\Facades\Schema\FieldQueryInterpreterFacade::getInstance();
        return $fieldQueryInterpreter->extractFieldArgumentsForSchema($this, $field);
    }
    public function resolveSchemaValidationErrorDescriptions(string $field, array &$variables = null) : array
    {
        // Get the value from a fieldResolver, from the first one that resolves it
        list($validField, $fieldName, $fieldArgs, $schemaErrors, ) = $this->dissectFieldForSchema($field);
        if ($fieldResolvers = $this->getFieldResolversForField($field)) {
            if ($maybeErrors = $fieldResolvers[0]->resolveSchemaValidationErrorDescriptions($this, $fieldName, $fieldArgs)) {
                foreach ($maybeErrors as $error) {
                    $schemaErrors[] = [\PoP\ComponentModel\Feedback\Tokens::PATH => [$field], \PoP\ComponentModel\Feedback\Tokens::MESSAGE => $error];
                }
            }
            return $schemaErrors;
        }
        // If we reach here, no fieldResolver processes this field, which is an error
        $translationAPI = \PoP\Translation\Facades\TranslationAPIFacade::getInstance();
        /**
         * If the error happened from requesting a version that doesn't exist, show an appropriate error message
         */
        if (\PoP\ComponentModel\Environment::enableSemanticVersionConstraints()) {
            if ($versionConstraint = $fieldArgs[\PoP\ComponentModel\Schema\SchemaDefinition::ARGNAME_VERSION_CONSTRAINT] ?? null) {
                $errorMessage = \sprintf($translationAPI->__('No FieldResolver resolves field \'%s\' and version constraint \'%s\' for type \'%s\'', 'pop-component-model'), $fieldName, $versionConstraint, $this->getMaybeNamespacedTypeName());
            }
        }
        if (!isset($errorMessage)) {
            $errorMessage = \sprintf($translationAPI->__('No FieldResolver resolves field \'%s\' for type \'%s\'', 'pop-component-model'), $fieldName, $this->getMaybeNamespacedTypeName());
        }
        return [[\PoP\ComponentModel\Feedback\Tokens::PATH => [$field], \PoP\ComponentModel\Feedback\Tokens::MESSAGE => $errorMessage]];
    }
    public function resolveSchemaValidationWarningDescriptions(string $field, array &$variables = null) : array
    {
        // Get the value from a fieldResolver, from the first one that resolves it
        if ($fieldResolvers = $this->getFieldResolversForField($field)) {
            list($validField, $fieldName, $fieldArgs, $schemaErrors, $schemaWarnings, ) = $this->dissectFieldForSchema($field);
            if ($maybeWarnings = $fieldResolvers[0]->resolveSchemaValidationWarningDescriptions($this, $fieldName, $fieldArgs)) {
                // $fieldQueryInterpreter = FieldQueryInterpreterFacade::getInstance();
                // $fieldOutputKey = $fieldQueryInterpreter->getFieldOutputKey($field);
                foreach ($maybeWarnings as $warning) {
                    $schemaWarnings[] = [\PoP\ComponentModel\Feedback\Tokens::PATH => [$field], \PoP\ComponentModel\Feedback\Tokens::MESSAGE => $warning];
                }
            }
            return $schemaWarnings;
        }
        return [];
    }
    public function resolveSchemaDeprecationDescriptions(string $field, array &$variables = null) : array
    {
        // Get the value from a fieldResolver, from the first one that resolves it
        if ($fieldResolvers = $this->getFieldResolversForField($field)) {
            list($validField, $fieldName, $fieldArgs, $schemaErrors, $schemaWarnings, $schemaDeprecations, ) = $this->dissectFieldForSchema($field);
            $fieldSchemaDefinition = $fieldResolvers[0]->getSchemaDefinitionForField($this, $fieldName, $fieldArgs);
            if ($fieldSchemaDefinition[\PoP\ComponentModel\Schema\SchemaDefinition::ARGNAME_DEPRECATED] ?? null) {
                // $fieldQueryInterpreter = FieldQueryInterpreterFacade::getInstance();
                // $fieldOutputKey = $fieldQueryInterpreter->getFieldOutputKey($field);
                $schemaDeprecations[] = [\PoP\ComponentModel\Feedback\Tokens::PATH => [$field], \PoP\ComponentModel\Feedback\Tokens::MESSAGE => $fieldSchemaDefinition[\PoP\ComponentModel\Schema\SchemaDefinition::ARGNAME_DEPRECATIONDESCRIPTION]];
            }
            // Check for deprecations in the enums
            if ($maybeDeprecations = $fieldResolvers[0]->resolveSchemaValidationDeprecationDescriptions($this, $fieldName, $fieldArgs)) {
                foreach ($maybeDeprecations as $deprecation) {
                    $schemaDeprecations[] = [\PoP\ComponentModel\Feedback\Tokens::PATH => [$field], \PoP\ComponentModel\Feedback\Tokens::MESSAGE => $deprecation];
                }
            }
            return $schemaDeprecations;
        }
        return [];
    }
    public function getSchemaFieldArgs(string $field) : array
    {
        // Get the value from a fieldResolver, from the first one that resolves it
        if ($fieldResolvers = $this->getFieldResolversForField($field)) {
            $fieldQueryInterpreter = \PoP\ComponentModel\Facades\Schema\FieldQueryInterpreterFacade::getInstance();
            $fieldName = $fieldQueryInterpreter->getFieldName($field);
            $fieldArgs = $fieldQueryInterpreter->extractStaticFieldArguments($field);
            $fieldSchemaDefinition = $fieldResolvers[0]->getSchemaDefinitionForField($this, $fieldName, $fieldArgs);
            return $fieldSchemaDefinition[\PoP\ComponentModel\Schema\SchemaDefinition::ARGNAME_ARGS] ?? [];
        }
        return [];
    }
    public function enableOrderedSchemaFieldArgs(string $field) : bool
    {
        // Get the value from a fieldResolver, from the first one that resolves it
        if ($fieldResolvers = $this->getFieldResolversForField($field)) {
            $fieldQueryInterpreter = \PoP\ComponentModel\Facades\Schema\FieldQueryInterpreterFacade::getInstance();
            $fieldName = $fieldQueryInterpreter->getFieldName($field);
            return $fieldResolvers[0]->enableOrderedSchemaFieldArgs($this, $fieldName);
        }
        return \false;
    }
    public function resolveFieldTypeResolverClass(string $field) : ?string
    {
        // Get the value from a fieldResolver, from the first one that resolves it
        if ($fieldResolvers = $this->getFieldResolversForField($field)) {
            list($validField, $fieldName, ) = $this->dissectFieldForSchema($field);
            return $fieldResolvers[0]->resolveFieldTypeResolverClass($this, $fieldName);
        }
        return null;
    }
    /**
     * @param array<string, mixed>|null $variables
     * @param array<string, mixed>|null $expressions
     * @param array<string, mixed> $options
     * @return mixed
     * @param object $resultItem
     */
    public function resolveValue($resultItem, string $field, ?array $variables = null, ?array $expressions = null, array $options = [])
    {
        $fieldQueryInterpreter = \PoP\ComponentModel\Facades\Schema\FieldQueryInterpreterFacade::getInstance();
        // Get the value from a fieldResolver, from the first one who can deliver the value
        // (The fact that they resolve the fieldName doesn't mean that they will always resolve it for that specific $resultItem)
        if ($fieldResolvers = $this->getFieldResolversForField($field)) {
            $feedbackMessageStore = \PoP\ComponentModel\Facades\Schema\FeedbackMessageStoreFacade::getInstance();
            // Important: $validField becomes $field: remove all invalid fieldArgs before executing `resolveValue` on the fieldResolver
            list($field, $fieldName, $fieldArgs, $schemaErrors, ) = $this->dissectFieldForSchema($field);
            // // Store the warnings to be read if needed
            // if ($schemaWarnings) {
            //     $feedbackMessageStore->addSchemaWarnings($schemaWarnings);
            // }
            if ($schemaErrors) {
                return \PoP\ComponentModel\ErrorUtils::getNestedSchemaErrorsFieldError($schemaErrors, $fieldName);
            }
            // Important: calculate 'isAnyFieldArgumentValueDynamic' before resolving the args for the resultItem
            // That is because if when resolving there is an error, the fieldArgValue will be removed completely, then we don't know that we must validate the schema again
            // Eg: doing /?query=arrayUnique(extract(..., 0)) and extract fails, arrayUnique will have no fieldArgs. However its fieldArg is mandatory, but by then it doesn't know it needs to validate it
            // Before resolving the fieldArgValues which are fields:
            // Calculate $validateSchemaOnResultItem: if any value containes a field, then we must perform the schemaValidation on the item, such as checking that all mandatory fields are there
            // For instance: After resolving a field and being casted it may be incorrect, so the value is invalidated, and after the schemaValidation the proper error is shown
            // Also need to check for variables, since these must be resolved too
            // For instance: ?query=posts(limit:3),post(id:$id).id|title&id=112
            // We can also force it through an option. This is needed when the field is created on runtime.
            // Eg: through the <transform> directive, in which case no parameter is dynamic anymore by the time it reaches here, yet it was not validated statically either
            $validateSchemaOnResultItem = ($options[self::OPTION_VALIDATE_SCHEMA_ON_RESULT_ITEM] ?? null) || \PoP\ComponentModel\Schema\FieldQueryUtils::isAnyFieldArgumentValueDynamic(\array_values($fieldQueryInterpreter->extractFieldArguments($this, $field)));
            // Once again, the $validField becomes the $field
            list($field, $fieldName, $fieldArgs, $dbErrors, $dbWarnings) = $fieldQueryInterpreter->extractFieldArgumentsForResultItem($this, $resultItem, $field, $variables, $expressions);
            // Store the warnings to be read if needed
            if ($dbWarnings) {
                $feedbackMessageStore->addDBWarnings($dbWarnings);
            }
            if ($dbErrors) {
                return \PoP\ComponentModel\ErrorUtils::getNestedDBErrorsFieldError($dbErrors, $fieldName);
            }
            foreach ($fieldResolvers as $fieldResolver) {
                // Also send the typeResolver along, as to get the id of the $resultItem being passed
                if ($fieldResolver->resolveCanProcessResultItem($this, $resultItem, $fieldName, $fieldArgs)) {
                    if ($validateSchemaOnResultItem) {
                        if ($maybeErrors = $fieldResolver->resolveSchemaValidationErrorDescriptions($this, $fieldName, $fieldArgs)) {
                            return \PoP\ComponentModel\ErrorUtils::getValidationFailedError($fieldName, $fieldArgs, $maybeErrors);
                        }
                        if ($maybeDeprecations = $fieldResolver->resolveSchemaValidationDeprecationDescriptions($this, $fieldName, $fieldArgs)) {
                            $id = $this->getID($resultItem);
                            foreach ($maybeDeprecations as $deprecation) {
                                $dbDeprecations[(string) $id][] = [\PoP\ComponentModel\Feedback\Tokens::PATH => [$field], \PoP\ComponentModel\Feedback\Tokens::MESSAGE => $deprecation];
                            }
                            $feedbackMessageStore->addDBDeprecations($dbDeprecations);
                        }
                    }
                    if ($validationErrorDescriptions = $fieldResolver->getValidationErrorDescriptions($this, $resultItem, $fieldName, $fieldArgs)) {
                        return \PoP\ComponentModel\ErrorUtils::getValidationFailedError($fieldName, $fieldArgs, $validationErrorDescriptions);
                    }
                    // Resolve the value
                    $value = $fieldResolver->resolveValue($this, $resultItem, $fieldName, $fieldArgs, $variables, $expressions, $options);
                    // If it is null and the field is nonNullable, return an error
                    if (\is_null($value)) {
                        $fieldSchemaDefinition = $fieldResolver->getSchemaDefinitionForField($this, $fieldName, $fieldArgs);
                        if ($fieldSchemaDefinition[\PoP\ComponentModel\Schema\SchemaDefinition::ARGNAME_NON_NULLABLE] ?? null) {
                            return \PoP\ComponentModel\ErrorUtils::getNonNullableFieldError($fieldName);
                        }
                    }
                    // Everything is good, return the value (which could also be an Error!)
                    return $value;
                }
            }
            return \PoP\ComponentModel\ErrorUtils::getNoFieldResolverProcessesFieldError($this->getID($resultItem), $fieldName, $fieldArgs);
        }
        // Return an error to indicate that no fieldResolver processes this field, which is different than returning a null value.
        // Needed for compatibility with CustomPostUnionTypeResolver (so that data-fields aimed for another post_type are not retrieved)
        $fieldName = $fieldQueryInterpreter->getFieldName($field);
        return \PoP\ComponentModel\ErrorUtils::getNoFieldError($fieldName);
    }
    protected function processFlatShapeSchemaDefinition(array $options = [])
    {
        $schemaDefinitionService = \PoP\ComponentModel\Facades\Schema\SchemaDefinitionServiceFacade::getInstance();
        $typeSchemaKey = $schemaDefinitionService->getTypeSchemaKey($this);
        // By now, we have the schema definition
        if (isset($this->schemaDefinition[$typeSchemaKey][\PoP\ComponentModel\Schema\SchemaDefinition::ARGNAME_CONNECTIONS])) {
            $connections =& $this->schemaDefinition[$typeSchemaKey][\PoP\ComponentModel\Schema\SchemaDefinition::ARGNAME_CONNECTIONS];
            foreach ($connections as &$connection) {
                // If it is a recursion or repeated there will be no schema
                if (isset($connection[\PoP\ComponentModel\Schema\SchemaDefinition::ARGNAME_TYPE_SCHEMA])) {
                    // Remove the typeSchema entry
                    unset($connection[\PoP\ComponentModel\Schema\SchemaDefinition::ARGNAME_TYPE_SCHEMA]);
                }
            }
        }
    }
    public function getSchemaDefinition(array $stackMessages, array &$generalMessages, array $options = []) : array
    {
        $schemaDefinitionService = \PoP\ComponentModel\Facades\Schema\SchemaDefinitionServiceFacade::getInstance();
        $typeSchemaKey = $schemaDefinitionService->getTypeSchemaKey($this);
        // Stop recursion
        $class = \get_called_class();
        if (\in_array($class, $stackMessages['processed'])) {
            return [$typeSchemaKey => [\PoP\ComponentModel\Schema\SchemaDefinition::ARGNAME_RECURSION => \true]];
        }
        $isFlatShape = isset($options['shape']) && $options['shape'] == \PoP\ComponentModel\Schema\SchemaDefinition::ARGVALUE_SCHEMA_SHAPE_FLAT;
        // If "compressed" or printing a flat shape, and the resolver has already been added to the schema, then skip it
        if (($isFlatShape || ($options['compressed'] ?? null)) && \in_array($class, $generalMessages['processed'])) {
            return [$typeSchemaKey => [\PoP\ComponentModel\Schema\SchemaDefinition::ARGNAME_REPEATED => \true]];
        }
        $stackMessages['processed'][] = $class;
        $generalMessages['processed'][] = $class;
        if (\is_null($this->schemaDefinition)) {
            // Important: This line stops the recursion when a type reference each other circularly, so do not remove it!
            $this->schemaDefinition = [];
            $this->addSchemaDefinition($stackMessages, $generalMessages, $options);
            // If it is a flat shape, we can remove the nested connections, replace them only with the type name
            if ($isFlatShape) {
                $this->processFlatShapeSchemaDefinition($options);
                // Add the type to the list of all types, displayed when doing "shape=>flat"
                $generalMessages[\PoP\ComponentModel\Schema\SchemaDefinition::ARGNAME_TYPES][$typeSchemaKey] = $this->schemaDefinition[$typeSchemaKey];
            }
        }
        return $this->schemaDefinition;
    }
    protected function getDirectiveSchemaDefinition(\PoP\ComponentModel\DirectiveResolvers\DirectiveResolverInterface $directiveResolver, array $options = []) : array
    {
        $directiveSchemaDefinition = $directiveResolver->getSchemaDefinitionForDirective($this);
        return $directiveSchemaDefinition;
    }
    protected function addSchemaDefinition(array $stackMessages, array &$generalMessages, array $options = [])
    {
        $schemaDefinitionService = \PoP\ComponentModel\Facades\Schema\SchemaDefinitionServiceFacade::getInstance();
        $typeSchemaKey = $schemaDefinitionService->getTypeSchemaKey($this);
        $typeName = $this->getMaybeNamespacedTypeName();
        $this->schemaDefinition[$typeSchemaKey][\PoP\ComponentModel\Schema\SchemaDefinition::ARGNAME_NAME] = $typeName;
        $this->schemaDefinition[$typeSchemaKey][\PoP\ComponentModel\Schema\SchemaDefinition::ARGNAME_NAMESPACED_NAME] = $this->getNamespacedTypeName();
        $this->schemaDefinition[$typeSchemaKey][\PoP\ComponentModel\Schema\SchemaDefinition::ARGNAME_ELEMENT_NAME] = $this->getTypeName();
        // Properties
        if ($description = $this->getSchemaTypeDescription()) {
            $this->schemaDefinition[$typeSchemaKey][\PoP\ComponentModel\Schema\SchemaDefinition::ARGNAME_DESCRIPTION] = $description;
        }
        // Add the directives (non-global)
        $this->schemaDefinition[$typeSchemaKey][\PoP\ComponentModel\Schema\SchemaDefinition::ARGNAME_DIRECTIVES] = [];
        $schemaDirectiveResolvers = $this->getSchemaDirectiveResolvers(\false);
        foreach ($schemaDirectiveResolvers as $directiveName => $directiveResolver) {
            $this->schemaDefinition[$typeSchemaKey][\PoP\ComponentModel\Schema\SchemaDefinition::ARGNAME_DIRECTIVES][$directiveName] = $this->getDirectiveSchemaDefinition($directiveResolver, $options);
        }
        // Add the fields (non-global)
        $this->schemaDefinition[$typeSchemaKey][\PoP\ComponentModel\Schema\SchemaDefinition::ARGNAME_FIELDS] = [];
        $schemaFieldResolvers = $this->getSchemaFieldResolvers(\false);
        foreach ($schemaFieldResolvers as $fieldName => $fieldResolver) {
            $this->addFieldSchemaDefinition($fieldResolver, $fieldName, $stackMessages, $generalMessages, $options);
        }
        // Add all the implemented interfaces
        $instanceManager = \PoP\ComponentModel\Facades\Instances\InstanceManagerFacade::getInstance();
        $schemaDefinitionService = \PoP\ComponentModel\Facades\Schema\SchemaDefinitionServiceFacade::getInstance();
        $typeInterfaceDefinitions = [];
        foreach ($this->getAllImplementedInterfaceResolverInstances() as $interfaceInstance) {
            $interfaceSchemaKey = $schemaDefinitionService->getInterfaceSchemaKey($interfaceInstance);
            // Conveniently get the fields from the schema, which have already been calculated above
            // since they also include their interface fields
            $interfaceFieldNames = $interfaceInstance::getFieldNamesToImplement();
            // The Interface fields may be implemented as either FieldResolver fields or FieldResolver connections,
            // Eg: Interface "Elemental" has field "id" and connection "self"
            // Merge both cases into interface fields
            $interfaceFields = \array_filter($this->schemaDefinition[$typeSchemaKey][\PoP\ComponentModel\Schema\SchemaDefinition::ARGNAME_FIELDS], function ($fieldName) use($interfaceFieldNames) {
                return \in_array($fieldName, $interfaceFieldNames);
            }, \ARRAY_FILTER_USE_KEY);
            $interfaceConnections = \array_filter($this->schemaDefinition[$typeSchemaKey][\PoP\ComponentModel\Schema\SchemaDefinition::ARGNAME_CONNECTIONS], function ($connectionName) use($interfaceFieldNames) {
                return \in_array($connectionName, $interfaceFieldNames);
            }, \ARRAY_FILTER_USE_KEY);
            $interfaceFields = \array_merge($interfaceFields, $interfaceConnections);
            // Interfaces and FieldResolvers must match on all attributes of the signature:
            // fieldName, arguments, and return type. But not on the description of the field,
            // as to make it more specific for the field
            // So override the description with the interface's own
            foreach ($interfaceFieldNames as $interfaceFieldName) {
                // Make sure a definition for that fieldName has been added,
                // since the field could've been removed through an ACL
                if ($interfaceFields[$interfaceFieldName]) {
                    if ($description = $interfaceInstance->getSchemaFieldDescription($interfaceFieldName)) {
                        $interfaceFields[$interfaceFieldName][\PoP\ComponentModel\Schema\SchemaDefinition::ARGNAME_DESCRIPTION] = $description;
                    } else {
                        // Do not keep the description from the fieldResolver
                        unset($interfaceFields[$interfaceFieldName][\PoP\ComponentModel\Schema\SchemaDefinition::ARGNAME_DESCRIPTION]);
                    }
                }
            }
            // An interface can itself implement interfaces!
            $interfaceImplementedInterfaceNames = [];
            if ($interfaceImplementedInterfaceClasses = $interfaceInstance::getImplementedInterfaceClasses()) {
                foreach ($interfaceImplementedInterfaceClasses as $interfaceImplementedInterfaceClass) {
                    $interfaceImplementedInterfaceInstance = $instanceManager->getInstance($interfaceImplementedInterfaceClass);
                    $interfaceImplementedInterfaceNames[] = $interfaceImplementedInterfaceInstance->getMaybeNamespacedInterfaceName();
                }
            }
            // // Add the versions to the fields, as coming from the interface
            // $interfaceFields = array_map(
            //     function ($fieldSchemaDefinition) use ($interfaceInstance) {
            //         if ($version = $interfaceInstance->getSchemaInterfaceVersion($fieldSchemaDefinition[SchemaDefinition::ARGNAME_NAME])) {
            //             $fieldSchemaDefinition[SchemaDefinition::ARGNAME_VERSION] = $version;
            //         }
            //         return $fieldSchemaDefinition;
            //     },
            //     $interfaceFields
            // );
            $interfaceName = $interfaceInstance->getMaybeNamespacedInterfaceName();
            // Possible types: Because we are generating this list as we go along resolving all the types, simply have this value point to a reference in $generalMessages
            // Just by updating that variable, it will eventually be updated everywhere
            $generalMessages['interfaceGeneralTypes'][$interfaceName] = $generalMessages['interfaceGeneralTypes'][$interfaceName] ?? [];
            $interfacePossibleTypes =& $generalMessages['interfaceGeneralTypes'][$interfaceName];
            // Add this type to the list of implemented types for this interface
            $interfacePossibleTypes[] = $typeName;
            $typeInterfaceDefinitions[$interfaceSchemaKey] = [
                \PoP\ComponentModel\Schema\SchemaDefinition::ARGNAME_NAME => $interfaceName,
                \PoP\ComponentModel\Schema\SchemaDefinition::ARGNAME_NAMESPACED_NAME => $interfaceInstance->getNamespacedInterfaceName(),
                \PoP\ComponentModel\Schema\SchemaDefinition::ARGNAME_ELEMENT_NAME => $interfaceInstance->getInterfaceName(),
                \PoP\ComponentModel\Schema\SchemaDefinition::ARGNAME_DESCRIPTION => $interfaceInstance->getSchemaInterfaceDescription(),
                \PoP\ComponentModel\Schema\SchemaDefinition::ARGNAME_FIELDS => $interfaceFields,
                \PoP\ComponentModel\Schema\SchemaDefinition::ARGNAME_INTERFACES => $interfaceImplementedInterfaceNames,
                // The list of types that implement this interface
                \PoP\ComponentModel\Schema\SchemaDefinition::ARGNAME_POSSIBLE_TYPES => &$interfacePossibleTypes,
            ];
        }
        $this->schemaDefinition[$typeSchemaKey][\PoP\ComponentModel\Schema\SchemaDefinition::ARGNAME_INTERFACES] = $typeInterfaceDefinitions;
    }
    protected function getSchemaDirectiveResolvers(bool $global) : array
    {
        $instanceManager = \PoP\ComponentModel\Facades\Instances\InstanceManagerFacade::getInstance();
        $directiveResolverInstances = [];
        $directiveNameClasses = $this->getDirectiveNameClasses();
        foreach ($directiveNameClasses as $directiveName => $directiveClasses) {
            foreach ($directiveClasses as $directiveClass) {
                /** @var DirectiveResolverInterface */
                $directiveResolverInstance = $instanceManager->getInstance($directiveClass);
                // A directive can decide to not be added to the schema, eg: when it is repeated/implemented several times
                if ($directiveResolverInstance->skipAddingToSchemaDefinition()) {
                    continue;
                }
                $isGlobal = $directiveResolverInstance->isGlobal($this);
                if ($global && $isGlobal || !$global && !$isGlobal) {
                    $directiveResolverInstances[$directiveName] = $directiveResolverInstance;
                }
            }
        }
        return $directiveResolverInstances;
    }
    protected function getSchemaFieldResolvers(bool $global) : array
    {
        $schemaFieldResolvers = [];
        foreach ($this->getAllFieldResolvers() as $fieldName => $fieldResolvers) {
            // Get the documentation from the first element
            $fieldResolver = $fieldResolvers[0];
            $isGlobal = $fieldResolver->isGlobal($this, $fieldName);
            if ($global && $isGlobal || !$global && !$isGlobal) {
                $schemaFieldResolvers[$fieldName] = $fieldResolver;
            }
        }
        return $schemaFieldResolvers;
    }
    protected function addFieldSchemaDefinition(\PoP\ComponentModel\FieldResolvers\FieldResolverInterface $fieldResolver, string $fieldName, array $stackMessages, array &$generalMessages, array $options = [])
    {
        /**
         * Fields may not be directly visible in the schema
         */
        if ($fieldResolver->skipAddingToSchemaDefinition($this, $fieldName)) {
            return;
        }
        $instanceManager = \PoP\ComponentModel\Facades\Instances\InstanceManagerFacade::getInstance();
        // Watch out! We are passing empty $fieldArgs to generate the schema!
        $fieldSchemaDefinition = $fieldResolver->getSchemaDefinitionForField($this, $fieldName, []);
        // Add subfield schema if it is deep, and this typeResolver has not been processed yet
        if ($options['deep'] ?? null) {
            // If this field is relational, then add its own schema
            if ($fieldTypeResolverClass = $this->resolveFieldTypeResolverClass($fieldName)) {
                $fieldTypeResolver = $instanceManager->getInstance($fieldTypeResolverClass);
                $fieldSchemaDefinition[\PoP\ComponentModel\Schema\SchemaDefinition::ARGNAME_TYPE_SCHEMA] = $fieldTypeResolver->getSchemaDefinition($stackMessages, $generalMessages, $options);
            }
        }
        // Convert the field type from its internal representation (eg: "array:id") to the type (eg: "array:Post")
        if ($options['useTypeName'] ?? null) {
            if ($type = $fieldSchemaDefinition[\PoP\ComponentModel\Schema\SchemaDefinition::ARGNAME_TYPE] ?? null) {
                $fieldSchemaDefinition[\PoP\ComponentModel\Schema\SchemaDefinition::ARGNAME_TYPE] = \PoP\ComponentModel\Schema\SchemaHelpers::convertTypeIDToTypeName($type, $this, $fieldName);
            }
        } else {
            // Display the type under entry "referencedType"
            if ($types = $fieldSchemaDefinition[\PoP\ComponentModel\Schema\SchemaDefinition::ARGNAME_TYPE_SCHEMA] ?? null) {
                $typeNames = \array_keys($types);
                $fieldSchemaDefinition[\PoP\ComponentModel\Schema\SchemaDefinition::ARGNAME_REFERENCED_TYPE] = $typeNames[0];
            }
        }
        $isGlobal = $fieldResolver->isGlobal($this, $fieldName);
        $isConnection = isset($fieldSchemaDefinition[\PoP\ComponentModel\Schema\SchemaDefinition::ARGNAME_RELATIONAL]) && $fieldSchemaDefinition[\PoP\ComponentModel\Schema\SchemaDefinition::ARGNAME_RELATIONAL];
        if ($isGlobal) {
            // If it is relational, it is a global connection
            if ($isConnection) {
                $entry = \PoP\ComponentModel\Schema\SchemaDefinition::ARGNAME_GLOBAL_CONNECTIONS;
                // Remove the "types"
                if ($options['useTypeName'] ?? null) {
                    unset($fieldSchemaDefinition[\PoP\ComponentModel\Schema\SchemaDefinition::ARGNAME_TYPE_SCHEMA]);
                }
            } else {
                $entry = \PoP\ComponentModel\Schema\SchemaDefinition::ARGNAME_GLOBAL_FIELDS;
            }
        } else {
            // Split the results into "fields" and "connections"
            $entry = $isConnection ? \PoP\ComponentModel\Schema\SchemaDefinition::ARGNAME_CONNECTIONS : \PoP\ComponentModel\Schema\SchemaDefinition::ARGNAME_FIELDS;
        }
        // Can remove attribute "relational"
        if ($isConnection) {
            unset($fieldSchemaDefinition[\PoP\ComponentModel\Schema\SchemaDefinition::ARGNAME_RELATIONAL]);
        }
        $schemaDefinitionService = \PoP\ComponentModel\Facades\Schema\SchemaDefinitionServiceFacade::getInstance();
        $typeSchemaKey = $schemaDefinitionService->getTypeSchemaKey($this);
        $this->schemaDefinition[$typeSchemaKey][$entry][$fieldName] = $fieldSchemaDefinition;
    }
    protected function isFieldNameResolvedByFieldResolver(\PoP\ComponentModel\FieldResolvers\FieldResolverInterface $fieldResolver, string $fieldName, array $fieldInterfaceResolverClasses) : bool
    {
        // Calculate all the interfaces that define this fieldName
        $fieldInterfaceResolverClassesForField = \array_values(\array_filter($fieldInterfaceResolverClasses, function ($fieldInterfaceResolverClass) use($fieldName) : bool {
            return \in_array($fieldName, $fieldInterfaceResolverClass::getFieldNamesToImplement());
        }));
        // Execute 2 filters: a generic one, and a specific one
        $hooksAPI = \PoP\Hooks\Facades\HooksAPIFacade::getInstance();
        if ($hooksAPI->applyFilters(\PoP\ComponentModel\TypeResolvers\HookHelpers::getHookNameToFilterField(), \true, $this, $fieldResolver, $fieldInterfaceResolverClassesForField, $fieldName)) {
            return $hooksAPI->applyFilters(\PoP\ComponentModel\TypeResolvers\HookHelpers::getHookNameToFilterField($fieldName), \true, $this, $fieldResolver, $fieldInterfaceResolverClassesForField, $fieldName);
        }
        return \false;
    }
    /**
     * Return the fieldNames resolved by the fieldResolverClass, adding a hook to disable each of them (eg: to implement a private schema)
     *
     * @param string $extensionClass
     * @return array
     */
    protected function getFieldNamesResolvedByFieldResolver(string $fieldResolverClass) : array
    {
        if (!isset($this->fieldNamesResolvedByFieldResolver[$fieldResolverClass])) {
            // Merge the fieldNames resolved by this field resolver class, and the interfaces it implements
            $fieldNames = \array_merge($fieldResolverClass::getFieldNamesToResolve(), $fieldResolverClass::getFieldNamesFromInterfaces());
            // Execute a hook, allowing to filter them out (eg: removing fieldNames from a private schema)
            $instanceManager = \PoP\ComponentModel\Facades\Instances\InstanceManagerFacade::getInstance();
            /** @var FieldResolverInterface */
            $fieldResolver = $instanceManager->getInstance($fieldResolverClass);
            // Also pass the implemented interfaces defining the field
            $fieldInterfaceResolverClasses = $fieldResolver::getImplementedInterfaceClasses();
            $fieldNames = \array_filter($fieldNames, function ($fieldName) use($fieldResolver, $fieldInterfaceResolverClasses) {
                return $this->isFieldNameResolvedByFieldResolver($fieldResolver, $fieldName, $fieldInterfaceResolverClasses);
            });
            $this->fieldNamesResolvedByFieldResolver[$fieldResolverClass] = $fieldNames;
        }
        return $this->fieldNamesResolvedByFieldResolver[$fieldResolverClass];
    }
    protected function getAllFieldResolvers() : array
    {
        if (\is_null($this->schemaFieldResolvers)) {
            $this->schemaFieldResolvers = $this->calculateAllFieldResolvers();
        }
        return $this->schemaFieldResolvers;
    }
    protected function getTypeResolverClassToCalculateSchema() : string
    {
        return \get_called_class();
    }
    protected function calculateAllFieldResolvers() : array
    {
        $attachableExtensionManager = \PoP\ComponentModel\Facades\AttachableExtensions\AttachableExtensionManagerFacade::getInstance();
        $schemaFieldResolvers = [];
        // Get the fieldResolvers attached to this typeResolver and to all the interfaces it implements
        $classStack = [$this->getTypeResolverClassToCalculateSchema()];
        while (!empty($classStack)) {
            $class = \array_shift($classStack);
            // Iterate classes from the current class towards the parent classes until finding typeResolver that satisfies processing this field
            do {
                foreach ($attachableExtensionManager->getExtensionClasses($class, \PoP\ComponentModel\AttachableExtensions\AttachableExtensionGroups::FIELDRESOLVERS) as $extensionClass => $extensionPriority) {
                    // Process the fields which have not been processed yet
                    $extensionClassFieldNames = $this->getFieldNamesResolvedByFieldResolver($extensionClass);
                    foreach (\array_diff($extensionClassFieldNames, \array_keys($schemaFieldResolvers)) as $fieldName) {
                        // Watch out here: no fieldArgs!!!! So this deals with the base case (static), not with all cases (runtime)
                        // If using an ACL to remove a field from an interface,
                        // getting the fieldResolvers for that field will be empty
                        // Then ignore adding the field, it must not be added to the schema
                        if ($fieldResolversForField = $this->getFieldResolversForField($fieldName)) {
                            $schemaFieldResolvers[$fieldName] = $fieldResolversForField;
                        }
                    }
                    // The interfaces implemented by the FieldResolver can have, themselves, fieldResolvers attached to them
                    $classStack = \array_values(\array_unique(\array_merge($classStack, $extensionClass::getImplementedInterfaceClasses())));
                }
                // Otherwise, continue iterating for the class parents
            } while ($class = \get_parent_class($class));
        }
        return $schemaFieldResolvers;
    }
    public function getAllMandatoryDirectivesForFields() : array
    {
        if (\is_null($this->mandatoryDirectivesForFields)) {
            $this->mandatoryDirectivesForFields = $this->calculateAllMandatoryDirectivesForFields();
        }
        return $this->mandatoryDirectivesForFields;
    }
    protected function calculateAllMandatoryDirectivesForFields() : array
    {
        $mandatoryDirectivesForFields = [];
        $instanceManager = \PoP\ComponentModel\Facades\Instances\InstanceManagerFacade::getInstance();
        $typeResolverDecoratorClasses = $this->getAllTypeResolverDecoratorClassess();
        foreach ($typeResolverDecoratorClasses as $typeResolverDecoratorClass) {
            $typeResolverDecoratorInstance = $instanceManager->getInstance($typeResolverDecoratorClass);
            // array_merge_recursive so that if 2 different decorators add a directive for the same field, the results are merged together, not override each other
            if ($typeResolverDecoratorInstance->enabled($this)) {
                $mandatoryDirectivesForFields = \array_merge_recursive($mandatoryDirectivesForFields, $typeResolverDecoratorInstance->getMandatoryDirectivesForFields($this));
            }
        }
        return $mandatoryDirectivesForFields;
    }
    protected function getAllPrecedingMandatoryDirectivesForDirectives() : array
    {
        if (\is_null($this->precedingMandatoryDirectivesForDirectives)) {
            $this->precedingMandatoryDirectivesForDirectives = $this->calculateAllPrecedingMandatoryDirectivesForDirectives();
        }
        return $this->precedingMandatoryDirectivesForDirectives;
    }
    protected function calculateAllPrecedingMandatoryDirectivesForDirectives() : array
    {
        $precedingMandatoryDirectivesForDirectives = [];
        $instanceManager = \PoP\ComponentModel\Facades\Instances\InstanceManagerFacade::getInstance();
        $typeResolverDecoratorClasses = $this->getAllTypeResolverDecoratorClassess();
        foreach ($typeResolverDecoratorClasses as $typeResolverDecoratorClass) {
            $typeResolverDecoratorInstance = $instanceManager->getInstance($typeResolverDecoratorClass);
            // array_merge_recursive so that if 2 different decorators add a directive for the same directive, the results are merged together, not override each other
            if ($typeResolverDecoratorInstance->enabled($this)) {
                $precedingMandatoryDirectivesForDirectives = \array_merge_recursive($precedingMandatoryDirectivesForDirectives, $typeResolverDecoratorInstance->getPrecedingMandatoryDirectivesForDirectives($this));
            }
        }
        return $precedingMandatoryDirectivesForDirectives;
    }
    protected function getAllSucceedingMandatoryDirectivesForDirectives() : array
    {
        if (\is_null($this->succeedingMandatoryDirectivesForDirectives)) {
            $this->succeedingMandatoryDirectivesForDirectives = $this->calculateAllSucceedingMandatoryDirectivesForDirectives();
        }
        return $this->succeedingMandatoryDirectivesForDirectives;
    }
    protected function calculateAllSucceedingMandatoryDirectivesForDirectives() : array
    {
        $succeedingMandatoryDirectivesForDirectives = [];
        $instanceManager = \PoP\ComponentModel\Facades\Instances\InstanceManagerFacade::getInstance();
        $typeResolverDecoratorClasses = $this->getAllTypeResolverDecoratorClassess();
        foreach ($typeResolverDecoratorClasses as $typeResolverDecoratorClass) {
            $typeResolverDecoratorInstance = $instanceManager->getInstance($typeResolverDecoratorClass);
            // array_merge_recursive so that if 2 different decorators add a directive for the same directive, the results are merged together, not override each other
            if ($typeResolverDecoratorInstance->enabled($this)) {
                $succeedingMandatoryDirectivesForDirectives = \array_merge_recursive($succeedingMandatoryDirectivesForDirectives, $typeResolverDecoratorInstance->getSucceedingMandatoryDirectivesForDirectives($this));
            }
        }
        return $succeedingMandatoryDirectivesForDirectives;
    }
    protected function getAllTypeResolverDecoratorClassess() : array
    {
        if (\is_null($this->typeResolverDecoratorClasses)) {
            $this->typeResolverDecoratorClasses = $this->calculateAllTypeResolverDecoratorClasses();
        }
        return $this->typeResolverDecoratorClasses;
    }
    protected function calculateAllTypeResolverDecoratorClasses() : array
    {
        $decoratorClasses = [];
        /**
         * Also get the decorators for the implemented interfaces
         */
        $classes = \array_merge([$this->getTypeResolverClassToCalculateSchema()], $this->getAllImplementedInterfaceClasses());
        foreach ($classes as $class) {
            $decoratorClasses = \array_merge($decoratorClasses, $this->calculateAllTypeResolverDecoratorClassesForTypeOrInterfaceClass($class));
        }
        return $decoratorClasses;
    }
    protected function calculateAllTypeResolverDecoratorClassesForTypeOrInterfaceClass(string $class) : array
    {
        $attachableExtensionManager = \PoP\ComponentModel\Facades\AttachableExtensions\AttachableExtensionManagerFacade::getInstance();
        $decoratorClasses = [];
        // Iterate classes from the current class towards the parent classes until finding typeResolver that satisfies processing this field
        do {
            // Important: do array_reverse to enable more specific hooks, which are initialized later on in the project, to be the chosen ones (if their priority is the same)
            $extensionClassPriorities = \array_reverse($attachableExtensionManager->getExtensionClasses($class, \PoP\ComponentModel\AttachableExtensions\AttachableExtensionGroups::TYPERESOLVERDECORATORS));
            // Order them by priority: higher priority are evaluated first
            $extensionClasses = \array_keys($extensionClassPriorities);
            $extensionPriorities = \array_values($extensionClassPriorities);
            \array_multisort($extensionPriorities, \SORT_DESC, \SORT_NUMERIC, $extensionClasses);
            // Add them to the results
            $decoratorClasses = \array_merge($decoratorClasses, $extensionClasses);
            // Continue iterating for the class parents
        } while ($class = \get_parent_class($class));
        return $decoratorClasses;
    }
    public function getAllImplementedInterfaceResolverInstances() : array
    {
        if (\is_null($this->interfaceResolverInstances)) {
            $this->interfaceResolverInstances = $this->calculateAllImplementedInterfaceResolverInstances();
        }
        return $this->interfaceResolverInstances;
    }
    protected function calculateAllImplementedInterfaceResolverInstances() : array
    {
        $instanceManager = \PoP\ComponentModel\Facades\Instances\InstanceManagerFacade::getInstance();
        return \array_map(function ($interfaceClass) use($instanceManager) {
            return $instanceManager->getInstance($interfaceClass);
        }, $this->getAllImplementedInterfaceClasses());
    }
    public function getAllImplementedInterfaceClasses() : array
    {
        if (\is_null($this->interfaceClasses)) {
            $this->interfaceClasses = $this->calculateAllImplementedInterfaceClasses();
        }
        return $this->interfaceClasses;
    }
    protected function calculateAllImplementedInterfaceClasses() : array
    {
        $interfaceClasses = [];
        $processedFieldResolverClasses = [];
        foreach ($this->getAllFieldResolvers() as $fieldName => $fieldResolvers) {
            foreach ($fieldResolvers as $fieldResolver) {
                $fieldResolverClass = \get_class($fieldResolver);
                if (!\in_array($fieldResolverClass, $processedFieldResolverClasses)) {
                    $processedFieldResolverClasses[] = $fieldResolverClass;
                    $interfaceClasses = \array_merge($interfaceClasses, $fieldResolver::getImplementedInterfaceClasses());
                }
            }
        }
        return \array_values(\array_unique($interfaceClasses));
    }
    protected function getFieldResolversForField(string $field) : array
    {
        // Calculate the fieldResolver to process this field if not already in the cache
        // If none is found, this value will be set to NULL. This is needed to stop attempting to find the fieldResolver
        if (!isset($this->fieldResolvers[$field])) {
            $this->fieldResolvers[$field] = $this->calculateFieldResolversForField($field);
        }
        return $this->fieldResolvers[$field];
    }
    public function hasFieldResolversForField(string $field) : bool
    {
        return !empty($this->getFieldResolversForField($field));
    }
    protected function calculateFieldResolversForField(string $field) : array
    {
        // Important: here we CAN'T use `dissectFieldForSchema` to get the fieldArgs, because it will attempt to validate them
        // To validate them, the fieldQueryInterpreter needs to know the schema, so it once again calls functions from this typeResolver
        // Generating an infinite loop
        // Then, just to find out which fieldResolvers will process this field, crudely obtain the fieldArgs, with NO schema-based validation!
        // list(
        //     $field,
        //     $fieldName,
        //     $fieldArgs,
        // ) = $this->dissectFieldForSchema($field);
        $fieldQueryInterpreter = \PoP\ComponentModel\Facades\Schema\FieldQueryInterpreterFacade::getInstance();
        $fieldName = $fieldQueryInterpreter->getFieldName($field);
        $fieldArgs = $fieldQueryInterpreter->extractStaticFieldArguments($field);
        $instanceManager = \PoP\ComponentModel\Facades\Instances\InstanceManagerFacade::getInstance();
        $attachableExtensionManager = \PoP\ComponentModel\Facades\AttachableExtensions\AttachableExtensionManagerFacade::getInstance();
        $fieldResolvers = [];
        // Get the fieldResolvers attached to this typeResolver and to all the interfaces it implements
        $classStack = [$this->getTypeResolverClassToCalculateSchema()];
        while (!empty($classStack)) {
            $class = \array_shift($classStack);
            // Iterate classes from the current class towards the parent classes until finding typeResolver that satisfies processing this field
            do {
                // All the Units and their priorities for this class level
                $classTypeResolverPriorities = [];
                $classFieldResolvers = [];
                // Important: do array_reverse to enable more specific hooks, which are initialized later on in the project, to be the chosen ones (if their priority is the same)
                foreach (\array_reverse($attachableExtensionManager->getExtensionClasses($class, \PoP\ComponentModel\AttachableExtensions\AttachableExtensionGroups::FIELDRESOLVERS)) as $extensionClass => $extensionPriority) {
                    // Check if this fieldResolver can process this field, and if its priority is bigger than the previous found instance attached to the same class
                    $extensionClassFieldNames = $this->getFieldNamesResolvedByFieldResolver($extensionClass);
                    if (\in_array($fieldName, $extensionClassFieldNames)) {
                        // Check that the fieldResolver can handle the field based on other parameters (eg: "version" in the fieldArgs)
                        $fieldResolver = $instanceManager->getInstance($extensionClass);
                        if ($fieldResolver->resolveCanProcess($this, $fieldName, $fieldArgs)) {
                            $classTypeResolverPriorities[] = $extensionPriority;
                            $classFieldResolvers[] = $fieldResolver;
                        }
                    }
                    // The interfaces implemented by the FieldResolver can have, themselves, fieldResolvers attached to them
                    $classStack = \array_values(\array_unique(\array_merge($classStack, $extensionClass::getImplementedInterfaceClasses())));
                }
                // Sort the found units by their priority, and then add to the stack of all units, for all classes
                // Higher priority means they execute first!
                \array_multisort($classTypeResolverPriorities, \SORT_DESC, \SORT_NUMERIC, $classFieldResolvers);
                $fieldResolvers = \array_merge($fieldResolvers, $classFieldResolvers);
                // Continue iterating for the class parents
            } while ($class = \get_parent_class($class));
        }
        // Return all the units that resolve the fieldName
        return $fieldResolvers;
    }
    protected function calculateFieldDirectiveNameClasses() : array
    {
        $attachableExtensionManager = \PoP\ComponentModel\Facades\AttachableExtensions\AttachableExtensionManagerFacade::getInstance();
        $directiveNameClasses = [];
        // Directives can also be attached to the interface implemented by this typeResolver
        $classes = \array_merge([$this->getTypeResolverClassToCalculateSchema()], $this->getAllImplementedInterfaceClasses());
        foreach ($classes as $class) {
            // Iterate classes from the current class towards the parent classes until finding typeResolver that satisfies processing this field
            do {
                // Important: do array_reverse to enable more specific hooks, which are initialized later on in the project, to be the chosen ones (if their priority is the same)
                $extensionClassPriorities = \array_reverse($attachableExtensionManager->getExtensionClasses($class, \PoP\ComponentModel\AttachableExtensions\AttachableExtensionGroups::DIRECTIVERESOLVERS));
                // Order them by priority: higher priority are evaluated first
                $extensionClasses = \array_keys($extensionClassPriorities);
                $extensionPriorities = \array_values($extensionClassPriorities);
                \array_multisort($extensionPriorities, \SORT_DESC, \SORT_NUMERIC, $extensionClasses);
                // Add them to the results. We keep the list of all resolvers, so that if the first one cannot process the directive (eg: through `resolveCanProcess`, the next one can do it)
                foreach ($extensionClasses as $extensionClass) {
                    $directiveName = $extensionClass::getDirectiveName();
                    $directiveNameClasses[$directiveName][] = $extensionClass;
                }
                // Continue iterating for the class parents
            } while ($class = \get_parent_class($class));
        }
        // Validate that the user has access to the directives (eg: can remove access to them for non logged-in users)
        $directiveNameClasses = $this->filterDirectiveNameClasses($directiveNameClasses);
        return $directiveNameClasses;
    }
    protected function calculateFieldNamesToResolve() : array
    {
        $attachableExtensionManager = \PoP\ComponentModel\Facades\AttachableExtensions\AttachableExtensionManagerFacade::getInstance();
        $ret = [];
        // Iterate classes from the current class towards the parent classes until finding typeResolver that satisfies processing this field
        $class = $this->getTypeResolverClassToCalculateSchema();
        do {
            foreach ($attachableExtensionManager->getExtensionClasses($class, \PoP\ComponentModel\AttachableExtensions\AttachableExtensionGroups::FIELDRESOLVERS) as $extensionClass => $extensionPriority) {
                $extensionClassFieldNames = $this->getFieldNamesResolvedByFieldResolver($extensionClass);
                $ret = \array_merge($ret, $extensionClassFieldNames);
            }
            // Continue iterating for the class parents
        } while ($class = \get_parent_class($class));
        return \array_values(\array_unique($ret));
    }
}
