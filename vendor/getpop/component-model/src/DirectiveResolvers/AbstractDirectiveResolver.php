<?php

declare (strict_types=1);
namespace PoP\ComponentModel\DirectiveResolvers;

use Exception;
use PrefixedByPoP\Composer\Semver\Semver;
use PoP\FieldQuery\QueryHelpers;
use PrefixedByPoP\League\Pipeline\StageInterface;
use PoP\ComponentModel\Environment;
use PoP\ComponentModel\Feedback\Tokens;
use PoP\ComponentModel\State\ApplicationState;
use PoP\ComponentModel\Schema\SchemaDefinition;
use PoP\ComponentModel\Directives\DirectiveTypes;
use PoP\Translation\Facades\TranslationAPIFacade;
use PoP\ComponentModel\TypeResolvers\FieldSymbols;
use PoP\ComponentModel\Versioning\VersioningHelpers;
use PoP\ComponentModel\TypeResolvers\PipelinePositions;
use PoP\ComponentModel\TypeResolvers\TypeResolverInterface;
use PoP\ComponentModel\Resolvers\FieldOrDirectiveResolverTrait;
use PoP\ComponentModel\DirectivePipeline\DirectivePipelineUtils;
use PoP\ComponentModel\Facades\Schema\FieldQueryInterpreterFacade;
use PoP\ComponentModel\AttachableExtensions\AttachableExtensionTrait;
use PoP\ComponentModel\Resolvers\ResolverTypes;
abstract class AbstractDirectiveResolver implements \PoP\ComponentModel\DirectiveResolvers\DirectiveResolverInterface, \PoP\ComponentModel\DirectiveResolvers\SchemaDirectiveResolverInterface, \PrefixedByPoP\League\Pipeline\StageInterface
{
    use AttachableExtensionTrait;
    use RemoveIDsDataFieldsDirectiveResolverTrait;
    use FieldOrDirectiveResolverTrait;
    const MESSAGE_EXPRESSIONS = 'expressions';
    /**
     * @var string
     */
    protected $directive;
    /**
     * @var array<string, array>
     */
    protected $directiveArgsForSchema = [];
    /**
     * @var array<string, array>
     */
    protected $directiveArgsForResultItems = [];
    /**
     * @var array[]
     */
    protected $nestedDirectivePipelineData = [];
    public function __construct(?string $directive = null)
    {
        // If the directive is not provided, then it directly the directive name
        // This allows to instantiate the directive through the DependencyInjection component
        $this->directive = $directive ?? $this->getDirectiveName();
    }
    /**
     * Directives can be either of type "Schema" or "Query" and,
     * depending on one case or the other, might be exposed to the user.
     * By default, use the Query type
     *
     * @return string
     */
    public function getDirectiveType() : string
    {
        return \PoP\ComponentModel\Directives\DirectiveTypes::QUERY;
    }
    /**
     * If a directive does not operate over the resultItems, then it must not allow to add fields or dynamic values in the directive arguments
     * Otherwise, it can lead to errors, since the field would never be transformed/casted to the expected type
     * Eg: <cacheControl(maxAge:id())>
     *
     * @return bool
     */
    protected function disableDynamicFieldsFromDirectiveArgs() : bool
    {
        return \false;
    }
    public function dissectAndValidateDirectiveForSchema(\PoP\ComponentModel\TypeResolvers\TypeResolverInterface $typeResolver, array &$fieldDirectiveFields, array &$variables, array &$schemaErrors, array &$schemaWarnings, array &$schemaDeprecations, array &$schemaNotices, array &$schemaTraces) : array
    {
        $translationAPI = \PoP\Translation\Facades\TranslationAPIFacade::getInstance();
        $fieldQueryInterpreter = \PoP\ComponentModel\Facades\Schema\FieldQueryInterpreterFacade::getInstance();
        // If it has nestedDirectives, extract them and validate them
        $nestedFieldDirectives = $fieldQueryInterpreter->getFieldDirectives($this->directive, \false);
        if ($nestedFieldDirectives) {
            $nestedDirectiveSchemaErrors = $nestedDirectiveSchemaWarnings = $nestedDirectiveSchemaDeprecations = $nestedDirectiveSchemaNotices = $nestedDirectiveSchemaTraces = [];
            $nestedFieldDirectives = \PoP\FieldQuery\QueryHelpers::splitFieldDirectives($nestedFieldDirectives);
            // Support repeated fields by adding a counter next to them
            if (\count($nestedFieldDirectives) != \count(\array_unique($nestedFieldDirectives))) {
                // Find the repeated fields, and add a counter next to them
                $expandedNestedFieldDirectives = [];
                $counters = [];
                foreach ($nestedFieldDirectives as $nestedFieldDirective) {
                    if (!isset($counters[$nestedFieldDirective])) {
                        $expandedNestedFieldDirectives[] = $nestedFieldDirective;
                        $counters[$nestedFieldDirective] = 1;
                    } else {
                        $expandedNestedFieldDirectives[] = $nestedFieldDirective . \PoP\ComponentModel\TypeResolvers\FieldSymbols::REPEATED_DIRECTIVE_COUNTER_SEPARATOR . $counters[$nestedFieldDirective];
                        $counters[$nestedFieldDirective]++;
                    }
                }
                $nestedFieldDirectives = $expandedNestedFieldDirectives;
            }
            // Each composed directive will deal with the same fields as the current directive
            $nestedFieldDirectiveFields = $fieldDirectiveFields;
            foreach ($nestedFieldDirectives as $nestedFieldDirective) {
                $nestedFieldDirectiveFields[$nestedFieldDirective] = $fieldDirectiveFields[$this->directive];
            }
            $this->nestedDirectivePipelineData = $typeResolver->resolveDirectivesIntoPipelineData($nestedFieldDirectives, $nestedFieldDirectiveFields, \true, $variables, $nestedDirectiveSchemaErrors, $nestedDirectiveSchemaWarnings, $nestedDirectiveSchemaDeprecations, $nestedDirectiveSchemaNotices, $nestedDirectiveSchemaTraces);
            foreach ($nestedDirectiveSchemaDeprecations as $nestedDirectiveSchemaDeprecation) {
                $schemaDeprecations[] = [\PoP\ComponentModel\Feedback\Tokens::PATH => \array_merge([$this->directive], $nestedDirectiveSchemaDeprecation[\PoP\ComponentModel\Feedback\Tokens::PATH]), \PoP\ComponentModel\Feedback\Tokens::MESSAGE => $nestedDirectiveSchemaDeprecation[\PoP\ComponentModel\Feedback\Tokens::MESSAGE]];
            }
            foreach ($nestedDirectiveSchemaWarnings as $nestedDirectiveSchemaWarning) {
                $schemaWarnings[] = [\PoP\ComponentModel\Feedback\Tokens::PATH => \array_merge([$this->directive], $nestedDirectiveSchemaWarning[\PoP\ComponentModel\Feedback\Tokens::PATH]), \PoP\ComponentModel\Feedback\Tokens::MESSAGE => $nestedDirectiveSchemaWarning[\PoP\ComponentModel\Feedback\Tokens::MESSAGE]];
            }
            foreach ($nestedDirectiveSchemaNotices as $nestedDirectiveSchemaNotice) {
                $schemaNotices[] = [\PoP\ComponentModel\Feedback\Tokens::PATH => \array_merge([$this->directive], $nestedDirectiveSchemaNotice[\PoP\ComponentModel\Feedback\Tokens::PATH]), \PoP\ComponentModel\Feedback\Tokens::MESSAGE => $nestedDirectiveSchemaNotice[\PoP\ComponentModel\Feedback\Tokens::MESSAGE]];
            }
            foreach ($nestedDirectiveSchemaTraces as $nestedDirectiveSchemaTrace) {
                $schemaTraces[] = [\PoP\ComponentModel\Feedback\Tokens::PATH => \array_merge([$this->directive], $nestedDirectiveSchemaTrace[\PoP\ComponentModel\Feedback\Tokens::PATH]), \PoP\ComponentModel\Feedback\Tokens::MESSAGE => $nestedDirectiveSchemaTrace[\PoP\ComponentModel\Feedback\Tokens::MESSAGE]];
            }
            // If there is any error, then we also can't proceed with the current directive
            if ($nestedDirectiveSchemaErrors) {
                foreach ($nestedDirectiveSchemaErrors as $nestedDirectiveSchemaError) {
                    $schemaErrors[] = [\PoP\ComponentModel\Feedback\Tokens::PATH => \array_merge([$this->directive], $nestedDirectiveSchemaError[\PoP\ComponentModel\Feedback\Tokens::PATH]), \PoP\ComponentModel\Feedback\Tokens::MESSAGE => $nestedDirectiveSchemaError[\PoP\ComponentModel\Feedback\Tokens::MESSAGE]];
                }
                $schemaErrors[] = [\PoP\ComponentModel\Feedback\Tokens::PATH => [$this->directive], \PoP\ComponentModel\Feedback\Tokens::MESSAGE => $translationAPI->__('This directive can\'t be executed due to errors from its composed directives', 'component-model')];
                return [null];
            }
        }
        // First validate schema (eg of error in schema: ?query=posts<include(if:this-field-doesnt-exist())>)
        list($validDirective, $directiveName, $directiveArgs, $directiveSchemaErrors, $directiveSchemaWarnings, $directiveSchemaDeprecations) = $fieldQueryInterpreter->extractDirectiveArgumentsForSchema($this, $typeResolver, $this->directive, $variables, $this->disableDynamicFieldsFromDirectiveArgs());
        // Store the args, they may be used in `resolveDirective`
        $this->directiveArgsForSchema = $directiveArgs;
        // If there were errors, warning or deprecations, integrate them into the feedback objects
        $schemaErrors = \array_merge($schemaErrors, $directiveSchemaErrors);
        // foreach ($directiveSchemaErrors as $directiveSchemaError) {
        //     $schemaErrors[] = [
        //         Tokens::PATH => array_merge([$this->directive], $directiveSchemaError[Tokens::PATH]),
        //         Tokens::MESSAGE => $directiveSchemaError[Tokens::MESSAGE],
        //     ];
        // }
        $schemaWarnings = \array_merge($schemaWarnings, $directiveSchemaWarnings);
        // foreach ($directiveSchemaWarnings as $directiveSchemaWarning) {
        //     $schemaWarnings[] = [
        //         Tokens::PATH => array_merge([$this->directive], $directiveSchemaWarning[Tokens::PATH]),
        //         Tokens::MESSAGE => $directiveSchemaWarning[Tokens::MESSAGE],
        //     ];
        // }
        $schemaDeprecations = \array_merge($schemaDeprecations, $directiveSchemaDeprecations);
        // foreach ($directiveSchemaDeprecations as $directiveSchemaDeprecation) {
        //     $schemaDeprecations[] = [
        //         Tokens::PATH => array_merge([$this->directive], $directiveSchemaDeprecation[Tokens::PATH]),
        //         Tokens::MESSAGE => $directiveSchemaDeprecation[Tokens::MESSAGE],
        //     ];
        // }
        return [$validDirective, $directiveName, $directiveArgs];
    }
    /**
     * By default, validate if there are deprecated fields
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
        if ($maybeDeprecation = $this->resolveSchemaDirectiveDeprecationDescription($typeResolver, $directiveName, $directiveArgs)) {
            $schemaDeprecations[] = [\PoP\ComponentModel\Feedback\Tokens::PATH => [$this->directive], \PoP\ComponentModel\Feedback\Tokens::MESSAGE => $maybeDeprecation];
        }
        return $directiveArgs;
    }
    /**
     * @param object $resultItem
     */
    public function dissectAndValidateDirectiveForResultItem(\PoP\ComponentModel\TypeResolvers\TypeResolverInterface $typeResolver, $resultItem, array &$variables, array &$expressions, array &$dbErrors, array &$dbWarnings, array &$dbDeprecations) : array
    {
        $fieldQueryInterpreter = \PoP\ComponentModel\Facades\Schema\FieldQueryInterpreterFacade::getInstance();
        list($validDirective, $directiveName, $directiveArgs, $nestedDBErrors, $nestedDBWarnings) = $fieldQueryInterpreter->extractDirectiveArgumentsForResultItem($this, $typeResolver, $resultItem, $this->directive, $variables, $expressions);
        // Store the args, they may be used in `resolveDirective`
        $this->directiveArgsForResultItems[$typeResolver->getID($resultItem)] = $directiveArgs;
        if ($nestedDBWarnings || $nestedDBErrors) {
            foreach ($nestedDBErrors as $id => $fieldOutputKeyErrorMessages) {
                $dbErrors[$id] = \array_merge($dbErrors[$id] ?? [], $fieldOutputKeyErrorMessages);
            }
            foreach ($nestedDBWarnings as $id => $fieldOutputKeyWarningMessages) {
                $dbWarnings[$id] = \array_merge($dbWarnings[$id] ?? [], $fieldOutputKeyWarningMessages);
            }
        }
        return [$validDirective, $directiveName, $directiveArgs];
    }
    /**
     * Indicate to what fieldNames this directive can be applied.
     * Returning an empty array means all of them
     *
     * @return array
     */
    public static function getFieldNamesToApplyTo() : array
    {
        // By default, apply to all fieldNames
        return [];
    }
    /**
     * Define if to use the version to decide if to process the directive or not
     *
     * @param TypeResolverInterface $typeResolver
     * @return boolean
     */
    public function decideCanProcessBasedOnVersionConstraint(\PoP\ComponentModel\TypeResolvers\TypeResolverInterface $typeResolver) : bool
    {
        return \false;
    }
    /**
     * By default, the directiveResolver instance can process the directive
     * This function can be overriden to force certain value on the directive args before it can be executed
     *
     * @param TypeResolverInterface $typeResolver
     * @param string $directiveName
     * @param array $directiveArgs
     * @return boolean
     */
    public function resolveCanProcess(\PoP\ComponentModel\TypeResolvers\TypeResolverInterface $typeResolver, string $directiveName, array $directiveArgs, string $field, array &$variables) : bool
    {
        /** Check if to validate the version */
        if (\PoP\ComponentModel\Environment::enableSemanticVersionConstraints() && $this->decideCanProcessBasedOnVersionConstraint($typeResolver)) {
            /**
             * Please notice: we can get the fieldVersion directly from this instance,
             * and not from the schemaDefinition, because the version is set at the FieldResolver level,
             * and not the FieldInterfaceResolver, which is the other entity filling data
             * inside the schemaDefinition object.
             * If this directive is tagged with a version...
             */
            if ($schemaDirectiveVersion = $this->getSchemaDirectiveVersion($typeResolver)) {
                $vars = \PoP\ComponentModel\State\ApplicationState::getVars();
                /**
                 * Get versionConstraint in this order:
                 * 1. Passed as directive argument
                 * 2. Through param `directiveVersionConstraints[$directiveName]`: specific to the directive
                 * 3. Through param `versionConstraint`: applies to all fields and directives in the query
                 */
                $versionConstraint = $directiveArgs[\PoP\ComponentModel\Schema\SchemaDefinition::ARGNAME_VERSION_CONSTRAINT] ?? \PoP\ComponentModel\Versioning\VersioningHelpers::getVersionConstraintsForDirective(static::getDirectiveName()) ?? $vars['version-constraint'];
                /**
                 * If the query doesn't restrict the version, then do not process
                 */
                if (!$versionConstraint) {
                    return \false;
                }
                /**
                 * Compare using semantic versioning constraint rules, as used by Composer
                 * If passing a wrong value to validate against (eg: "saraza" instead of "1.0.0"), it will throw an Exception
                 */
                try {
                    return \PrefixedByPoP\Composer\Semver\Semver::satisfies($schemaDirectiveVersion, $versionConstraint);
                } catch (\Exception $e) {
                    return \false;
                }
            }
        }
        return \true;
    }
    public function resolveSchemaValidationErrorDescriptions(\PoP\ComponentModel\TypeResolvers\TypeResolverInterface $typeResolver, string $directiveName, array $directiveArgs = []) : ?array
    {
        $directiveSchemaDefinition = $this->getSchemaDefinitionForDirective($typeResolver);
        if ($schemaDirectiveArgs = $directiveSchemaDefinition[\PoP\ComponentModel\Schema\SchemaDefinition::ARGNAME_ARGS] ?? null) {
            /**
             * Validate mandatory values
             */
            if ($maybeError = $this->maybeValidateNotMissingFieldOrDirectiveArguments($typeResolver, $directiveName, $directiveArgs, $schemaDirectiveArgs, \PoP\ComponentModel\Resolvers\ResolverTypes::DIRECTIVE)) {
                return [$maybeError];
            }
            /**
             * Validate enums
             */
            list($maybeError) = $this->maybeValidateEnumFieldOrDirectiveArguments($typeResolver, $directiveName, $directiveArgs, $schemaDirectiveArgs, \PoP\ComponentModel\Resolvers\ResolverTypes::DIRECTIVE);
            if ($maybeError) {
                return [$maybeError];
            }
        }
        return null;
    }
    protected function getExpressionsForResultItem($id, array &$variables, array &$messages)
    {
        // Create a custom $variables containing all the properties from $dbItems for this resultItem
        // This way, when encountering $propName in a fieldArg in a fieldResolver, it can resolve that value
        // Otherwise it can't, since the fieldResolver doesn't have access to either $dbItems
        return \array_merge($variables, $messages[self::MESSAGE_EXPRESSIONS][(string) $id] ?? []);
    }
    protected function addExpressionForResultItem($id, $key, $value, array &$messages)
    {
        return $messages[self::MESSAGE_EXPRESSIONS][(string) $id][$key] = $value;
    }
    protected function getExpressionForResultItem($id, $key, array &$messages)
    {
        return $messages[self::MESSAGE_EXPRESSIONS][(string) $id][$key];
    }
    /**
     * By default, place the directive after the ResolveAndMerge directive, so the property will be in $dbItems by then
     *
     * @return void
     */
    public function getPipelinePosition() : string
    {
        return \PoP\ComponentModel\TypeResolvers\PipelinePositions::AFTER_RESOLVE;
    }
    /**
     * By default, a directive can be executed only one time for "Schema" and "System"
     * type directives (eg: <translate(en,es),translate(es,en)>),
     * and many times for the other types, "Query" and "Scripting"
     *
     * @return boolean
     */
    public function isRepeatable() : bool
    {
        return !($this->getDirectiveType() == \PoP\ComponentModel\Directives\DirectiveTypes::SYSTEM || $this->getDirectiveType() == \PoP\ComponentModel\Directives\DirectiveTypes::SCHEMA);
    }
    /**
     * Indicate if the directive needs to be passed $idsDataFields filled with data to be able to execute
     * Because most commonly it will need, the default value is `true`
     *
     * @return void
     */
    public function needsIDsDataFieldsToExecute() : bool
    {
        return \true;
    }
    /**
     * Indicate that there is data in variable $idsDataFields
     *
     * @param array $idsDataFields
     * @return boolean
     */
    protected function hasIDsDataFields(array &$idsDataFields) : bool
    {
        foreach ($idsDataFields as $id => &$data_fields) {
            if ($data_fields['direct'] ?? null) {
                // If there's data-fields to fetch for any ID, that's it, there's data
                return \true;
            }
        }
        // If we reached here, there is no data
        return \false;
    }
    public function getSchemaDirectiveVersion(\PoP\ComponentModel\TypeResolvers\TypeResolverInterface $typeResolver) : ?string
    {
        return null;
    }
    public function enableOrderedSchemaDirectiveArgs(\PoP\ComponentModel\TypeResolvers\TypeResolverInterface $typeResolver) : bool
    {
        if ($schemaDefinitionResolver = $this->getSchemaDefinitionResolver($typeResolver)) {
            return $schemaDefinitionResolver->enableOrderedSchemaDirectiveArgs($typeResolver);
        }
        return \true;
    }
    public function getSchemaDirectiveArgs(\PoP\ComponentModel\TypeResolvers\TypeResolverInterface $typeResolver) : array
    {
        if ($schemaDefinitionResolver = $this->getSchemaDefinitionResolver($typeResolver)) {
            return $schemaDefinitionResolver->getSchemaDirectiveArgs($typeResolver);
        }
        return [];
    }
    public function getFilteredSchemaDirectiveArgs(\PoP\ComponentModel\TypeResolvers\TypeResolverInterface $typeResolver) : array
    {
        if ($schemaDefinitionResolver = $this->getSchemaDefinitionResolver($typeResolver)) {
            $schemaDirectiveArgs = $schemaDefinitionResolver->getSchemaDirectiveArgs($typeResolver);
        } else {
            $schemaDirectiveArgs = [];
        }
        $this->maybeAddVersionConstraintSchemaFieldOrDirectiveArg($schemaDirectiveArgs, !empty($this->getSchemaDirectiveVersion($typeResolver)));
        return $schemaDirectiveArgs;
    }
    public function getSchemaDirectiveDeprecationDescription(\PoP\ComponentModel\TypeResolvers\TypeResolverInterface $typeResolver) : ?string
    {
        if ($schemaDefinitionResolver = $this->getSchemaDefinitionResolver($typeResolver)) {
            return $schemaDefinitionResolver->getSchemaDirectiveDeprecationDescription($typeResolver);
        }
        return null;
    }
    public function resolveSchemaDirectiveDeprecationDescription(\PoP\ComponentModel\TypeResolvers\TypeResolverInterface $typeResolver, string $directiveName, array $directiveArgs = []) : ?string
    {
        $directiveSchemaDefinition = $this->getSchemaDefinitionForDirective($typeResolver);
        if ($schemaDirectiveArgs = $directiveSchemaDefinition[\PoP\ComponentModel\Schema\SchemaDefinition::ARGNAME_ARGS] ?? null) {
            list($maybeError, $maybeDeprecation) = $this->maybeValidateEnumFieldOrDirectiveArguments($typeResolver, $directiveName, $directiveArgs, $schemaDirectiveArgs, \PoP\ComponentModel\Resolvers\ResolverTypes::DIRECTIVE);
            if ($maybeDeprecation) {
                return $maybeDeprecation;
            }
        }
        return null;
    }
    public function getSchemaDirectiveWarningDescription(\PoP\ComponentModel\TypeResolvers\TypeResolverInterface $typeResolver) : ?string
    {
        if ($schemaDefinitionResolver = $this->getSchemaDefinitionResolver($typeResolver)) {
            return $schemaDefinitionResolver->getSchemaDirectiveWarningDescription($typeResolver);
        }
        return null;
    }
    public function resolveSchemaDirectiveWarningDescription(\PoP\ComponentModel\TypeResolvers\TypeResolverInterface $typeResolver) : ?string
    {
        if (\PoP\ComponentModel\Environment::enableSemanticVersionConstraints()) {
            /**
             * If restricting the version, and this fieldResolver doesn't have any version, then show a warning
             */
            if ($versionConstraint = $this->directiveArgsForSchema[\PoP\ComponentModel\Schema\SchemaDefinition::ARGNAME_VERSION_CONSTRAINT] ?? null) {
                /**
                 * If this fieldResolver doesn't have versioning, then it accepts everything
                 */
                if (!$this->decideCanProcessBasedOnVersionConstraint($typeResolver)) {
                    $translationAPI = \PoP\Translation\Facades\TranslationAPIFacade::getInstance();
                    return \sprintf($translationAPI->__('The DirectiveResolver used to process directive \'%s\' (which has version \'%s\') does not pay attention to the version constraint; hence, argument \'versionConstraint\', with value \'%s\', was ignored', 'component-model'), $this->getDirectiveName(), $this->getSchemaDirectiveVersion($typeResolver) ?? '', $versionConstraint);
                }
            }
        }
        return $this->getSchemaDirectiveWarningDescription($typeResolver);
    }
    public function getSchemaDirectiveExpressions(\PoP\ComponentModel\TypeResolvers\TypeResolverInterface $typeResolver) : array
    {
        if ($schemaDefinitionResolver = $this->getSchemaDefinitionResolver($typeResolver)) {
            return $schemaDefinitionResolver->getSchemaDirectiveExpressions($typeResolver);
        }
        return [];
    }
    public function getSchemaDirectiveDescription(\PoP\ComponentModel\TypeResolvers\TypeResolverInterface $typeResolver) : ?string
    {
        if ($schemaDefinitionResolver = $this->getSchemaDefinitionResolver($typeResolver)) {
            return $schemaDefinitionResolver->getSchemaDirectiveDescription($typeResolver);
        }
        return null;
    }
    public function isGlobal(\PoP\ComponentModel\TypeResolvers\TypeResolverInterface $typeResolver) : bool
    {
        if ($schemaDefinitionResolver = $this->getSchemaDefinitionResolver($typeResolver)) {
            return $schemaDefinitionResolver->isGlobal($typeResolver);
        }
        return \false;
    }
    public function __invoke($payload)
    {
        // 1. Extract the arguments from the payload
        // $pipelineIDsDataFields is an array containing all stages of the pipe
        // The one corresponding to the current stage is at the head. Take it out from there,
        // and keep passing down the rest of the array to the next stages
        list($typeResolver, $pipelineIDsDataFields, $pipelineDirectiveResolverInstances, $resultIDItems, $unionDBKeyIDs, $dbItems, $previousDBItems, $variables, $messages, $dbErrors, $dbWarnings, $dbDeprecations, $dbNotices, $dbTraces, $schemaErrors, $schemaWarnings, $schemaDeprecations, $schemaNotices, $schemaTraces) = \PoP\ComponentModel\DirectivePipeline\DirectivePipelineUtils::extractArgumentsFromPayload($payload);
        // Extract the head, keep passing down the rest
        $idsDataFields = $pipelineIDsDataFields[0];
        \array_shift($pipelineIDsDataFields);
        // The $pipelineDirectiveResolverInstances is the series of directives executed in the pipeline
        // The current stage is at the head. Remove it
        \array_shift($pipelineDirectiveResolverInstances);
        // // 2. Validate operation
        // $this->validateDirective(
        //     $typeResolver,
        //     $idsDataFields,
        //     $pipelineIDsDataFields,
        //     $pipelineDirectiveResolverInstances,
        //     $resultIDItems,
        //     $dbItems,
        //     $previousDBItems,
        //     $variables,
        //     $messages,
        //     $dbErrors,
        //     $dbWarnings,
        //     $dbDeprecations,
        //     $dbNotices,
        //     $dbTraces,
        //     $schemaErrors,
        //     $schemaWarnings,
        //     $schemaDeprecations,
        //     $schemaNotices,
        //     $schemaTraces
        // );
        // 2. Execute operation.
        // First check that if the validation took away the elements, and so the directive can't execute anymore
        // For instance, executing ?query=posts.id|title<default,translate(from:en,to:es)> will fail
        // after directive "default", so directive "translate" must not even execute
        if (!$this->needsIDsDataFieldsToExecute() || $this->hasIDsDataFields($idsDataFields)) {
            $this->resolveDirective($typeResolver, $idsDataFields, $pipelineIDsDataFields, $pipelineDirectiveResolverInstances, $resultIDItems, $unionDBKeyIDs, $dbItems, $previousDBItems, $variables, $messages, $dbErrors, $dbWarnings, $dbDeprecations, $dbNotices, $dbTraces, $schemaErrors, $schemaWarnings, $schemaDeprecations, $schemaNotices, $schemaTraces);
        }
        // 3. Re-create the payload from the modified variables
        return \PoP\ComponentModel\DirectivePipeline\DirectivePipelineUtils::convertArgumentsToPayload($typeResolver, $pipelineIDsDataFields, $pipelineDirectiveResolverInstances, $resultIDItems, $unionDBKeyIDs, $dbItems, $previousDBItems, $variables, $messages, $dbErrors, $dbWarnings, $dbDeprecations, $dbNotices, $dbTraces, $schemaErrors, $schemaWarnings, $schemaDeprecations, $schemaNotices, $schemaTraces);
    }
    // public function validateDirective(TypeResolverInterface $typeResolver, array &$idsDataFields, array &$succeedingPipelineIDsDataFields, array &$resultIDItems, array &$dbItems, array &$previousDBItems, array &$variables, array &$messages, array &$dbErrors, array &$dbWarnings, array &$dbDeprecations, array &$schemaErrors, array &$schemaWarnings, array &$schemaDeprecations)
    // {
    //     // Check that the directive can be applied to all provided fields
    //     $this->validateAndFilterFieldsForDirective($idsDataFields, $schemaErrors, $schemaWarnings);
    // }
    // /**
    //  * Check that the directive can be applied to all provided fields
    //  *
    //  * @param array $idsDataFields
    //  * @param array $schemaErrors
    //  * @return void
    //  */
    // protected function validateAndFilterFieldsForDirective(array &$idsDataFields, array &$schemaErrors, array &$schemaWarnings)
    // {
    //     $directiveSupportedFieldNames = $this->getFieldNamesToApplyTo();
    //     // If this function returns an empty array, then it supports all fields, then do nothing
    //     if (!$directiveSupportedFieldNames) {
    //         return;
    //     }
    //     // Check if all fields are supported by this directive
    //     $fieldQueryInterpreter = FieldQueryInterpreterFacade::getInstance();
    //     $failedFields = [];
    //     foreach ($idsDataFields as $id => &$data_fields) {
    //         // Get the fieldName for each field
    //         $nameFields = [];
    //         foreach ($data_fields['direct'] as $field) {
    //             $nameFields[$fieldQueryInterpreter->getFieldName($field)] = $field;
    //         }
    //         // If any fieldName failed, remove it from the list of fields to execute for this directive
    //         if ($unsupportedFieldNames = array_diff(array_keys($nameFields), $directiveSupportedFieldNames)) {
    //             $unsupportedFields = array_map(
    //                 function($fieldName) use ($nameFields) {
    //                     return $nameFields[$fieldName];
    //                 },
    //                 $unsupportedFieldNames
    //             );
    //             $failedFields = array_values(array_unique(array_merge(
    //                 $failedFields,
    //                 $unsupportedFields
    //             )));
    //         }
    //     }
    //     // Give an error message for all failed fields
    //     if ($failedFields) {
    //         $translationAPI = TranslationAPIFacade::getInstance();
    //         $directiveName = $this->getDirectiveName();
    //         $failedFieldNames = array_map(
    //             [$fieldQueryInterpreter, 'getFieldName'],
    //             $failedFields
    //         );
    //         if (count($failedFields) == 1) {
    //             $message = $translationAPI->__('Directive \'%s\' doesn\'t support field \'%s\' (the only supported field names are: \'%s\')', 'component-model');
    //         } else {
    //             $message = $translationAPI->__('Directive \'%s\' doesn\'t support fields \'%s\' (the only supported field names are: \'%s\')', 'component-model');
    //         }
    //         $failureMessage = sprintf(
    //             $message,
    //             $directiveName,
    //             implode($translationAPI->__('\', \''), $failedFieldNames),
    //             implode($translationAPI->__('\', \''), $directiveSupportedFieldNames)
    //         );
    //         $this->processFailure($failureMessage, $failedFields, $idsDataFields, $schemaErrors, $schemaWarnings);
    //     }
    // }
    /**
     * Depending on environment configuration, either show a warning,
     * or show an error and remove the fields from the directive pipeline for further execution
     *
     * @param array $schemaErrors
     * @param array $schemaWarnings
     * @return void
     */
    protected function processFailure(string $failureMessage, array $failedFields, array &$idsDataFields, array &$succeedingPipelineIDsDataFields, array &$schemaErrors, array &$schemaWarnings)
    {
        $allFieldsFailed = empty($failedFields);
        if ($allFieldsFailed) {
            // Remove all fields
            $idsDataFieldsToRemove = $idsDataFields;
            // Calculate which fields are being removed, to add to the error
            foreach ($idsDataFields as $id => &$data_fields) {
                $failedFields = \array_merge($failedFields, $data_fields['direct']);
            }
            $failedFields = \array_values(\array_unique($failedFields));
        } else {
            $idsDataFieldsToRemove = [];
            // Calculate which fields to remove
            foreach ($idsDataFields as $id => &$data_fields) {
                $idsDataFieldsToRemove[(string) $id]['direct'] = \array_intersect($data_fields['direct'], $failedFields);
            }
        }
        // If the failure must be processed as an error, we must also remove the fields from the directive pipeline
        $removeFieldIfDirectiveFailed = \PoP\ComponentModel\Environment::removeFieldIfDirectiveFailed();
        if ($removeFieldIfDirectiveFailed) {
            $this->removeIDsDataFields($idsDataFieldsToRemove, $succeedingPipelineIDsDataFields);
        }
        // Show the failureMessage either as error or as warning
        // $fieldQueryInterpreter = FieldQueryInterpreterFacade::getInstance();
        $translationAPI = \PoP\Translation\Facades\TranslationAPIFacade::getInstance();
        $directiveName = $this->getDirectiveName();
        // $failedFieldNames = array_map(
        //     [$fieldQueryInterpreter, 'getFieldName'],
        //     $failedFields
        // );
        if ($removeFieldIfDirectiveFailed) {
            if (\count($failedFields) == 1) {
                $message = $translationAPI->__('%s. Field \'%s\' has been removed from the directive pipeline', 'component-model');
            } else {
                $message = $translationAPI->__('%s. Fields \'%s\' have been removed from the directive pipeline', 'component-model');
            }
            $schemaErrors[] = [\PoP\ComponentModel\Feedback\Tokens::PATH => [\implode($translationAPI->__('\', \''), $failedFields), $this->directive], \PoP\ComponentModel\Feedback\Tokens::MESSAGE => \sprintf($message, $failureMessage, \implode($translationAPI->__('\', \''), $failedFields))];
        } else {
            if (\count($failedFields) == 1) {
                $message = $translationAPI->__('%s. Execution of directive \'%s\' has been ignored on field \'%s\'', 'component-model');
            } else {
                $message = $translationAPI->__('%s. Execution of directive \'%s\' has been ignored on fields \'%s\'', 'component-model');
            }
            $schemaWarnings[] = [\PoP\ComponentModel\Feedback\Tokens::PATH => [\implode($translationAPI->__('\', \''), $failedFields), $this->directive], \PoP\ComponentModel\Feedback\Tokens::MESSAGE => \sprintf($message, $failureMessage, $directiveName, \implode($translationAPI->__('\', \''), $failedFields))];
        }
    }
    public function getSchemaDefinitionResolver(\PoP\ComponentModel\TypeResolvers\TypeResolverInterface $typeResolver) : ?\PoP\ComponentModel\DirectiveResolvers\SchemaDirectiveResolverInterface
    {
        return null;
    }
    /**
     * Directives may not be directly visible in the schema,
     * eg: because their name is duplicated across directives (eg: "cacheControl")
     * or because they are used through code (eg: "validateIsUserLoggedIn")
     *
     * @return boolean
     */
    public function skipAddingToSchemaDefinition() : bool
    {
        return \false;
    }
    public function getSchemaDefinitionForDirective(\PoP\ComponentModel\TypeResolvers\TypeResolverInterface $typeResolver) : array
    {
        $directiveName = $this->getDirectiveName();
        $schemaDefinition = [\PoP\ComponentModel\Schema\SchemaDefinition::ARGNAME_NAME => $directiveName, \PoP\ComponentModel\Schema\SchemaDefinition::ARGNAME_DIRECTIVE_TYPE => $this->getDirectiveType(), \PoP\ComponentModel\Schema\SchemaDefinition::ARGNAME_DIRECTIVE_PIPELINE_POSITION => $this->getPipelinePosition(), \PoP\ComponentModel\Schema\SchemaDefinition::ARGNAME_DIRECTIVE_IS_REPEATABLE => $this->isRepeatable(), \PoP\ComponentModel\Schema\SchemaDefinition::ARGNAME_DIRECTIVE_NEEDS_DATA_TO_EXECUTE => $this->needsIDsDataFieldsToExecute()];
        if ($limitedToFields = $this::getFieldNamesToApplyTo()) {
            $schemaDefinition[\PoP\ComponentModel\Schema\SchemaDefinition::ARGNAME_DIRECTIVE_LIMITED_TO_FIELDS] = $limitedToFields;
        }
        if ($schemaDefinitionResolver = $this->getSchemaDefinitionResolver($typeResolver)) {
            if ($description = $schemaDefinitionResolver->getSchemaDirectiveDescription($typeResolver)) {
                $schemaDefinition[\PoP\ComponentModel\Schema\SchemaDefinition::ARGNAME_DESCRIPTION] = $description;
            }
            if ($expressions = $schemaDefinitionResolver->getSchemaDirectiveExpressions($typeResolver)) {
                $schemaDefinition[\PoP\ComponentModel\Schema\SchemaDefinition::ARGNAME_DIRECTIVE_EXPRESSIONS] = $expressions;
            }
            if ($deprecationDescription = $schemaDefinitionResolver->getSchemaDirectiveDeprecationDescription($typeResolver)) {
                $schemaDefinition[\PoP\ComponentModel\Schema\SchemaDefinition::ARGNAME_DEPRECATED] = \true;
                $schemaDefinition[\PoP\ComponentModel\Schema\SchemaDefinition::ARGNAME_DEPRECATIONDESCRIPTION] = $deprecationDescription;
            }
            if ($args = $schemaDefinitionResolver->getFilteredSchemaDirectiveArgs($typeResolver)) {
                // Add the args under their name
                $nameArgs = [];
                foreach ($args as $arg) {
                    $nameArgs[$arg[\PoP\ComponentModel\Schema\SchemaDefinition::ARGNAME_NAME]] = $arg;
                }
                $schemaDefinition[\PoP\ComponentModel\Schema\SchemaDefinition::ARGNAME_ARGS] = $nameArgs;
            }
        }
        /**
         * Please notice: the version always comes from the directiveResolver, and not from the schemaDefinitionResolver
         * That is because it is the implementer the one who knows what version it is, and not the one defining the interface
         * If the interface changes, the implementer will need to change, so the version will be upgraded
         * But it could also be that the contract doesn't change, but the implementation changes
         * it's really not their responsibility
         */
        if (\PoP\ComponentModel\Environment::enableSemanticVersionConstraints()) {
            if ($version = $this->getSchemaDirectiveVersion($typeResolver)) {
                $schemaDefinition[\PoP\ComponentModel\Schema\SchemaDefinition::ARGNAME_VERSION] = $version;
            }
        }
        $this->addSchemaDefinitionForDirective($schemaDefinition);
        return $schemaDefinition;
    }
    /**
     * Function to override
     */
    protected function addSchemaDefinitionForDirective(array &$schemaDefinition)
    {
    }
}
