<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\AppObjects\BlockAttributes;

class PersistedQueryEndpointGraphiQLBlockAttributes
{
    /**
     * @var string
     */
    protected $query;
    /**
     * @var array<string, mixed>
     */
    protected $variables;
    /**
     * @param array<string,mixed> $variables
     */
    public function __construct(string $query, array $variables)
    {
        $this->query = $query;
        $this->variables = $variables;
    }
    public function getQuery(): string
    {
        return $this->query;
    }

    /** @return array<string,mixed> */
    public function getVariables(): array
    {
        return $this->variables;
    }
}
