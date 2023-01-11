<?php

declare (strict_types=1);
namespace PoP\ComponentModel\TypeResolvers\ObjectType;

use PoP\ComponentModel\Feedback\EngineIterationFeedbackStore;
use PoP\ComponentModel\FieldResolvers\ObjectType\ObjectTypeFieldResolverInterface;
use PoP\ComponentModel\MutationResolvers\MutationResolverInterface;
use PoP\ComponentModel\QueryResolution\FieldDataAccessorInterface;
use PoP\ComponentModel\TypeResolvers\ConcreteTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\OutputTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\RelationalTypeResolverInterface;
use PoP\GraphQLParser\Spec\Parser\Ast\Directive;
use PoP\GraphQLParser\Spec\Parser\Ast\FieldInterface;
use PoP\Root\Feedback\FeedbackItemResolution;
use SplObjectStorage;
interface ObjectTypeResolverInterface extends RelationalTypeResolverInterface, OutputTypeResolverInterface
{
    /**
     * @return array<string,mixed>
     * @param \PoP\GraphQLParser\Spec\Parser\Ast\FieldInterface $field
     */
    public function getFieldSchemaDefinition($field) : ?array;
    /**
     * @param \PoP\GraphQLParser\Spec\Parser\Ast\FieldInterface $field
     */
    public function hasObjectTypeFieldResolversForField($field) : bool;
    /**
     * @param \PoP\GraphQLParser\Spec\Parser\Ast\FieldInterface $field
     */
    public function getFieldTypeResolver($field) : ?ConcreteTypeResolverInterface;
    /**
     * @param \PoP\GraphQLParser\Spec\Parser\Ast\FieldInterface $field
     */
    public function getFieldTypeModifiers($field) : ?int;
    /**
     * @param \PoP\GraphQLParser\Spec\Parser\Ast\FieldInterface $field
     */
    public function getFieldMutationResolver($field) : ?MutationResolverInterface;
    /**
     * @param \PoP\GraphQLParser\Spec\Parser\Ast\FieldInterface|string $fieldOrFieldName
     */
    public function isFieldAMutation($fieldOrFieldName) : ?bool;
    /**
     * @return array<string,Directive[]>
     */
    public function getAllMandatoryDirectivesForFields() : array;
    /**
     * The "executable" FieldResolver is the first one in the list
     * for each field, as according to their priority.
     *
     * @return array<string,ObjectTypeFieldResolverInterface> Key: fieldName, Value: FieldResolver
     * @param bool $global
     */
    public function getExecutableObjectTypeFieldResolversByField($global) : array;
    /**
     * The list of all the FieldResolvers that resolve each field, for
     * every fieldName
     *
     * @return array<string,ObjectTypeFieldResolverInterface[]> Key: fieldName, Value: List of FieldResolvers
     * @param bool $global
     */
    public function getObjectTypeFieldResolversByField($global) : array;
    /**
     * Get the first FieldResolver that resolves the field
     * @param \PoP\GraphQLParser\Spec\Parser\Ast\FieldInterface|string $fieldOrFieldName
     */
    public function getExecutableObjectTypeFieldResolverForField($fieldOrFieldName) : ?ObjectTypeFieldResolverInterface;
    /**
     * @param array<string,mixed> $fieldArgs
     * @param \PoP\GraphQLParser\Spec\Parser\Ast\FieldInterface $field
     */
    public function createFieldDataAccessor($field, $fieldArgs) : FieldDataAccessorInterface;
    /**
     * Handle case:
     *
     * 1. Data from a Field in an ObjectTypeResolver: a single instance of the
     *    FieldArgs will satisfy all queried objects, since the same schema applies
     *    to all of them.
     *
     * @return SplObjectStorage<ObjectTypeResolverInterface,SplObjectStorage<object,array<string,mixed>>>|null null if there was an error casting the fieldArgs
     * @param \PoP\GraphQLParser\Spec\Parser\Ast\FieldInterface $field
     * @param \PoP\ComponentModel\Feedback\EngineIterationFeedbackStore $engineIterationFeedbackStore
     */
    public function getWildcardObjectTypeResolverObjectFieldData($field, $engineIterationFeedbackStore) : ?SplObjectStorage;
    /**
     * Handle case:
     *
     * 3. Data for a specific object: When executing nested mutations, the FieldArgs
     *    for each object will be different, as it will contain implicit information
     *    belonging to the object.
     *    For instance, when querying `mutation { posts { update(title: "New title") { id } } }`,
     *    the value for the `$postID` is injected into the FieldArgs for each object,
     *    and the validation of the FieldArgs must also be executed for each object.
     *
     * @param array<string|int> $objectIDs
     * @param array<string|int,object> $idObjects
     * @return SplObjectStorage<ObjectTypeResolverInterface,SplObjectStorage<object,array<string,mixed>>>|null null if there was an error casting the fieldArgs
     * @param \PoP\GraphQLParser\Spec\Parser\Ast\FieldInterface $field
     * @param \PoP\ComponentModel\Feedback\EngineIterationFeedbackStore $engineIterationFeedbackStore
     */
    public function getIndependentObjectTypeResolverObjectFieldData($field, $objectIDs, $idObjects, $engineIterationFeedbackStore) : ?SplObjectStorage;
    /**
     * The mutation resolver might expect to receive the data properties
     * directly (eg: "title", "content" and "status"), and these may be
     * contained under a subproperty (eg: "input") from the original fieldArgs.
     * @param \PoP\ComponentModel\QueryResolution\FieldDataAccessorInterface $fieldDataAccessor
     */
    public function getFieldDataAccessorForMutation($fieldDataAccessor) : FieldDataAccessorInterface;
    /**
     * Provide a different error message if a particular version was requested,
     * or if not.
     * @param \PoP\GraphQLParser\Spec\Parser\Ast\FieldInterface $field
     */
    public function getFieldNotResolvedByObjectTypeFeedbackItemResolution($field) : FeedbackItemResolution;
}
