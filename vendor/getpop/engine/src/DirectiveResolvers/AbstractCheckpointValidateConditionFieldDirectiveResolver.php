<?php

declare (strict_types=1);
namespace PoP\Engine\DirectiveResolvers;

use PoP\ComponentModel\Checkpoints\CheckpointInterface;
use PoP\ComponentModel\Engine\EngineInterface;
use PoP\ComponentModel\Engine\EngineIterationFieldSet;
use PoP\ComponentModel\Feedback\EngineIterationFeedbackStore;
use PoP\ComponentModel\TypeResolvers\RelationalTypeResolverInterface;
use PoP\GraphQLParser\Spec\Parser\Ast\FieldInterface;
use SplObjectStorage;
abstract class AbstractCheckpointValidateConditionFieldDirectiveResolver extends \PoP\Engine\DirectiveResolvers\AbstractValidateConditionFieldDirectiveResolver
{
    /**
     * @var \PoP\ComponentModel\Engine\EngineInterface|null
     */
    private $engine;
    /**
     * @param \PoP\ComponentModel\Engine\EngineInterface $engine
     */
    public final function setEngine($engine) : void
    {
        $this->engine = $engine;
    }
    protected final function getEngine() : EngineInterface
    {
        /** @var EngineInterface */
        return $this->engine = $this->engine ?? $this->instanceManager->getInstance(EngineInterface::class);
    }
    /**
     * @param array<string|int,EngineIterationFieldSet> $idFieldSet
     * @param array<string|int,SplObjectStorage<FieldInterface,mixed>> $resolvedIDFieldValues
     * @param array<array<string|int,EngineIterationFieldSet>> $succeedingPipelineIDFieldSet
     * @param \PoP\ComponentModel\TypeResolvers\RelationalTypeResolverInterface $relationalTypeResolver
     * @param \PoP\ComponentModel\Feedback\EngineIterationFeedbackStore $engineIterationFeedbackStore
     */
    protected function isValidationSuccessful($relationalTypeResolver, $idFieldSet, &$succeedingPipelineIDFieldSet, &$resolvedIDFieldValues, $engineIterationFeedbackStore) : bool
    {
        $checkpoints = $this->getValidationCheckpoints($relationalTypeResolver);
        $feedbackItemResolution = $this->getEngine()->validateCheckpoints($checkpoints);
        return $feedbackItemResolution === null;
    }
    /**
     * Provide the checkpoint to validate
     *
     * @return CheckpointInterface[]
     * @param \PoP\ComponentModel\TypeResolvers\RelationalTypeResolverInterface $relationalTypeResolver
     */
    protected abstract function getValidationCheckpoints($relationalTypeResolver) : array;
}
