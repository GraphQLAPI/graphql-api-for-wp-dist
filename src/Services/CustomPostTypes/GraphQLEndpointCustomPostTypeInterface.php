<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\Services\CustomPostTypes;

use GraphQLAPI\GraphQLAPI\Services\Blocks\BlockInterface;
use WP_Post;

interface GraphQLEndpointCustomPostTypeInterface extends CustomPostTypeInterface
{
    public function getEndpointOptionsBlock(): BlockInterface;

    /**
     * Read the options block and check the value of attribute "isEndpointEnabled"
     * @param \WP_Post|int $postOrID
     */
    public function isEndpointEnabled($postOrID): bool;

    /**
     * @return array<string,mixed>|null Data inside the block is saved as key (string) => value
     * @param \WP_Post|int $postOrID
     */
    public function getOptionsBlockDataItem($postOrID): ?array;
}
