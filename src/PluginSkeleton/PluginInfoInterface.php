<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\PluginSkeleton;

interface PluginInfoInterface
{
    /**
     * @return mixed
     * @param string $key
     */
    public function get($key);
}
