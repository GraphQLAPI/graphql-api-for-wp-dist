<?php

declare (strict_types=1);
namespace PoPSchema\SchemaCommons\Services;

use PoPSchema\SchemaCommons\Constants\Behaviors;
class AllowOrDenySettingsService implements \PoPSchema\SchemaCommons\Services\AllowOrDenySettingsServiceInterface
{
    /**
     * Check if the allow/denylist validation fails
     * Compare for full match or regex
     *
     * @param string[] $entries
     * @param string $name
     * @param string $behavior
     */
    public function isEntryAllowed($name, $entries, $behavior) : bool
    {
        if ($entries === []) {
            return $behavior === Behaviors::DENY;
        }
        $matchResults = \array_filter(\array_map(function (string $termOrRegex) use($name) : bool {
            // Check if it is a regex expression
            if (\strncmp($termOrRegex, '/', \strlen('/')) === 0 && \substr_compare($termOrRegex, '/', -\strlen('/')) === 0 || \strncmp($termOrRegex, '#', \strlen('#')) === 0 && \substr_compare($termOrRegex, '#', -\strlen('#')) === 0) {
                return \preg_match($termOrRegex, $name) === 1;
            }
            // Check it's a full match
            return $termOrRegex === $name;
        }, $entries));
        if ($behavior == Behaviors::ALLOW && \count($matchResults) === 0 || $behavior == Behaviors::DENY && \count($matchResults) > 0) {
            return \false;
        }
        return \true;
    }
}
