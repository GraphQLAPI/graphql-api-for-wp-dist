<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\AppObjects\BlockAttributes;

class PersistedQueryEndpointAPIHierarchyBlockAttributes
{
    /**
     * @var bool
     */
    protected $inheritQuery;
    public function __construct(bool $inheritQuery)
    {
        $this->inheritQuery = $inheritQuery;
    }
    public function isInheritQuery(): bool
    {
        return $this->inheritQuery;
    }
}
