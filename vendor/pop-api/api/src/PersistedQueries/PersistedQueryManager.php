<?php

declare (strict_types=1);
namespace PoPAPI\API\PersistedQueries;

class PersistedQueryManager extends \PoPAPI\API\PersistedQueries\AbstractPersistedQueryManager
{
    protected function addQueryResolutionToSchema() : bool
    {
        return \true;
    }
}
