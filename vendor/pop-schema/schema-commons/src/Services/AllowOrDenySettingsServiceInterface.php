<?php

declare (strict_types=1);
namespace PoPSchema\SchemaCommons\Services;

interface AllowOrDenySettingsServiceInterface
{
    /**
     * Check if the allow/denylist validation fails
     * Compare for full match or regex
     *
     * @param string[] $entries
     * @param string $name
     * @param string $behavior
     */
    public function isEntryAllowed($name, $entries, $behavior) : bool;
}
