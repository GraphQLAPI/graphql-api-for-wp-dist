<?php

declare (strict_types=1);
namespace PoPAPI\API\PersistedQueries;

interface PersistedQueryManagerInterface
{
    /**
     * @return array<string,string>
     */
    public function getPersistedQueries() : array;
    /**
     * @param string $queryName
     */
    public function getPersistedQuery($queryName) : ?string;
    /**
     * @param string $queryName
     */
    public function hasPersistedQuery($queryName) : bool;
    /**
     * @param string $queryName
     * @param string $queryResolution
     * @param string|null $description
     */
    public function addPersistedQuery($queryName, $queryResolution, $description = null) : void;
    /**
     * @return array<string,array<string,string>>
     */
    public function getPersistedQueriesForSchema() : array;
}
