<?php

declare (strict_types=1);
namespace PoP\ComponentModel\Feedback;

use PoP\ComponentModel\TypeResolvers\RelationalTypeResolverInterface;
use PoP\GraphQLParser\Spec\Parser\Ast\FieldInterface;
class SchemaFeedbackStore
{
    /** @var SchemaFeedbackInterface[] */
    private $errors = [];
    /** @var SchemaFeedbackInterface[] */
    private $warnings = [];
    /** @var SchemaFeedbackInterface[] */
    private $deprecations = [];
    /** @var SchemaFeedbackInterface[] */
    private $notices = [];
    /** @var SchemaFeedbackInterface[] */
    private $suggestions = [];
    /** @var SchemaFeedbackInterface[] */
    private $logs = [];
    /**
     * @param \PoP\ComponentModel\Feedback\SchemaFeedbackStore $schemaFeedbackStore
     */
    public function incorporate($schemaFeedbackStore) : void
    {
        $this->errors = \array_merge($this->errors, $schemaFeedbackStore->getErrors());
        $this->warnings = \array_merge($this->warnings, $schemaFeedbackStore->getWarnings());
        $this->deprecations = \array_merge($this->deprecations, $schemaFeedbackStore->getDeprecations());
        $this->notices = \array_merge($this->notices, $schemaFeedbackStore->getNotices());
        $this->suggestions = \array_merge($this->suggestions, $schemaFeedbackStore->getSuggestions());
        $this->logs = \array_merge($this->logs, $schemaFeedbackStore->getLogs());
    }
    /**
     * @param FieldInterface[] $fields
     * @param \PoP\ComponentModel\Feedback\ObjectTypeFieldResolutionFeedbackStore $objectTypeFieldResolutionFeedbackStore
     * @param \PoP\ComponentModel\TypeResolvers\RelationalTypeResolverInterface $relationalTypeResolver
     */
    public function incorporateFromObjectTypeFieldResolutionFeedbackStore($objectTypeFieldResolutionFeedbackStore, $relationalTypeResolver, $fields) : void
    {
        foreach ($objectTypeFieldResolutionFeedbackStore->getErrors() as $objectTypeFieldResolutionFeedbackError) {
            $this->errors[] = \PoP\ComponentModel\Feedback\SchemaFeedback::fromObjectTypeFieldResolutionFeedback($objectTypeFieldResolutionFeedbackError, $relationalTypeResolver, $fields);
        }
        foreach ($objectTypeFieldResolutionFeedbackStore->getWarnings() as $objectTypeFieldResolutionFeedbackWarning) {
            $this->warnings[] = \PoP\ComponentModel\Feedback\SchemaFeedback::fromObjectTypeFieldResolutionFeedback($objectTypeFieldResolutionFeedbackWarning, $relationalTypeResolver, $fields);
        }
        foreach ($objectTypeFieldResolutionFeedbackStore->getDeprecations() as $objectTypeFieldResolutionFeedbackDeprecation) {
            $this->deprecations[] = \PoP\ComponentModel\Feedback\SchemaFeedback::fromObjectTypeFieldResolutionFeedback($objectTypeFieldResolutionFeedbackDeprecation, $relationalTypeResolver, $fields);
        }
        foreach ($objectTypeFieldResolutionFeedbackStore->getNotices() as $objectTypeFieldResolutionFeedbackNotice) {
            $this->notices[] = \PoP\ComponentModel\Feedback\SchemaFeedback::fromObjectTypeFieldResolutionFeedback($objectTypeFieldResolutionFeedbackNotice, $relationalTypeResolver, $fields);
        }
        foreach ($objectTypeFieldResolutionFeedbackStore->getSuggestions() as $objectTypeFieldResolutionFeedbackSuggestion) {
            $this->suggestions[] = \PoP\ComponentModel\Feedback\SchemaFeedback::fromObjectTypeFieldResolutionFeedback($objectTypeFieldResolutionFeedbackSuggestion, $relationalTypeResolver, $fields);
        }
        foreach ($objectTypeFieldResolutionFeedbackStore->getLogs() as $objectTypeFieldResolutionFeedbackLog) {
            $this->logs[] = \PoP\ComponentModel\Feedback\SchemaFeedback::fromObjectTypeFieldResolutionFeedback($objectTypeFieldResolutionFeedbackLog, $relationalTypeResolver, $fields);
        }
    }
    public function getErrorCount() : int
    {
        return \count($this->getErrors());
    }
    /**
     * @return SchemaFeedbackInterface[]
     */
    public function getErrors() : array
    {
        return $this->errors;
    }
    /**
     * @param \PoP\ComponentModel\Feedback\SchemaFeedbackInterface $error
     */
    public function addError($error) : void
    {
        $this->errors[] = $error;
    }
    /**
     * @param SchemaFeedbackInterface[] $errors
     */
    public function setErrors($errors) : void
    {
        $this->errors = $errors;
    }
    /**
     * @return SchemaFeedbackInterface[]
     */
    public function getWarnings() : array
    {
        return $this->warnings;
    }
    /**
     * @param \PoP\ComponentModel\Feedback\SchemaFeedbackInterface $warning
     */
    public function addWarning($warning) : void
    {
        $this->warnings[] = $warning;
    }
    /**
     * @param SchemaFeedbackInterface[] $warnings
     */
    public function setWarnings($warnings) : void
    {
        $this->warnings = $warnings;
    }
    /**
     * @return SchemaFeedbackInterface[]
     */
    public function getDeprecations() : array
    {
        return $this->deprecations;
    }
    /**
     * @param \PoP\ComponentModel\Feedback\SchemaFeedbackInterface $deprecation
     */
    public function addDeprecation($deprecation) : void
    {
        $this->deprecations[] = $deprecation;
    }
    /**
     * @param SchemaFeedbackInterface[] $deprecations
     */
    public function setDeprecations($deprecations) : void
    {
        $this->deprecations = $deprecations;
    }
    /**
     * @return SchemaFeedbackInterface[]
     */
    public function getNotices() : array
    {
        return $this->notices;
    }
    /**
     * @param \PoP\ComponentModel\Feedback\SchemaFeedbackInterface $notice
     */
    public function addNotice($notice) : void
    {
        $this->notices[] = $notice;
    }
    /**
     * @param SchemaFeedbackInterface[] $notices
     */
    public function setNotices($notices) : void
    {
        $this->notices = $notices;
    }
    /**
     * @return SchemaFeedbackInterface[]
     */
    public function getSuggestions() : array
    {
        return $this->suggestions;
    }
    /**
     * @param \PoP\ComponentModel\Feedback\SchemaFeedbackInterface $suggestion
     */
    public function addSuggestion($suggestion) : void
    {
        $this->suggestions[] = $suggestion;
    }
    /**
     * @param SchemaFeedbackInterface[] $suggestions
     */
    public function setSuggestions($suggestions) : void
    {
        $this->suggestions = $suggestions;
    }
    /**
     * @return SchemaFeedbackInterface[]
     */
    public function getLogs() : array
    {
        return $this->logs;
    }
    /**
     * @param \PoP\ComponentModel\Feedback\SchemaFeedbackInterface $log
     */
    public function addLog($log) : void
    {
        $this->logs[] = $log;
    }
    /**
     * @param SchemaFeedbackInterface[] $logs
     */
    public function setLogs($logs) : void
    {
        $this->logs = $logs;
    }
}
