<?php

declare (strict_types=1);
namespace PoP\ComponentModel\DirectiveResolvers;

use PoP\ComponentModel\DirectiveResolvers\FieldDirectiveResolverInterface;
use PoP\ComponentModel\FeedbackItemProviders\ErrorFeedbackItemProvider;
use PoP\ComponentModel\Feedback\EngineIterationFeedbackStore;
use PoP\ComponentModel\Feedback\SchemaFeedback;
use PoP\ComponentModel\Schema\SchemaTypeModifiers;
use PoP\ComponentModel\TypeResolvers\ConcreteTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\InputTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\RelationalTypeResolverInterface;
use PoP\GraphQLParser\ExtendedSpec\Parser\Ast\MetaDirective;
use PoP\GraphQLParser\Module;
use PoP\GraphQLParser\ModuleConfiguration;
use PoP\GraphQLParser\Spec\Parser\Ast\Directive;
use PoP\GraphQLParser\Spec\Parser\Ast\FieldInterface;
use PoP\Root\App;
use PoP\Root\Feedback\FeedbackItemResolution;
use SplObjectStorage;
abstract class AbstractMetaFieldDirectiveResolver extends \PoP\ComponentModel\DirectiveResolvers\AbstractFieldDirectiveResolver implements \PoP\ComponentModel\DirectiveResolvers\MetaFieldDirectiveResolverInterface
{
    /** @var SplObjectStorage<FieldDirectiveResolverInterface,FieldInterface[]> */
    protected $nestedDirectivePipelineData;
    public function isDirectiveEnabled() : bool
    {
        /** @var ModuleConfiguration */
        $moduleConfiguration = App::getModule(Module::class)->getConfiguration();
        if (!$moduleConfiguration->enableComposableDirectives()) {
            return \false;
        }
        return parent::isDirectiveEnabled();
    }
    /**
     * If it has nestedDirectives, extract them and validate them
     *
     * @param FieldInterface[] $fields
     * @param \PoP\ComponentModel\TypeResolvers\RelationalTypeResolverInterface $relationalTypeResolver
     * @param \PoP\ComponentModel\Feedback\EngineIterationFeedbackStore $engineIterationFeedbackStore
     */
    public function prepareDirective($relationalTypeResolver, $fields, $engineIterationFeedbackStore) : void
    {
        parent::prepareDirective($relationalTypeResolver, $fields, $engineIterationFeedbackStore);
        if ($this->hasValidationErrors) {
            return;
        }
        $nestedDirectivePipelineData = $this->getNestedDirectivePipelineData($relationalTypeResolver, $fields, $engineIterationFeedbackStore);
        if ($nestedDirectivePipelineData === null) {
            $this->setHasValidationErrors(\true);
            return;
        }
        $this->nestedDirectivePipelineData = $nestedDirectivePipelineData;
    }
    /**
     * @param FieldInterface[] $fields
     * @return SplObjectStorage<FieldDirectiveResolverInterface,FieldInterface[]>|null
     * @param \PoP\ComponentModel\TypeResolvers\RelationalTypeResolverInterface $relationalTypeResolver
     * @param \PoP\ComponentModel\Feedback\EngineIterationFeedbackStore $engineIterationFeedbackStore
     */
    protected function getNestedDirectivePipelineData($relationalTypeResolver, $fields, $engineIterationFeedbackStore) : ?SplObjectStorage
    {
        /**
         * If any Meta Directive doesn't have any composed directives,
         * then the Parser will not cast it to MetaDirective.
         *
         * Eg:
         *
         * ```
         * {
         *   posts {
         *     categoryNames
         *       @forEach
         *         ## Nothing here!
         *   }
         * }
         * ```
         */
        if (!$this->directive instanceof MetaDirective) {
            $engineIterationFeedbackStore->schemaFeedbackStore->addError(new SchemaFeedback(new FeedbackItemResolution(ErrorFeedbackItemProvider::class, ErrorFeedbackItemProvider::E5, [$this->getDirectiveName()]), $this->directive, $relationalTypeResolver, $fields));
            return null;
        }
        /** @var MetaDirective */
        $metaDirective = $this->directive;
        $nestedDirectives = $metaDirective->getNestedDirectives();
        /**
         * Validate that there are composed directives
         */
        if ($nestedDirectives === []) {
            $engineIterationFeedbackStore->schemaFeedbackStore->addError(new SchemaFeedback(new FeedbackItemResolution(ErrorFeedbackItemProvider::class, ErrorFeedbackItemProvider::E5, [$this->getDirectiveName()]), $this->directive, $relationalTypeResolver, $fields));
            return null;
        }
        $appStateManager = App::getAppStateManager();
        /**
         * Each composed directive will deal with the same fields
         * as the current directive.
         *
         * @var SplObjectStorage<Directive,FieldInterface[]>
         */
        $nestedDirectiveFields = new SplObjectStorage();
        foreach ($nestedDirectives as $nestedDirective) {
            $nestedDirectiveFields[$nestedDirective] = $fields;
        }
        $errorCount = $engineIterationFeedbackStore->getErrorCount();
        /**
         * Modify the field type being processed to DangerouslyNonScalar.
         *
         * Originally being the one from the field, this avoids validating
         * if the directives in the downstream-nested-pipeline
         * can process the field or not.
         *
         * For instance, @forEach modifies the type modifiers
         * from [[String]] => [String], so the underlying type,
         * `String`, does not change.
         *
         * However, @underJSONObjectProperty modifies the type
         * from JSONObject to whatever value is contained under
         * that entry (maybe Scalar, maybe Int), so represent
         * it as DangerouslyNonScalar.
         *
         * First check that the AppState has not been set further upstream!
         * If it has, keep that TypeResolver (eg: directive
         * @underJSONObjectProperty could be applied twice).
         */
        $currentSupportedDirectiveResolutionFieldTypeResolver = null;
        $mustChangeProcessingFieldTypeToDangerouslyNonScalarForSupportedNestedDirectivesResolution = $this->mustChangeProcessingFieldTypeToDangerouslyNonScalarForSupportedNestedDirectivesResolution();
        if ($mustChangeProcessingFieldTypeToDangerouslyNonScalarForSupportedNestedDirectivesResolution) {
            /** @var ConcreteTypeResolverInterface|null */
            $currentSupportedDirectiveResolutionFieldTypeResolver = App::getState('field-type-resolver-for-supported-directive-resolution');
            $appStateManager->override('field-type-resolver-for-supported-directive-resolution', $this->getDangerouslyNonSpecificScalarTypeScalarTypeResolver());
        }
        $nestedDirectivePipelineData = $relationalTypeResolver->resolveDirectivesIntoPipelineData($nestedDirectives, $nestedDirectiveFields, $engineIterationFeedbackStore);
        /**
         * Restore from DangerouslyNonScalar to original field type
         */
        if ($mustChangeProcessingFieldTypeToDangerouslyNonScalarForSupportedNestedDirectivesResolution) {
            $appStateManager->override('field-type-resolver-for-supported-directive-resolution', $currentSupportedDirectiveResolutionFieldTypeResolver);
        }
        if ($engineIterationFeedbackStore->getErrorCount() > $errorCount) {
            return null;
        }
        /**
         * Validate that the directive pipeline was created successfully
         */
        if ($nestedDirectivePipelineData->count() === 0) {
            $engineIterationFeedbackStore->schemaFeedbackStore->addError(new SchemaFeedback(new FeedbackItemResolution(ErrorFeedbackItemProvider::class, ErrorFeedbackItemProvider::E5A, [$this->getDirectiveName()]), $this->directive, $relationalTypeResolver, $fields));
            return null;
        }
        return $nestedDirectivePipelineData;
    }
    /**
     * Indicate if the directive will modify the type being processed
     * to DangerouslyNonScalar (originally being the one from the field).
     *
     * This is to avoid the resolution of any downstream nested directive
     * failing, when it's been set to process a certain type only.
     *
     * Eg: `@strUpperCase` has been set to process `String`, but doing
     * `{ _request(url: ...) @underJSONObjectProperty(...) @strUpperCase }`
     * must not fail. Then, @underJSONObjectProperty indicates to
     * switch from the original JSONObject to DangerouslyNonScalar.
     */
    protected abstract function mustChangeProcessingFieldTypeToDangerouslyNonScalarForSupportedNestedDirectivesResolution() : bool;
    /**
     * Name for the directive arg to indicate which directives
     * are being nested, by indicating their relative position
     * to the meta-directive.
     *
     * Eg: @foreach(affectDirectivesUnderPos: [1]) @strTranslate
     */
    public function getAffectDirectivesUnderPosArgumentName() : string
    {
        return 'affectDirectivesUnderPos';
    }
    /**
     * This array cannot be empty!
     *
     * @return int[]
     */
    public function getAffectDirectivesUnderPosArgumentDefaultValue() : array
    {
        return [1];
    }
    /**
     * @return array<string,InputTypeResolverInterface>
     * @param \PoP\ComponentModel\TypeResolvers\RelationalTypeResolverInterface $relationalTypeResolver
     */
    public function getDirectiveArgNameTypeResolvers($relationalTypeResolver) : array
    {
        return \array_merge(parent::getDirectiveArgNameTypeResolvers($relationalTypeResolver), [$this->getAffectDirectivesUnderPosArgumentName() => $this->getIntScalarTypeResolver()]);
    }
    /**
     * Do not allow the "multi-field directives" feature for this directive
     */
    public function getAffectAdditionalFieldsUnderPosArgumentName() : ?string
    {
        return null;
    }
    /**
     * @param \PoP\ComponentModel\TypeResolvers\RelationalTypeResolverInterface $relationalTypeResolver
     * @param string $directiveArgName
     */
    public function getDirectiveArgDescription($relationalTypeResolver, $directiveArgName) : ?string
    {
        switch ($directiveArgName) {
            case $this->getAffectDirectivesUnderPosArgumentName():
                return $this->__('Positions of the directives to be affected, relative from this one (as an array of positive integers)', 'graphql-server');
            default:
                return parent::getDirectiveArgDescription($relationalTypeResolver, $directiveArgName);
        }
    }
    /**
     * @return mixed
     * @param \PoP\ComponentModel\TypeResolvers\RelationalTypeResolverInterface $relationalTypeResolver
     * @param string $directiveArgName
     */
    public function getDirectiveArgDefaultValue($relationalTypeResolver, $directiveArgName)
    {
        switch ($directiveArgName) {
            case $this->getAffectDirectivesUnderPosArgumentName():
                return $this->getAffectDirectivesUnderPosArgumentDefaultValue();
            default:
                return parent::getDirectiveArgDefaultValue($relationalTypeResolver, $directiveArgName);
        }
    }
    /**
     * @param \PoP\ComponentModel\TypeResolvers\RelationalTypeResolverInterface $relationalTypeResolver
     * @param string $directiveArgName
     */
    public function getDirectiveArgTypeModifiers($relationalTypeResolver, $directiveArgName) : int
    {
        switch ($directiveArgName) {
            case $this->getAffectDirectivesUnderPosArgumentName():
                return SchemaTypeModifiers::MANDATORY | SchemaTypeModifiers::IS_ARRAY | SchemaTypeModifiers::IS_NON_NULLABLE_ITEMS_IN_ARRAY;
            default:
                return parent::getDirectiveArgTypeModifiers($relationalTypeResolver, $directiveArgName);
        }
    }
}
