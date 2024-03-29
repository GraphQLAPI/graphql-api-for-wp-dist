<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\Services\Blocks;

interface EditorBlockInterface extends BlockInterface
{
    public function getBlockPriority(): int;
}
