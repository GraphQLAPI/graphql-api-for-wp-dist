<?php

declare (strict_types=1);
namespace PoP\ComponentModel\Schema;

use PoP\ComponentModel\Feedback\ObjectTypeFieldResolutionFeedbackStore;
use PoP\GraphQLParser\Spec\Parser\Ast\WithArgumentsInterface;
interface SchemaCastingServiceInterface
{
    /**
     * @param array<string,mixed> $argumentKeyValues
     * @param array<string,array<string,mixed>> $argumentSchemaDefinition
     * @return array<string,mixed>
     * @param \PoP\GraphQLParser\Spec\Parser\Ast\WithArgumentsInterface $withArgumentsAST
     * @param \PoP\ComponentModel\Feedback\ObjectTypeFieldResolutionFeedbackStore $objectTypeFieldResolutionFeedbackStore
     */
    public function castArguments($argumentKeyValues, $argumentSchemaDefinition, $withArgumentsAST, $objectTypeFieldResolutionFeedbackStore) : array;
}
