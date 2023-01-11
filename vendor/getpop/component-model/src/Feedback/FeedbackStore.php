<?php

declare (strict_types=1);
namespace PoP\ComponentModel\Feedback;

class FeedbackStore
{
    /**
     * @var \PoP\ComponentModel\Feedback\GeneralFeedbackStore
     */
    public $generalFeedbackStore;
    /**
     * @var \PoP\ComponentModel\Feedback\DocumentFeedbackStore
     */
    public $documentFeedbackStore;
    public function __construct()
    {
        $this->regenerateGeneralFeedbackStore();
        $this->regenerateDocumentFeedbackStore();
    }
    public function regenerateGeneralFeedbackStore() : void
    {
        $this->generalFeedbackStore = new \PoP\ComponentModel\Feedback\GeneralFeedbackStore();
    }
    public function regenerateDocumentFeedbackStore() : void
    {
        $this->documentFeedbackStore = new \PoP\ComponentModel\Feedback\DocumentFeedbackStore();
    }
}
