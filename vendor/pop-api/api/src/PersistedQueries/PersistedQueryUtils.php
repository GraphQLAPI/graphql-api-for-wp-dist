<?php

declare (strict_types=1);
namespace PoPAPI\API\PersistedQueries;

class PersistedQueryUtils
{
    /**
     * Trim, and remove tabs and new lines
     * @param string $fragmentResolution
     */
    public static function removeWhitespaces($fragmentResolution) : string
    {
        /** @var string */
        return \preg_replace('/[ ]{2,}|[\\t]|[\\n]/', '', \trim($fragmentResolution));
    }
}
