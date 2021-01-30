<?php

declare (strict_types=1);
namespace GraphQLByPoP\GraphQLRequest\PersistedQueries;

use PoP\API\PersistedQueries\AbstractPersistedQueryManager;
class GraphQLPersistedQueryManager extends \PoP\API\PersistedQueries\AbstractPersistedQueryManager implements \GraphQLByPoP\GraphQLRequest\PersistedQueries\GraphQLPersistedQueryManagerInterface
{
    protected function addQueryResolutionToSchema() : bool
    {
        return \false;
    }
}
