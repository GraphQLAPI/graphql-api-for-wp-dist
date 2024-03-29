<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\Services\EndpointAnnotators;

use PoP\Root\Services\ServiceInterface;
use WP_Post;

interface EndpointAnnotatorInterface extends ServiceInterface
{
    /**
     * Add actions to the CPT list
     * @param array<string,string> $actions
     * @param \WP_Post $post
     */
    public function addCustomPostTypeTableActions(&$actions, $post): void;
}
