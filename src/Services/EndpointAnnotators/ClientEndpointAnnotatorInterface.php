<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\Services\EndpointAnnotators;

use WP_Post;

interface ClientEndpointAnnotatorInterface extends EndpointAnnotatorInterface
{
    /**
     * @param \WP_Post|int $postOrID
     */
    public function isClientEnabled($postOrID): bool;
}
