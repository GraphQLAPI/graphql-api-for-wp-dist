<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\GetterSetterObjects\BlockAttributes;

class PersistedQueryEndpointGraphiQLBlockAttributes
{
    /**
     * @var string
     */
    protected $query;
    /**
     * @var mixed[]
     */
    protected $variables;
    public function __construct(
        string $query,
        /** @var array<string, mixed> */
        array $variables
    )
    {
        $this->query = $query;
        $this->variables = $variables;
    }
    public function getQuery(): string
    {
        return $this->query;
    }

    /** @return array<string, mixed> */
    public function getVariables(): array
    {
        return $this->variables;
    }
}
