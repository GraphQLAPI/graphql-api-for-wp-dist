<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\Services\EndpointExecuters;

use GraphQLAPI\GraphQLAPI\Services\EndpointExecuters\EndpointExecuterInterface;
use WP_Post;

interface GraphQLEndpointExecuterInterface extends EndpointExecuterInterface
{
    /**
     * Provide the query to execute and its variables
     *
     * @return array{0:?string,1:?array<string,mixed>} Array of 2 elements: [query, variables]
     * @param \WP_Post|null $graphQLQueryPost
     */
    public function getGraphQLQueryAndVariables($graphQLQueryPost): array;
    /**
     * @param \WP_Post|null $customPost
     */
    public function doURLParamsOverrideGraphQLVariables($customPost): bool;
}
