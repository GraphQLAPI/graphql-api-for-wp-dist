<?php

declare (strict_types=1);
namespace PoP\CacheControl\Managers;

interface CacheControlManagerInterface
{
    /**
     * @return array<mixed[]>
     */
    public function getEntriesForFields() : array;
    /**
     * @return array<mixed[]>
     */
    public function getEntriesForDirectives() : array;
    /**
     * @param array<mixed[]> $fieldEntries
     */
    public function addEntriesForFields($fieldEntries) : void;
    /**
     * @param array<mixed[]> $directiveEntries
     */
    public function addEntriesForDirectives($directiveEntries) : void;
}
