<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\PluginSkeleton;

abstract class AbstractPluginInfo implements PluginInfoInterface
{
    /**
     * @var array<string,mixed>
     */
    protected $values = [];
    /**
     * @var \GraphQLAPI\GraphQLAPI\PluginSkeleton\PluginInterface
     */
    protected $plugin;

    final public function __construct(
        PluginInterface $plugin
    ) {
        $this->plugin = $plugin;
        $this->initialize();
    }

    abstract protected function initialize(): void;

    /**
     * @return mixed
     * @param string $key
     */
    public function get($key)
    {
        return $this->values[$key] ?? null;
    }
}
