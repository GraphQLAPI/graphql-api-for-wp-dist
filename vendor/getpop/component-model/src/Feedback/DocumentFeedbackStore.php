<?php

declare (strict_types=1);
namespace PoP\ComponentModel\Feedback;

class DocumentFeedbackStore
{
    /** @var DocumentFeedbackInterface[] */
    private $errors = [];
    /** @var DocumentFeedbackInterface[] */
    private $warnings = [];
    /** @var DocumentFeedbackInterface[] */
    private $deprecations = [];
    /** @var DocumentFeedbackInterface[] */
    private $notices = [];
    /** @var DocumentFeedbackInterface[] */
    private $suggestions = [];
    /** @var DocumentFeedbackInterface[] */
    private $logs = [];
    public function getErrorCount() : int
    {
        return \count($this->getErrors());
    }
    /**
     * @return DocumentFeedbackInterface[]
     */
    public function getErrors() : array
    {
        return $this->errors;
    }
    /**
     * @param \PoP\ComponentModel\Feedback\DocumentFeedbackInterface $error
     */
    public function addError($error) : void
    {
        $this->errors[] = $error;
    }
    /**
     * @param DocumentFeedbackInterface[] $errors
     */
    public function setErrors($errors) : void
    {
        $this->errors = $errors;
    }
    /**
     * @return DocumentFeedbackInterface[]
     */
    public function getWarnings() : array
    {
        return $this->warnings;
    }
    /**
     * @param \PoP\ComponentModel\Feedback\DocumentFeedbackInterface $warning
     */
    public function addWarning($warning) : void
    {
        $this->warnings[] = $warning;
    }
    /**
     * @param DocumentFeedbackInterface[] $warnings
     */
    public function setWarnings($warnings) : void
    {
        $this->warnings = $warnings;
    }
    /**
     * @return DocumentFeedbackInterface[]
     */
    public function getDeprecations() : array
    {
        return $this->deprecations;
    }
    /**
     * @param \PoP\ComponentModel\Feedback\DocumentFeedbackInterface $deprecation
     */
    public function addDeprecation($deprecation) : void
    {
        $this->deprecations[] = $deprecation;
    }
    /**
     * @param DocumentFeedbackInterface[] $deprecations
     */
    public function setDeprecations($deprecations) : void
    {
        $this->deprecations = $deprecations;
    }
    /**
     * @return DocumentFeedbackInterface[]
     */
    public function getNotices() : array
    {
        return $this->notices;
    }
    /**
     * @param \PoP\ComponentModel\Feedback\DocumentFeedbackInterface $notice
     */
    public function addNotice($notice) : void
    {
        $this->notices[] = $notice;
    }
    /**
     * @param DocumentFeedbackInterface[] $notices
     */
    public function setNotices($notices) : void
    {
        $this->notices = $notices;
    }
    /**
     * @return DocumentFeedbackInterface[]
     */
    public function getSuggestions() : array
    {
        return $this->suggestions;
    }
    /**
     * @param \PoP\ComponentModel\Feedback\DocumentFeedbackInterface $suggestion
     */
    public function addSuggestion($suggestion) : void
    {
        $this->suggestions[] = $suggestion;
    }
    /**
     * @param DocumentFeedbackInterface[] $suggestions
     */
    public function setSuggestions($suggestions) : void
    {
        $this->suggestions = $suggestions;
    }
    /**
     * @return DocumentFeedbackInterface[]
     */
    public function getLogs() : array
    {
        return $this->logs;
    }
    /**
     * @param \PoP\ComponentModel\Feedback\DocumentFeedbackInterface $log
     */
    public function addLog($log) : void
    {
        $this->logs[] = $log;
    }
    /**
     * @param DocumentFeedbackInterface[] $logs
     */
    public function setLogs($logs) : void
    {
        $this->logs = $logs;
    }
}
