<?php

declare (strict_types=1);
namespace PoP\ComponentModel\Feedback;

use PoP\Root\Feedback\FeedbackItemResolution;
abstract class AbstractFeedback implements \PoP\ComponentModel\Feedback\FeedbackInterface
{
    /**
     * @var \PoP\Root\Feedback\FeedbackItemResolution
     */
    protected $feedbackItemResolution;
    /**
     * @var array<string, mixed>
     */
    protected $extensions = [];
    /**
     * @param array<string,mixed> $extensions
     */
    public function __construct(FeedbackItemResolution $feedbackItemResolution, array $extensions = [])
    {
        $this->feedbackItemResolution = $feedbackItemResolution;
        $this->extensions = $extensions;
    }
    public function getFeedbackItemResolution() : FeedbackItemResolution
    {
        return $this->feedbackItemResolution;
    }
    /**
     * @return array<string,mixed>
     */
    public function getExtensions() : array
    {
        return $this->extensions;
    }
}
