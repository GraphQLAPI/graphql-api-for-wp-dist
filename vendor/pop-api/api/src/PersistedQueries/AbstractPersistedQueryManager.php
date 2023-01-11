<?php

declare (strict_types=1);
namespace PoPAPI\API\PersistedQueries;

use PoPAPI\API\Schema\SchemaDefinition;
abstract class AbstractPersistedQueryManager implements \PoPAPI\API\PersistedQueries\PersistedQueryManagerInterface
{
    /**
     * @var array<string,string>
     */
    protected $persistedQueries = [];
    /**
     * @var array<string,array<string,string>>
     */
    protected $persistedQueriesForSchema = [];
    /**
     * @return array<string,array<string,string>>
     */
    public function getPersistedQueriesForSchema() : array
    {
        return $this->persistedQueriesForSchema;
    }
    /**
     * @return array<string,string>
     */
    public function getPersistedQueries() : array
    {
        return $this->persistedQueries;
    }
    /**
     * @param string $queryName
     */
    public function hasPersistedQuery($queryName) : bool
    {
        return isset($this->persistedQueries[$queryName]);
    }
    /**
     * @param string $queryName
     */
    public function getPersistedQuery($queryName) : ?string
    {
        return $this->persistedQueries[$queryName];
    }
    /**
     * @param string $queryName
     * @param string $queryResolution
     * @param string|null $description
     */
    public function addPersistedQuery($queryName, $queryResolution, $description = null) : void
    {
        $this->persistedQueries[$queryName] = $queryResolution;
        $this->persistedQueriesForSchema[$queryName] = [SchemaDefinition::NAME => $queryName];
        if ($description) {
            $this->persistedQueriesForSchema[$queryName][SchemaDefinition::DESCRIPTION] = $description;
        }
        if ($this->addQueryResolutionToSchema()) {
            $this->persistedQueriesForSchema[$queryName][SchemaDefinition::FRAGMENT_RESOLUTION] = $queryResolution;
        }
    }
    protected abstract function addQueryResolutionToSchema() : bool;
}
