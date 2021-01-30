<?php

declare (strict_types=1);
namespace PoP\API\PersistedQueries;

class PersistedQueryManager extends \PoP\API\PersistedQueries\AbstractPersistedQueryManager
{
    protected function addQueryResolutionToSchema() : bool
    {
        return \true;
    }
}
