<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\Services\BlockAccessors;

use GraphQLAPI\GraphQLAPI\AppObjects\BlockAttributes\PersistedQueryEndpointAPIHierarchyBlockAttributes;
use GraphQLAPI\GraphQLAPI\Services\Blocks\PersistedQueryEndpointAPIHierarchyBlock;
use GraphQLAPI\GraphQLAPI\Services\Helpers\BlockHelpers;
use PoP\Root\Services\BasicServiceTrait;
use WP_Post;

class PersistedQueryEndpointAPIHierarchyBlockAccessor
{
    use BasicServiceTrait;

    /**
     * @var \GraphQLAPI\GraphQLAPI\Services\Helpers\BlockHelpers|null
     */
    private $blockHelpers;
    /**
     * @var \GraphQLAPI\GraphQLAPI\Services\Blocks\PersistedQueryEndpointAPIHierarchyBlock|null
     */
    private $persistedQueryEndpointAPIHierarchyBlock;

    /**
     * @param \GraphQLAPI\GraphQLAPI\Services\Helpers\BlockHelpers $blockHelpers
     */
    final public function setBlockHelpers($blockHelpers): void
    {
        $this->blockHelpers = $blockHelpers;
    }
    final protected function getBlockHelpers(): BlockHelpers
    {
        /** @var BlockHelpers */
        return $this->blockHelpers = $this->blockHelpers ?? $this->instanceManager->getInstance(BlockHelpers::class);
    }
    /**
     * @param \GraphQLAPI\GraphQLAPI\Services\Blocks\PersistedQueryEndpointAPIHierarchyBlock $persistedQueryEndpointAPIHierarchyBlock
     */
    final public function setPersistedQueryEndpointAPIHierarchyBlock($persistedQueryEndpointAPIHierarchyBlock): void
    {
        $this->persistedQueryEndpointAPIHierarchyBlock = $persistedQueryEndpointAPIHierarchyBlock;
    }
    final protected function getPersistedQueryEndpointAPIHierarchyBlock(): PersistedQueryEndpointAPIHierarchyBlock
    {
        /** @var PersistedQueryEndpointAPIHierarchyBlock */
        return $this->persistedQueryEndpointAPIHierarchyBlock = $this->persistedQueryEndpointAPIHierarchyBlock ?? $this->instanceManager->getInstance(PersistedQueryEndpointAPIHierarchyBlock::class);
    }

    /**
     * Extract the Persisted Query Options block attributes from the post
     * @param \WP_Post $post
     */
    public function getAttributes($post): ?PersistedQueryEndpointAPIHierarchyBlockAttributes
    {
        $apiHierarchyBlock = $this->getBlockHelpers()->getSingleBlockOfTypeFromCustomPost(
            $post,
            $this->getPersistedQueryEndpointAPIHierarchyBlock()
        );
        // If there is either 0 or more than 1, return nothing
        if ($apiHierarchyBlock === null) {
            return null;
        }
        return new PersistedQueryEndpointAPIHierarchyBlockAttributes($apiHierarchyBlock['attrs'][PersistedQueryEndpointAPIHierarchyBlock::ATTRIBUTE_NAME_INHERIT_QUERY] ?? false);
    }
}
