<?php

declare (strict_types=1);
namespace PoP\ComponentModel\DirectiveResolvers;

use PoP\ComponentModel\AttachableExtensions\AttachableExtensionInterface;
use PoP\ComponentModel\Engine\EngineIterationFieldSet;
use PoP\ComponentModel\Feedback\EngineIterationFeedbackStore;
use PoP\ComponentModel\QueryResolution\FieldDataAccessProviderInterface;
use PoP\ComponentModel\TypeResolvers\InterfaceType\InterfaceTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\RelationalTypeResolverInterface;
use PoP\GraphQLParser\Spec\Parser\Ast\Directive;
use PoP\GraphQLParser\Spec\Parser\Ast\FieldInterface;
use SplObjectStorage;
interface FieldDirectiveResolverInterface extends \PoP\ComponentModel\DirectiveResolvers\DirectiveResolverInterface, AttachableExtensionInterface, \PoP\ComponentModel\DirectiveResolvers\SchemaFieldDirectiveResolverInterface
{
    /**
     * The classes of the ObjectTypeResolvers and/or InterfaceTypeResolvers
     * this DirectiveResolver adds directives to.
     * The list can contain both concrete and abstract classes (in which case all classes
     * extending from them will be selected)
     *
     * @return array<class-string<InterfaceTypeResolverInterface|RelationalTypeResolverInterface>>
     */
    public function getRelationalTypeOrInterfaceTypeResolverClassesToAttachTo() : array;
    /**
     * Validate and initialize the Directive, such as adding
     * the default values for Arguments which were not provided
     * in the query.
     *
     * @param FieldInterface[] $fields
     * @param \PoP\ComponentModel\TypeResolvers\RelationalTypeResolverInterface $relationalTypeResolver
     * @param \PoP\ComponentModel\Feedback\EngineIterationFeedbackStore $engineIterationFeedbackStore
     */
    public function prepareDirective($relationalTypeResolver, $fields, $engineIterationFeedbackStore) : void;
    /**
     * After calling `prepareDirective`, indicate if casting
     * the Directive Arguments produced any error.
     */
    public function hasValidationErrors() : bool;
    /**
     * Indicate to what fieldNames this directive can be applied.
     * Returning an empty array means all of them
     *
     * @return string[]
     */
    public function getFieldNamesToApplyTo() : array;
    /**
     * Directives can be either of type "Schema" or "Query" and,
     * depending on one case or the other, might be exposed to the user.
     * By default, use the Query type
     */
    public function getDirectiveKind() : string;
    /**
     * The FieldDirectiveResolver can handle Field Directives and,
     * in addition, Operation Directives.
     *
     * This method indicates the behavior of the FieldDirectiveResolver,
     * indicating one of the following:
     *
     * - Behave as Field (default)
     * - Behave as Field and Operation
     * - Behave as Operation
     *
     * Based on this value, the Directive Locations will be reflected
     * as defined by the GraphQL spec.
     */
    public function getFieldDirectiveBehavior() : string;
    /**
     * Define where to place the directive in the directive execution pipeline
     *
     * 2 directives are mandatory, and executed in this order:
     *
     *   1. ResolveAndMerge: to resolve the field and place the data into the DB object
     *   2. SerializeLeafOutputTypeValues: to serialize Scalar and Enum Type values
     *
     * All other directives must indicate where to position themselves,
     * using these 2 directives as anchors.
     *
     * There are 6 positions:
     *
     *   1. At the very beginning
     *   2. Before the Validate directive
     *   3. Between the Validate and Resolve directives
     *   4. Between the Resolve and Serialize directives
     *   5. After the Serialize directive
     *   6. At the very end
     *
     * In the "serialize" step, the directive takes the objects
     * stored in $resolvedIDFieldValues, such as a DateTime object,
     * and converts them to string for printing in the response.
     */
    public function getPipelinePosition() : string;
    /**
     * This is the equivalent to `__invoke` in League\Pipeline\StageInterface
     *
     * @param mixed[] $payload
     * @return mixed[]
     */
    public function resolveDirectivePipelinePayload($payload) : array;
    /**
     * Indicate if the directiveResolver can process the directive with the given name and args
     * @param \PoP\ComponentModel\TypeResolvers\RelationalTypeResolverInterface $relationalTypeResolver
     * @param \PoP\GraphQLParser\Spec\Parser\Ast\Directive $directive
     */
    public function resolveCanProcessDirective($relationalTypeResolver, $directive) : bool;
    /**
     * Indicate if the directiveResolver can process the directive with the given name and args
     * @param \PoP\ComponentModel\TypeResolvers\RelationalTypeResolverInterface $relationalTypeResolver
     * @param \PoP\GraphQLParser\Spec\Parser\Ast\FieldInterface $field
     */
    public function resolveCanProcessField($relationalTypeResolver, $field) : bool;
    /**
     * Indicate if the directive needs to be passed $idFieldSet filled with data to be able to execute
     */
    public function needsSomeIDFieldToExecute() : bool;
    /**
     * Execute the directive
     *
     * @param array<string|int,EngineIterationFieldSet> $idFieldSet
     * @param array<array<string|int,EngineIterationFieldSet>> $succeedingPipelineIDFieldSet
     * @param array<string|int,object> $idObjects
     * @param array<FieldDataAccessProviderInterface> $succeedingPipelineFieldDataAccessProviders
     * @param array<string,array<string|int,SplObjectStorage<FieldInterface,mixed>>> $previouslyResolvedIDFieldValues
     * @param array<string|int,SplObjectStorage<FieldInterface,mixed>> $resolvedIDFieldValues
     * @param array<\PoP\ComponentModel\DirectiveResolvers\FieldDirectiveResolverInterface> $succeedingPipelineFieldDirectiveResolvers
     * @param array<string,array<string|int,SplObjectStorage<FieldInterface,array<string|int>>>> $unionTypeOutputKeyIDs
     * @param array<string,mixed> $messages
     * @param \PoP\ComponentModel\TypeResolvers\RelationalTypeResolverInterface $relationalTypeResolver
     * @param \PoP\ComponentModel\QueryResolution\FieldDataAccessProviderInterface $fieldDataAccessProvider
     * @param \PoP\ComponentModel\Feedback\EngineIterationFeedbackStore $engineIterationFeedbackStore
     */
    public function resolveDirective($relationalTypeResolver, $idFieldSet, $fieldDataAccessProvider, $succeedingPipelineFieldDirectiveResolvers, $idObjects, $unionTypeOutputKeyIDs, $previouslyResolvedIDFieldValues, &$succeedingPipelineIDFieldSet, &$succeedingPipelineFieldDataAccessProviders, &$resolvedIDFieldValues, &$messages, $engineIterationFeedbackStore) : void;
    /**
     * A directive can decide to not be added to the schema, eg: when it is repeated/implemented several times
     * @param \PoP\ComponentModel\TypeResolvers\RelationalTypeResolverInterface $relationalTypeResolver
     */
    public function skipExposingDirectiveInSchema($relationalTypeResolver) : bool;
    /**
     * @param \PoP\ComponentModel\TypeResolvers\RelationalTypeResolverInterface $relationalTypeResolver
     * @param string $directiveArgName
     */
    public function skipExposingDirectiveArgInSchema($relationalTypeResolver, $directiveArgName) : bool;
    /**
     * @return array<string,mixed>
     * @param \PoP\ComponentModel\TypeResolvers\RelationalTypeResolverInterface $relationalTypeResolver
     */
    public function getDirectiveSchemaDefinition($relationalTypeResolver) : array;
    /**
     * The version of the directive, using semantic versioning
     * @param \PoP\ComponentModel\TypeResolvers\RelationalTypeResolverInterface $relationalTypeResolver
     */
    public function getDirectiveVersion($relationalTypeResolver) : ?string;
    /**
     * @param \PoP\ComponentModel\TypeResolvers\RelationalTypeResolverInterface $relationalTypeResolver
     */
    public function hasDirectiveVersion($relationalTypeResolver) : bool;
    /**
     * @param \PoP\ComponentModel\TypeResolvers\RelationalTypeResolverInterface $relationalTypeResolver
     */
    public function getDirectiveDeprecationMessage($relationalTypeResolver) : ?string;
    /**
     * Name for the directive arg to indicate which additional fields
     * must be affected by the directive, by indicating their relative position.
     *
     * Eg: { posts { excerpt content @strTranslate(affectAdditionalFieldsUnderPos: [1]) } }
     *
     * @return string Name of the directiveArg, or `null` to disable this feature for the directive
     */
    public function getAffectAdditionalFieldsUnderPosArgumentName() : ?string;
}
