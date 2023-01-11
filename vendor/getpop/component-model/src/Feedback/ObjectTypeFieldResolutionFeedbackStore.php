<?php

declare (strict_types=1);
namespace PoP\ComponentModel\Feedback;

class ObjectTypeFieldResolutionFeedbackStore
{
    /** @var ObjectTypeFieldResolutionFeedbackInterface[] */
    private $errors = [];
    /** @var ObjectTypeFieldResolutionFeedbackInterface[] */
    private $warnings = [];
    /** @var ObjectTypeFieldResolutionFeedbackInterface[] */
    private $deprecations = [];
    /** @var ObjectTypeFieldResolutionFeedbackInterface[] */
    private $notices = [];
    /** @var ObjectTypeFieldResolutionFeedbackInterface[] */
    private $suggestions = [];
    /** @var ObjectTypeFieldResolutionFeedbackInterface[] */
    private $logs = [];
    /**
     * @param \PoP\ComponentModel\Feedback\ObjectTypeFieldResolutionFeedbackStore $objectTypeFieldResolutionFeedbackStore
     */
    public function incorporate($objectTypeFieldResolutionFeedbackStore) : void
    {
        $this->errors = \array_merge($this->errors, $objectTypeFieldResolutionFeedbackStore->getErrors());
        $this->warnings = \array_merge($this->warnings, $objectTypeFieldResolutionFeedbackStore->getWarnings());
        $this->deprecations = \array_merge($this->deprecations, $objectTypeFieldResolutionFeedbackStore->getDeprecations());
        $this->notices = \array_merge($this->notices, $objectTypeFieldResolutionFeedbackStore->getNotices());
        $this->suggestions = \array_merge($this->suggestions, $objectTypeFieldResolutionFeedbackStore->getSuggestions());
        $this->logs = \array_merge($this->logs, $objectTypeFieldResolutionFeedbackStore->getLogs());
    }
    public function getErrorCount() : int
    {
        return \count($this->getErrors());
    }
    /**
     * @return ObjectTypeFieldResolutionFeedbackInterface[]
     */
    public function getErrors() : array
    {
        return $this->errors;
    }
    /**
     * @param \PoP\ComponentModel\Feedback\ObjectTypeFieldResolutionFeedbackInterface $error
     */
    public function addError($error) : void
    {
        $this->errors[] = $error;
    }
    /**
     * @param ObjectTypeFieldResolutionFeedbackInterface[] $errors
     */
    public function setErrors($errors) : void
    {
        $this->errors = $errors;
    }
    /**
     * @return ObjectTypeFieldResolutionFeedbackInterface[]
     */
    public function getWarnings() : array
    {
        return $this->warnings;
    }
    /**
     * @param \PoP\ComponentModel\Feedback\ObjectTypeFieldResolutionFeedbackInterface $warning
     */
    public function addWarning($warning) : void
    {
        $this->warnings[] = $warning;
    }
    /**
     * @param ObjectTypeFieldResolutionFeedbackInterface[] $warnings
     */
    public function setWarnings($warnings) : void
    {
        $this->warnings = $warnings;
    }
    /**
     * @return ObjectTypeFieldResolutionFeedbackInterface[]
     */
    public function getDeprecations() : array
    {
        return $this->deprecations;
    }
    /**
     * @param \PoP\ComponentModel\Feedback\ObjectTypeFieldResolutionFeedbackInterface $deprecation
     */
    public function addDeprecation($deprecation) : void
    {
        $this->deprecations[] = $deprecation;
    }
    /**
     * @param ObjectTypeFieldResolutionFeedbackInterface[] $deprecations
     */
    public function setDeprecations($deprecations) : void
    {
        $this->deprecations = $deprecations;
    }
    /**
     * @return ObjectTypeFieldResolutionFeedbackInterface[]
     */
    public function getNotices() : array
    {
        return $this->notices;
    }
    /**
     * @param \PoP\ComponentModel\Feedback\ObjectTypeFieldResolutionFeedbackInterface $notice
     */
    public function addNotice($notice) : void
    {
        $this->notices[] = $notice;
    }
    /**
     * @param ObjectTypeFieldResolutionFeedbackInterface[] $notices
     */
    public function setNotices($notices) : void
    {
        $this->notices = $notices;
    }
    /**
     * @return ObjectTypeFieldResolutionFeedbackInterface[]
     */
    public function getSuggestions() : array
    {
        return $this->suggestions;
    }
    /**
     * @param \PoP\ComponentModel\Feedback\ObjectTypeFieldResolutionFeedbackInterface $suggestion
     */
    public function addSuggestion($suggestion) : void
    {
        $this->suggestions[] = $suggestion;
    }
    /**
     * @param ObjectTypeFieldResolutionFeedbackInterface[] $suggestions
     */
    public function setSuggestions($suggestions) : void
    {
        $this->suggestions = $suggestions;
    }
    /**
     * @return ObjectTypeFieldResolutionFeedbackInterface[]
     */
    public function getLogs() : array
    {
        return $this->logs;
    }
    /**
     * @param \PoP\ComponentModel\Feedback\ObjectTypeFieldResolutionFeedbackInterface $log
     */
    public function addLog($log) : void
    {
        $this->logs[] = $log;
    }
    /**
     * @param ObjectTypeFieldResolutionFeedbackInterface[] $logs
     */
    public function setLogs($logs) : void
    {
        $this->logs = $logs;
    }
}
