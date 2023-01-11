<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\Registries;

use GraphQLAPI\GraphQLAPI\Services\Blocks\BlockInterface;

interface BlockRegistryInterface
{
    /**
     * @param \GraphQLAPI\GraphQLAPI\Services\Blocks\BlockInterface $block
     */
    public function addBlock($block): void;
    /**
     * @return BlockInterface[]
     */
    public function getBlocks(): array;
    /**
     * @return BlockInterface[]
     */
    public function getEnabledBlocks(): array;
}
