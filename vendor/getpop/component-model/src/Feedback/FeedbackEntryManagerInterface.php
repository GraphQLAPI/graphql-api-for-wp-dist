<?php

declare (strict_types=1);
namespace PoP\ComponentModel\Feedback;

use PoP\GraphQLParser\Spec\Parser\Ast\AstInterface;
use PoP\GraphQLParser\Spec\Parser\Ast\FieldInterface;
use PoP\GraphQLParser\Spec\Parser\Location;
use PoP\Root\Feedback\FeedbackItemResolution;
use SplObjectStorage;
interface FeedbackEntryManagerInterface
{
    /**
     * Add the feedback (errors, warnings, deprecations, notices, etc)
     * into the output.
     *
     * @param array<string,mixed> $data
     * @param array<string,array<string,array<string,SplObjectStorage<FieldInterface,array<string,mixed>>>>> $schemaFeedbackEntries
     * @param array<string,array<string,array<string,SplObjectStorage<FieldInterface,array<string,mixed>>>>> $objectFeedbackEntries
     * @return array<string,mixed>
     */
    public function combineAndAddFeedbackEntries($data, $schemaFeedbackEntries, $objectFeedbackEntries) : array;
    /**
     * @param Location|null $location If `null` use the Location from the astNode
     * @param array<string,mixed> $extensions
     * @param array<string|int> $ids
     * @return array<string,mixed>
     * @param \PoP\GraphQLParser\Spec\Parser\Ast\AstInterface $astNode
     * @param \PoP\Root\Feedback\FeedbackItemResolution $feedbackItemResolution
     */
    public function formatObjectOrSchemaFeedbackCommonEntry($astNode, $location, $extensions, $feedbackItemResolution, $ids) : array;
    /**
     * @param array<string|int,object> $idObjects
     * @param array<string,array<string,array<string,SplObjectStorage<FieldInterface,array<string,mixed>>>>> $objectFeedbackEntries
     * @param \PoP\ComponentModel\Feedback\ObjectResolutionFeedbackStore $objectResolutionFeedbackStore
     */
    public function transferObjectFeedback($idObjects, $objectResolutionFeedbackStore, &$objectFeedbackEntries) : void;
    /**
     * @param array<string,array<string,array<string,SplObjectStorage<FieldInterface,array<string,mixed>>>>> $schemaFeedbackEntries
     * @param \PoP\ComponentModel\Feedback\SchemaFeedbackStore $schemaFeedbackStore
     */
    public function transferSchemaFeedback($schemaFeedbackStore, &$schemaFeedbackEntries) : void;
}
