<?php

declare (strict_types=1);
namespace PoP\ComponentModel\Feedback;

class GeneralFeedbackStore
{
    /** @var GeneralFeedbackInterface[] */
    private $errors = [];
    /** @var GeneralFeedbackInterface[] */
    private $warnings = [];
    /** @var GeneralFeedbackInterface[] */
    private $deprecations = [];
    /** @var GeneralFeedbackInterface[] */
    private $notices = [];
    /** @var GeneralFeedbackInterface[] */
    private $suggestions = [];
    /** @var GeneralFeedbackInterface[] */
    private $logs = [];
    public function getErrorCount() : int
    {
        return \count($this->getErrors());
    }
    /**
     * @return GeneralFeedbackInterface[]
     */
    public function getErrors() : array
    {
        return $this->errors;
    }
    /**
     * @param \PoP\ComponentModel\Feedback\GeneralFeedbackInterface $error
     */
    public function addError($error) : void
    {
        $this->errors[] = $error;
    }
    /**
     * @param GeneralFeedbackInterface[] $errors
     */
    public function setErrors($errors) : void
    {
        $this->errors = $errors;
    }
    /**
     * @return GeneralFeedbackInterface[]
     */
    public function getWarnings() : array
    {
        return $this->warnings;
    }
    /**
     * @param \PoP\ComponentModel\Feedback\GeneralFeedbackInterface $warning
     */
    public function addWarning($warning) : void
    {
        $this->warnings[] = $warning;
    }
    /**
     * @param GeneralFeedbackInterface[] $warnings
     */
    public function setWarnings($warnings) : void
    {
        $this->warnings = $warnings;
    }
    /**
     * @return GeneralFeedbackInterface[]
     */
    public function getDeprecations() : array
    {
        return $this->deprecations;
    }
    /**
     * @param \PoP\ComponentModel\Feedback\GeneralFeedbackInterface $deprecation
     */
    public function addDeprecation($deprecation) : void
    {
        $this->deprecations[] = $deprecation;
    }
    /**
     * @param GeneralFeedbackInterface[] $deprecations
     */
    public function setDeprecations($deprecations) : void
    {
        $this->deprecations = $deprecations;
    }
    /**
     * @return GeneralFeedbackInterface[]
     */
    public function getNotices() : array
    {
        return $this->notices;
    }
    /**
     * @param \PoP\ComponentModel\Feedback\GeneralFeedbackInterface $notice
     */
    public function addNotice($notice) : void
    {
        $this->notices[] = $notice;
    }
    /**
     * @param GeneralFeedbackInterface[] $notices
     */
    public function setNotices($notices) : void
    {
        $this->notices = $notices;
    }
    /**
     * @return GeneralFeedbackInterface[]
     */
    public function getSuggestions() : array
    {
        return $this->suggestions;
    }
    /**
     * @param \PoP\ComponentModel\Feedback\GeneralFeedbackInterface $suggestion
     */
    public function addSuggestion($suggestion) : void
    {
        $this->suggestions[] = $suggestion;
    }
    /**
     * @param GeneralFeedbackInterface[] $suggestions
     */
    public function setSuggestions($suggestions) : void
    {
        $this->suggestions = $suggestions;
    }
    /**
     * @return GeneralFeedbackInterface[]
     */
    public function getLogs() : array
    {
        return $this->logs;
    }
    /**
     * @param \PoP\ComponentModel\Feedback\GeneralFeedbackInterface $log
     */
    public function addLog($log) : void
    {
        $this->logs[] = $log;
    }
    /**
     * @param GeneralFeedbackInterface[] $logs
     */
    public function setLogs($logs) : void
    {
        $this->logs = $logs;
    }
}
