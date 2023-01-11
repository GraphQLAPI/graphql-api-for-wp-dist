<?php

declare (strict_types=1);
namespace PoP\ComponentModel\Feedback;

class EngineIterationFeedbackStore
{
    /**
     * @var \PoP\ComponentModel\Feedback\SchemaFeedbackStore
     */
    public $schemaFeedbackStore;
    /**
     * @var \PoP\ComponentModel\Feedback\ObjectResolutionFeedbackStore
     */
    public $objectResolutionFeedbackStore;
    public function __construct()
    {
        $this->schemaFeedbackStore = new \PoP\ComponentModel\Feedback\SchemaFeedbackStore();
        $this->objectResolutionFeedbackStore = new \PoP\ComponentModel\Feedback\ObjectResolutionFeedbackStore();
    }
    /**
     * @param \PoP\ComponentModel\Feedback\EngineIterationFeedbackStore $engineIterationFeedbackStore
     */
    public function incorporate($engineIterationFeedbackStore) : void
    {
        $this->schemaFeedbackStore->incorporate($engineIterationFeedbackStore->schemaFeedbackStore);
        $this->objectResolutionFeedbackStore->incorporate($engineIterationFeedbackStore->objectResolutionFeedbackStore);
    }
    public function hasErrors() : bool
    {
        return $this->schemaFeedbackStore->getErrors() !== [] || $this->objectResolutionFeedbackStore->getErrors() !== [];
    }
    public function getErrorCount() : int
    {
        return $this->schemaFeedbackStore->getErrorCount() + $this->objectResolutionFeedbackStore->getErrorCount();
    }
}
