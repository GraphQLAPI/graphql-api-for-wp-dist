<?php

declare (strict_types=1);
namespace PoPAPI\API\PersistedQueries;

interface PersistedFragmentManagerInterface
{
    /**
     * @return array<string,string>
     */
    public function getPersistedFragments() : array;
    /**
     * @return array<string,array<string,string>>
     */
    public function getPersistedFragmentsForSchema() : array;
    /**
     * @param string $fragmentName
     * @param string $fragmentResolution
     * @param string|null $description
     */
    public function addPersistedFragment($fragmentName, $fragmentResolution, $description = null) : void;
}
