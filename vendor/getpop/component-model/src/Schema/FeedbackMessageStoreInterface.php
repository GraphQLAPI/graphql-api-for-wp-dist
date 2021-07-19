<?php

declare (strict_types=1);
namespace PoP\ComponentModel\Schema;

interface FeedbackMessageStoreInterface extends \PoP\FieldQuery\FeedbackMessageStoreInterface
{
    public function addDBWarnings(array $dbWarnings);
    public function addDBDeprecations(array $dbDeprecations);
    public function addSchemaWarnings(array $schemaWarnings);
    /**
     * @param string|int $resultItemID
     */
    public function retrieveAndClearResultItemDBWarnings($resultItemID) : ?array;
    /**
     * @param string|int $resultItemID
     */
    public function retrieveAndClearResultItemDBDeprecations($resultItemID) : ?array;
    public function addSchemaError(string $dbKey, string $field, string $error);
    public function retrieveAndClearSchemaErrors() : array;
    public function retrieveAndClearSchemaWarnings() : array;
    public function getSchemaErrorsForField(string $dbKey, string $field) : ?array;
    public function addLogEntry(string $entry) : void;
    public function maybeAddLogEntry(string $entry) : void;
    public function getLogEntries() : array;
}
